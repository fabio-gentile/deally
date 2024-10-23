<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\Discussion;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AdminDashboardController extends Controller
{
    public function index(): \Inertia\Response
    {
        $months = [
            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
        ];

        $deals = DB::table('deals')
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(id) as count')
            ->where('created_at', '>', Carbon::now()->subYear())
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $dealsChart = $deals->map(function ($deal) use ($months) {
            return [
                'Deals' => $deal->count,
                'name' => $months[$deal->month]
            ];
        });

        return Inertia::render('Admin/Dashboard', [
            'activeDealsCount' => Deal::where('expiration_date', '>', now())->count(),
            'lastMonthDealsCount' => Deal::whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->count(),
            'usersCount' => User::count(),
            'lastMonthUsersCount' => User::whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->count(),
            'discussionsCount' => Discussion::count(),
            'lastMonthDiscussionsCount' => Discussion::whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->count(),
            'dealsChart' =>$dealsChart
        ]);
    }
}
