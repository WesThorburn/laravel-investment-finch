<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Portfolio;
use App\Models\PortfolioStock;
use App\Http\Controllers\Controller;

class PortfolioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userPortfolios = Portfolio::belongingToCurrentUser()->get();
        if($userPortfolios->count() > 0){
            return redirect('user/portfolio/'.$userPortfolios->first()->id);
        }
        return redirect('user/portfolio/0');
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'portfolioName' => 'required|string|max:64'
        ]);

        if(Portfolio::belongingToCurrentUser()->withName($request->portfolioName)->first()){
            \Session::flash('portfolioNameError', 'You already have a portfolio with the same name!');
            return redirect()->back();
        }

        $id = \DB::table('portfolios')->insertGetId([
            'user_id' => \Auth::user()->id,
            'portfolio_name' => $request->portfolioName,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);

        \Session::flash('portfolioCreateSuccess', 'Your Portfolio was created successfully!');
        return redirect('user/portfolio/'.$id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //Check portfolio requested belongs to current user
        if($id == 0 || Portfolio::where('id', $id)->pluck('user_id') == \Auth::user()->id){
            return view('pages.user.portfolio')->with([            
                'portfolios' => Portfolio::select('id', 'portfolio_name')->where('user_id', \Auth::user()->id)->get(),
                'selectedPortfolio' => Portfolio::select('id', 'portfolio_name')->where('id', $id)->first(),
                'trades' => \DB::table('trades')->where('user_id', \Auth::user()->id)->orderBy('date')->get(),
                'stocksInSelectedPortfolio' => \DB::table('portfolio_stocks')
                    ->join('stock_metrics', 'portfolio_stocks.stock_code', '=', 'stock_metrics.stock_code')
                    ->select(
                        'portfolio_stocks.portfolio_id', 
                        'portfolio_stocks.stock_code', 
                        'portfolio_stocks.purchase_price', 
                        'portfolio_stocks.quantity',
                        'stock_metrics.last_trade',
                        'stock_metrics.day_change'
                        )
                    ->where('portfolio_stocks.portfolio_id', $id)
                    ->get()
            ]);
        }
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Check portfolio belongs to current user
        if(Portfolio::where('id', $id)->pluck('user_id') == \Auth::user()->id){
            if($request->tradeType == "buy"){
                $this->buy($request, $id);
            }
            elseif($request->tradeType == "sell"){
                $this->sell($request, $id);
            }
            return redirect()->back();
        }
        \Session::flash($request->tradeType.'PortfolioError', 'There was an error with your request!');
        return redirect()->back();
    }

    private function buy(Request $request, $id){
        $this->validate($request, [
            'purchaseStockCode' => 'required|string|max:3',
            'purchasePrice' => 'required|regex:/^\d*(\.\d{1,3})?$/',
            'purchaseQuantity' => 'required|integer|min:1',
            'purchaseBrokerage' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'purchaseDate' => 'required|date'
        ]);

        $this->recordTrade(\Auth::user()->id, 'buy', $request->purchaseStockCode, $request->purchasePrice, $request->purchaseQuantity, $request->purchaseBrokerage,$request->purchaseDate);

        if(PortfolioStock::stockIsInPortfolio($request->purchaseStockCode, $id)){
            $this->ammendPosition($request, $id);
        }
        else{
            //Add stock to portfolio
            $portfolioStock = new PortfolioStock;
            $portfolioStock->portfolio_id = $id;
            $portfolioStock->stock_code = $request->purchaseStockCode;
            $portfolioStock->purchase_price = (($request->purchasePrice*$request->purchaseQuantity)+$request->purchaseBrokerage)/$request->purchaseQuantity;
            $portfolioStock->quantity = $request->purchaseQuantity;
            $portfolioStock->save();
            
        }
        \Session::flash('addStockToPortfolioSuccess', $request->purchaseStockCode.' was added to your Portfolio successfully!');
        return redirect('user/portfolio/'.$id);
    }

    private function sell(Request $request, $id){
        $this->validate($request, [
            'saleStockCode' => 'required|string|max:3',
            'salePrice' => 'required|regex:/^\d*(\.\d{1,3})?$/',
            'saleQuantity' => 'required|integer|min:1',
            'saleBrokerage' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'saleDate' => 'required|date'
        ]);

        //Check if stock already exists in portfolio
        if(!\DB::table('portfolio_stocks')->where(['portfolio_id' => $id, 'stock_code' => $request->saleStockCode])->first()){
            \Session::flash('sellPortfolioError', "You currently don't own ".$request->saleStockCode.' therefore, you cannot sell it!');
            return redirect('user/portfolio/'.$id);
        }

        //Retreive existing stock record
        $stockInPortfolio = \DB::table('portfolio_stocks')->where(['portfolio_id' => $id, 'stock_code' => $request->saleStockCode])->first();

        //Check if sell quantity exists owned quantity
        if($stockInPortfolio->quantity < $request->saleQuantity){
            \Session::flash('sellPortfolioError', "You don't own enough ".$request->saleStockCode.' shares to record this sale!');
            return redirect('user/portfolio/'.$id);
        }
        //Delete if sell quantity equals owned quantity
        elseif($stockInPortfolio->quantity == $request->saleQuantity){
            \DB::table('portfolio_stocks')
                ->where(['portfolio_id' => $id, 'stock_code' => $request->saleStockCode])
                ->delete();
        }

        //Update portfolio
        \DB::table('portfolio_stocks')
            ->where(['portfolio_id' => $id, 'stock_code' => $request->saleStockCode])
            ->update([
                'quantity' => $stockInPortfolio->quantity-$request->saleQuantity,
                'updated_at' => date("Y-m-d H:i:s")
            ]);


        $this->recordTrade(\Auth::user()->id, 'sell', $request->saleStockCode, $request->salePrice, $request->saleQuantity, $request->saleBrokerage, $request->saleDate);

        \Session::flash('sellStockSuccess', $request->saleQuantity.' units of '.$request->saleStockCode.' were sold successfully!');
        return redirect('user/portfolio/'.$id);   
    }

    private function ammendPosition(Request $request, $id){
        $stockInPortfolio = \DB::table('portfolio_stocks')->where(['portfolio_id' => $id, 'stock_code' => $request->purchaseStockCode])->first();
        $thisPurchaseTotal = $request->purchaseQuantity * $request->purchasePrice + $request->purchaseBrokerage;
        $updatedPurchaseQty = $stockInPortfolio->quantity + $request->purchaseQuantity;
        $updatedPurchasePrice = ($stockInPortfolio->purchase_price * $stockInPortfolio->quantity + $thisPurchaseTotal)/$updatedPurchaseQty;

        \DB::table('portfolio_stocks')
            ->where(['portfolio_id' => $id, 'stock_code' => $request->purchaseStockCode])
            ->update([
                'purchase_price' => $updatedPurchasePrice,
                'quantity' => $updatedPurchaseQty,
                'updated_at' => date("Y-m-d H:i:s")
            ]);
    }

    private function recordTrade($userId, $tradeType, $stockCode, $price, $quantity, $brokerage, $date){
        //Insert trade data
        \DB::table('trades')->insert([
            'user_id' => $userId,
            'trade_type' => $tradeType,
            'stock_code' => $stockCode,
            'price' => $price,
            'quantity' => $quantity,
            'brokerage' => $brokerage,
            'date' => $date,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }
}
