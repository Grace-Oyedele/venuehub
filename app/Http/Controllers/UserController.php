<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ApiResponser;

    public function index()
    {
        $users = User::all();
        return $this->success(['data' => $users]);
    }


    public function show($id)
    {
        $user = User::where('id', $id)->first();
        if (empty($user)) {
            return $this->error("User not found", 404);
        }
        return $this->success(['data' => $user]);


    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            "first_name" => 'required|string',
            "last_name" => 'required|string',
            "email" => 'required|string|unique:users,email',
            "phone_number" => 'required|integer|unique:users,phone_number',
        ]);

        $user = User::where('id', $id)->first();
        if (empty($user)) {
            return $this->error("User not found", 404);
        }

        $user->update($validatedData);
        $response = ['data' => $user, 'message' => 'details updated successfully.'];
        return $this->success($response);

    }

    public function destroy($id)
    {
        $user = User::where('id', $id)->first();
        if (empty($user)) {
            return $this->error('User not found');
        }
        $user->delete();
        return $this->success(["data" => "User deleted"
        ]);
    }


}
