<?php

namespace App\Http\Controllers;

use App\Models\Series;
use Illuminate\Http\Request;

class SeriesController extends Controller
{
    public function index()
    {
        return view('series.index', [
            // 'popular' => Series::withTotalVisitCount()->get()
            'popular' => Series::withTotalVisitCount()->popularLastDays(2)->get()
        ]);
    }

    public function show(Series $series)
    {
        auth()->loginUsingId(1);
        $series->visit()->withIp()->withUser()->withData([
            'cats' => true
        ]);
        return view('series.show', [
            'series' => $series
        ]);
    }
}