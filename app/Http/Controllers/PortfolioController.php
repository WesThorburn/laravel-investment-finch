<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Portfolio;
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
        $userPortfolios = Portfolio::where('user_id', \Auth::user()->id)->get();
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

        if(Portfolio::where(['user_id' => \Auth::user()->id, 'portfolio_name' => $request->portfolioName])->first()){
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
                'stocksInSelectedPortfolio' => \DB::table('portfolio_stocks')
                    ->join('stock_metrics', 'portfolio_stocks.stock_code', '=', 'stock_metrics.stock_code')
                    ->select(
                        'portfolio_stocks.portfolio_id', 
                        'portfolio_stocks.stock_code', 
                        'portfolio_stocks.purchase_price', 
                        'portfolio_stocks.purchase_qty', 
                        'portfolio_stocks.brokerage', 
                        'portfolio_stocks.purchase_date',
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

        //Check if stock already exists in portfolio
        if(\DB::table('portfolio_stocks')->where(['portfolio_id' => $id, 'stock_code' => $request->purchaseStockCode])->first()){
            $this->ammendPosition($request, $id);
        }
        else{
            //Insert request data
            \DB::table('portfolio_stocks')->insert([
                'portfolio_id' => $id,
                'stock_code' => $request->purchaseStockCode,
                'purchase_price' => (($request->purchasePrice*$request->purchaseQuantity)+$request->purchaseBrokerage)/$request->purchaseQuantity,
                'purchase_qty' => $request->purchaseQuantity,
                'brokerage' => $request->purchaseBrokerage,
                'purchase_date' => $request->purchaseDate,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]);
        }
        \Session::flash('addStockToPortfolioSuccess', $request->purchaseStockCode.' was added to your Portfolio successfully!');
        return redirect('user/portfolio/'.$id);
    }

    private function sell(Request $request, $id){
        $this->validate($request, [
            'stockCode' => 'required|string|max:3',
            'salePrice' => 'required|regex:/^\d*(\.\d{1,3})?$/',
            'saleQuantity' => 'required|integer|min:1',
            'saleBrokerage' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'saleDate' => 'required|date'
        ]);

        //Check if stock already exists in portfolio
        if(\DB::table('portfolio_stocks')->where(['portfolio_id' => $id, 'stock_code' => $request->sellStockCode])->first()){
            
        }
        \Session::flash('addStockToPortfolioSuccess', $request->purchaseStockCode.' was added to your Portfolio successfully!');
        return redirect('user/portfolio/'.$id);
    }

    private function ammendPosition(Request $request, $id){
        $stockInPortfolio = \DB::table('portfolio_stocks')->where(['portfolio_id' => $id, 'stock_code' => $request->stockCode])->first();
        $thisPurchaseTotal = $request->quantity * $request->purchasePrice + $request->brokerage;
        $updatedPurchaseQty = $stockInPortfolio->purchase_qty + $request->quantity;
        $updatedPurchasePrice = ($stockInPortfolio->purchase_price * $stockInPortfolio->purchase_qty + $thisPurchaseTotal)/$updatedPurchaseQty;
        $updatedBrokerage = $stockInPortfolio->brokerage + $request->brokerage;

        \DB::table('portfolio_stocks')
            ->where(['portfolio_id' => $id, 'stock_code' => $request->stockCode])
            ->update([
                'purchase_price' => $updatedPurchasePrice,
                'purchase_qty' => $updatedPurchaseQty,
                'brokerage' => $updatedBrokerage
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
