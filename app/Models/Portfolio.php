<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $table = 'portfolios';

    protected $fillable = ['user_id', 'portfolio_name'];

    public function stocks(){
    	return $this->belongsToMany('App\Models\Stock');
    }

    public function user(){
    	return $this->belongsTo('App\Models\User');
    }

    public function scopeBelongingToCurrentUser($query){
    	return $query->where('user_id', \Auth::user()->id);
    }

    public function scopeWithName($query, $name){
    	return $query->where('portfolio_name', $name);
    }
}
