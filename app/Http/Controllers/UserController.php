<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // get input data
        $input = \Request::all();

        // make response data
        $response = [
            'status' => '200',
            'message' => 'OK'
        ];

        // find all user
        $users = User::all();
        if($users->isEmpty()){
            // if no user found
            abort(404);
        }

        $response['result'] = [];
        foreach($users as $user) {
            $response['result'][] = $user->toArray(['name', 'email']);
        }
        return \Response::json($response, 200, [], JSON_FORCE_OBJECT);
    }

    public function show()
    {
        // get input data
        $input = \Request::all();
        $userId = $input['id'];

        // make response data
        $response = [
            'status' => '200',
            'message' => 'OK'
        ];

        // find user
        $user = User::findOrFail($userId);
        $response['result'] = $user->toArray(['name', 'email']);
        return \Response::json($response);
    }

    public function create(Request $request)
    {
        // get input data
        $input = \Request::all();

        // validate
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'required|alpha_num|between:8,16|confirmed',
            'password_confirmation' => 'required_with:password',
        ]);

        // make response data
        $response = [
            'status' => '200',
            'message' => 'OK'
        ];

        // create user
        $user = new User();
        $user->name = $input['name'];
        $user->email = $input['email'];
        $user->password = $input['password'];
        $user->save();
        return \Response::json($response);
    }

    public function store(Request $request)
    {
        // get input data
        $input = \Request::all();
        $userId = $input['id'];

        // validate
        $this->validate($request, [
            'name' => 'required',
            'email' => 'unique:users|email',
            'password' => 'alpha_num|between:8,16|confirmed',
            'password_confirmation' => 'required_with:password',
        ]);

        // make response data
        $response = [
            'status' => '200',
            'message' => 'OK'
        ];

        // update user
        $user = User::findOrFail($userId);
        $user->name = $input['name'];
        $user->email = $input['email'];
        $user->password = $input['password'];
        $user->save();
        return \Response::json($response);
    }

    public function destroy()
    {
        // get input data
        $input = \Request::all();
        $userId = $input['id'];

        // make response data
        $response = [
            'status' => '200',
            'message' => 'OK'
        ];

        // delete user
        // update user
        $user = User::findOrFail($userId);
        $user->deleted_at = true;
        $user->save();
        return \Response::json($response);
    }
}
