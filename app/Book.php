<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function borrows()
    {
        return $this->hasMany('App\Transactions');
    }
}
