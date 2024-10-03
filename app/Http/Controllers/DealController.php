<?php

namespace App\Http\Controllers;

use App\Models\CategoryDeal;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DealController extends Controller
{
    public function create(Request $request)
    {
        return Inertia::render('Deal/Create', [
            'categories' => CategoryDeal::all(),
        ]);
    }
}
