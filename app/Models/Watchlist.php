<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Watchlist extends Model
{
    protected $table = 'watchlists';

    protected $fillable = ['user_id', 'watchlist_name'];

    public function stocks(){
    	return $this->belongsToMany('App\Models\Stock');
    }

    public function user(){
    	return $this->belongsTo('App\Models\User');
    }

    public function scopeBelongingToCurrentUser($query){
    	return $query->where('user_id', \Auth::user()->id);
    }
}
