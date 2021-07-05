<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * List of users
     */
    public function index()
    {
        $users = User::whereEnabled(1)->get();

        return response()->json([
            'users' => $users
        ]);
    }

    /**
     * Create a new user
     */
    public function store(Request $request)
    {
        // Validate request data
        $validators = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'identity_number' => 'required|unique:users,identity_number|digits_between:9,12',
            'birthday' => 'date|nullable',
            'gender' => 'nullable',
            'address' => 'required',
            'city' => 'required',
            'phone' => 'required',
            'password' => 'required|min:8'
        ];

        $validator = Validator::make($request->all(), $validators);

        // Return validation results
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->messages()
            ]);
        } else {
            // Hash the user password
            $request->merge([
                'password' => bcrypt($request->get('password'))
            ]);

            // Create new user
            $user = User::create($request->all());
        }

        return response()->json([
            'message' => 'User ' . $user->name . ' has been created successfully'
        ]);
    }

    /**
     * Display the specified user info
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        if(!$user->enabled) {
            return response()->json([
                'message' => 'User is not available'
            ]);
        }

        return response()->json([
            'user' => $user
        ]);
    }

    /**
     * Remove the specified user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $name = $user->name;

        $user->delete();

        return response()->json([
            'message' => 'User ' . $name . ' has been deleted successfully'
        ]);
    }
}
