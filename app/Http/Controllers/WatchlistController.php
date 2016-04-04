<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Watchlist;
use App\Http\Controllers\Controller;

class WatchlistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userWatchlists = Watchlist::belongingToCurrentUser()->get();
        if($userWatchlists->count() > 0){
            return redirect('user/watchlist/'.$userWatchlists->first()->id);
        }
        return redirect('user/watchlist/0');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'watchlistName' => 'required|string|max:64'
        ]);

        if(Watchlist::belongingToCurrentUser()->withName($request->watchlistName)->first()){
            \Session::flash('watchlistNameError', 'You already have a watchlist with the same name!');
            return redirect()->back();
        }

        $watchlist = new Watchlist;
        $watchlist->user_id = \Auth::user()->id;
        $watchlist->watchlist_name = $request->watchlistName;
        $watchlist->save();

        \Session::flash('watchlistCreateSuccess', 'Your Watchlist was created successfully!');
        return redirect('user/watchlist/'.$watchlist->id);
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
        if($id == 0 || Watchlist::where('id', $id)->pluck('user_id') == \Auth::user()->id){
            return view('pages.user.watchlist')->with([            
                'watchlists' => Watchlist::select('id', 'watchlist_name')->where('user_id', \Auth::user()->id)->get(),
                'selectedWatchlist' => Watchlist::select('id', 'watchlist_name')->where('id', $id)->first(),
                'stocksInSelectedWatchlist' => Watchlist::getStockMetricsDataForPortfolio($id),
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
        //
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
