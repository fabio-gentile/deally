<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminDashbordController extends Controller
{
    //
    public function index()
    {
//        dd(Auth::user()->hasRole('admin'));
        return Inertia::render('Admin/Dashboard');
    }
}
