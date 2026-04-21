<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CountryImage extends Model
{

    protected $fillable=['country_id','image','sequence'];
    public function country(){
        return $this->belongsTo(Country::class);
    }
}
