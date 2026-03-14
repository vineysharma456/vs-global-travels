<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class HomePageController extends Controller
{
    public function homePage()
    {

       $airports = DB::table('airports')
                ->select(
                'id',
                'name',
                'city',
                'country',
                'iata_code',
                DB::raw("CONCAT(iata_code,' - ',city,', ',country,' (',name,')') as label")
            )
            ->whereNotNull('iata_code')
            ->whereNotNull('city')
            ->orderBy('city')
            ->get();


        return view('homepage', compact('airports'));
    }
}