<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $table = 'portfolios';

    protected $fillable = ['user_id', 'portfolio_name'];

    public function stocks(){
    	return $this->belongsToMany('App\Models\Stock');
    }
}
