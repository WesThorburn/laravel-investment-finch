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
            return redirect('user/portfolio/'.$userPortfolios->first()->pluck('id'));
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
        return view('pages.user.portfolio')->with([            
            'portfolios' => Portfolio::select('id', 'portfolio_name')->where('user_id', \Auth::user()->id)->get(),
            'selectedPortfolio' => Portfolio::select('id', 'portfolio_name')->where('id', $id)->first(),
            'stocksInSelectedPortfolio' => \DB::table('portfolio_stocks')
                ->select('portfolio_id', 'stock_code', 'purchase_price', 'purchase_qty', 'brokerage', 'purchase_date')
                ->where('portfolio_id', $id)
                ->get()
        ]);
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
        $this->validate($request, [
            'stockCode' => 'required|string|max:3',
            'purchasePrice' => 'required|regex:/^\d*(\.\d{1,3})?$/',
            'quantity' => 'required|integer',
            'brokerage' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'date' => 'required|date'
        ]);

        //Check portfolio belongs to current user
        if(Portfolio::where('id', $id)->pluck('user_id') == \Auth::user()->id){
            \DB::table('portfolio_stocks')->insert([
                'portfolio_id' => $id,
                'stock_code' => $request->stockCode,
                'purchase_price' => $request->purchasePrice,
                'purchase_qty' => $request->quantity,
                'brokerage' => $request->brokerage,
                'purchase_date' => $request->date,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]);

            \Session::flash('addStockToPortfolioSuccess', $request->stockCode.' was added to your Portfolio successfully!');
            return redirect('user/portfolio/'.$id);
        }

        \Session::flash('addStockToPortfolioError', 'There was an error adding this stock to your portfolio!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
