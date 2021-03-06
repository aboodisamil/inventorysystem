<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['id', 'name', 'phone', 'address'];

    public function orders()
    {
        return $this->hasMany('App\Export');
    }




}
