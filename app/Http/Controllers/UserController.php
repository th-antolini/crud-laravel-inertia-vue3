<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $usersQuery = User::query();

        $this->applySearch($usersQuery, $request->search);

        $users = UserResource::collection(
            $usersQuery->paginate(10)
        );

        return inertia('Users/Index', [
            'users' => $users,
            'search' => $request->search ?? ''
        ]);
    }

    protected function applySearch($query, $search)
    {
        return $query->when($search, function ($query, $search) {
            $query->where('name', 'like', '%'. $search .'%')
                ->orWhere('email', 'like', '%'. $search .'%');
        });
    }

    public function create()
    {
        return inertia('Users/Create');
    }

    public function store(StoreUserRequest $request)
    {
        User::create(array_merge($request->validated(), [
            'password' => bcrypt('password')
        ]));

        return redirect()->route('users.index');
    }

    public function edit(User $user)
    {
        return inertia('Users/Edit', [
            'user' => UserResource::make($user)
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());

        return redirect()->route('users.index');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index');
    }
}
