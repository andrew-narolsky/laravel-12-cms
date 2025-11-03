<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Filters\UserFilter;
use App\Http\Requests\UserIndexRequest;
use App\Http\Requests\UserSaveRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(UserIndexRequest $request): View
    {
        $users = User::filter(new UserFilter($request))->paginate(User::PAGINATION_LIMIT);
        return view('admin.pages.users.index', compact('users'));
    }

    public function create(): View
    {
        $roles = Role::all();
        return view('admin.pages.users.create', compact('roles'));
    }

    public function store(UserSaveRequest $request): JsonResponse
    {
        $user = new User($request->only(['name', 'email', 'role_id']));
        $user->password = bcrypt($request->get('password'));
        $user->save();

        return response()->json([
            'message' => 'Profile successfully created!',
            'redirect' => route('users.index')
        ]);
    }

    public function edit(User $user): View
    {
        $roles = Role::all();
        return view('admin.pages.users.update', compact('user', 'roles'));
    }

    public function update(UserSaveRequest $request, User $user): JsonResponse
    {
        $user->fill($request->only(['name', 'email', 'role_id']));

        if ($password = $request->get('password')) {
            $user->password = bcrypt($password);
        }

        $user->save();

        return response()->json(['message' => 'Profile successfully updated!']);
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        if ($attachment = $user->avatar) {
            Storage::disk('public')->delete($attachment->path . '/' . rawurlencode($attachment->filename));
            $attachment->delete();
        }

        return redirect()->route('users.index');
    }
}
