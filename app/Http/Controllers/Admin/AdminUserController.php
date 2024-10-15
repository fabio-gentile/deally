<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminUserController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        $users = User::query();

        if ($request->has('search') && $request->search !== null) {
            $users->where('name', 'like', '%' . $request->search . '%')->orWhere('email', 'like', '%' . $request->search . '%');
        }

        if ($request->has('name') && $request->name !== null) {
            $users->orderBy('name', $request->name === 'desc' ? 'desc' : 'asc');
        }

        if ($request->has('email') && $request->email !== null) {
            $users->orderBy('email', $request->email === 'desc' ? 'desc' : 'asc');
        }

        $users = $users->paginate($request->per_page ?? 10);
//        dd($users->toArray());
        return Inertia::render('Admin/User/List', [
            'users' => $users->items(),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ],
            'filters' => $request->all(),
        ]);
    }
}
