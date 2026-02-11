<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        return $this->success(User::with('roles')->get(), 'Users retrieved successfully');
    }

    public function show(User $user)
    {
        return $this->success($user->load('roles'), 'User retrieved successfully');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation failed', 422, $validator->errors());
        }

        try {
            $user = User::create($request->all());
            $user->password = Hash::make($user->password);
            $user->save();

            // Reload with roles (will be empty but ensures consistent structure)
            $user->load('roles');

            return $this->success($user, 'User created successfully', 201);

        } catch (\Exception $e) {
            return $this->error('Something went wrong', 500, $e->getMessage());
        }
    }


    public function update(Request $request, User $user)
    {
        try {
            $data = $request->validate([
                'name' => 'sometimes|string',
                'email' => 'sometimes|email|unique:users,email,' . $user->id,
                'password' => 'sometimes|string|min:6',
            ]);

            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            $user->update($data);

            // Reload to ensure roles are included and data is fresh
            $user->load('roles');

            return $this->success($user, 'User updated successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error('Validation failed', 422, $e->errors());
        } catch (\Exception $e) {
            return $this->error('Something went wrong', 500, $e->getMessage());
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            return $this->success(null, 'User deleted successfully');
        } catch (\Exception $e) {
            return $this->error('Something went wrong', 500, $e->getMessage());
        }
    }
}
