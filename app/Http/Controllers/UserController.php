<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request): UserCollection
    {
        $users = User::all();

        return new UserCollection($users);
    }

    public function store(UserStoreRequest $request): UserResource
    {
        $data = $request->except(['password']);
        $password = Hash::make($request->password);
        $data += [
            'password' => $password,
        ];
        $user = User::create($data);

        return new UserResource($user);
    }

    public function show(Request $request, User $user): UserResource
    {
        return new UserResource($user);
    }

    public function update(UserUpdateRequest $request, User $user): UserResource
    {
        $user->update($request->validated());

        return new UserResource($user);
    }

    public function destroy(Request $request, User $user): Response
    {
        $user->delete();

        return response()->noContent();
    }


}
