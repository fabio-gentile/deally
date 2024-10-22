<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use App\Services\ImageService;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class AdminUserController extends Controller
{
    public function __construct(private ImageService $imageService) {}

    /**
     * Display a listing of users.
     */
    public function index(Request $request): \Inertia\Response
    {
        $users = User::query();

        if (!$request->has('created_at')) {
            $users->orderBy('created_at', 'desc');
        }

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

    /**
     * Show the form for editing the user
     */
    public function edit(Request $request, string $user): \Inertia\Response
    {
        $user = User::where('id', $user)->firstOrFail();
        return Inertia::render('Admin/User/Edit', [
            'user' => $user,
            'roles' => (Role::all()),
            'userRoles' => $user->getRoleNames(),
        ]);
    }

    /**
     * Update the user
     * @throws Exception
     */
    public function update(UpdateUserRequest $request, string $user): \Illuminate\Http\RedirectResponse
    {
        $user = User::where('id', $user)->firstOrFail();
        $roles = Role::whereIn('name', $request->roles)->get()->pluck('name')->toArray();

        if ($roles) {
            $user->syncRoles($roles);
        }

        // Delete avatar
        if ($request->isAvatarRemoved === true) {
            try {
                $this->imageService->deleteImage($user->avatar, 'uploads/avatar/');
                $user->update(['avatar' => null]);
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }

        $user->update([
            'name' => $request->name,
            'biography' => $request->biography,
        ]);

        return redirect()->route('admin.users.list')->with('success', $user->name . ' a été mis à jour avec succès');
    }

    /**
     * Remove the user
     * @throws Exception
     */
    public function destroy(string $user): \Illuminate\Http\RedirectResponse
    {
        $user = User::where('id', $user)->firstOrFail();

        if ($user->hasRole('admin')) {
            return back()->with('error', 'Vous ne pouvez pas supprimer un compte administrateur.');
        }

        $user->delete();

        return redirect()->route('admin.users.list')->with('success', $user->name . ' a été supprimé avec succès');
    }
}
