<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Models\users;
use App\Models\volunteers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;


class UserController extends Controller
{
    private $validationRules = [
        'username' => 'required|string',
        'email' => 'required|unique:users,email',
        'password' => 'required|min:8'
    ];
    private $volunteer_rules = [
        'username' => 'required|string',
        'email' => 'required|unique:volunteers,email',
        'phone_number' => 'required|unique:volunteers,phone_number'
    ];
    private $customMessages = [
        'required' => 'Cannot be empty',
        'string' => 'Please use alphabet letters',
        'min' => 'Must have a minimum 8 characters',
    ];

    private function generateToken()
    {
        $token = Str::random(32);


        $validToken = Validator::make(
            ['token' => $token],
            [
                'id' => Rule::unique('users'),
                Rule::unique('volunteers')
            ]
        );

        if (!$validToken->passes()) {
            return $this->generateToken();
        }

        return $token;
    }
    private function generatePassword()
    {
        $token = Str::random(8);
        $validToken = Validator::make(['token' => $token], ['token' => 'unique:volunteers,id']);

        if (!$validToken->passes()) {
            return $this->generateToken();
        }

        return $token;
    }

    //completed
    public function get_all_users()
    {
        //gets all users
        $users = users::all();

        //if none return an error code message of 400
        if ($users->count() > 0) {
            return response()->json([
                'status' => 200,
                'success' => true,
                'data' => $users
            ], 200);
        }
        return  response()->json([
            'status' => 400,
            'success' => false,
            'message' => 'No records found'
        ], 400);
    }
    public function getSpecificUser($id)
    {
        $selectedUser = users::find($id);
        if ($selectedUser) {
            return response()->json([
                'status' => 200,
                'success' => true,
                'user' => $selectedUser
            ], 200);
        }
        return response()->json([
            'status' => 200,
            'res' => false,
            'message'=>'user not found'
        ], 200);
    }
    public function get_volunteers()
    {
        //gets all users
        $volunteers = volunteers::all();

        //if none return an error code message of 400
        if ($volunteers->count() > 0) {
            return response()->json([
                'status' => 200,
                'success' => true,
                'volunteers' => $volunteers
            ], 200);
        }
        return  response()->json([
            'status' => 400,
            'success' => false,
            'message' => 'No records found'
        ], 400);
    }
    public function register(Request $userData)
    {

        $validatedInput = Validator::make($userData->all(), $this->validationRules, $this->customMessages);

        if ($validatedInput->fails()) {
            $errors = $validatedInput->errors()->toArray();
            return response()->json([
                'status' => 400,
                'success' => false,
                'data' => [
                    'message' => $errors
                ]
            ], 400);
        }



        $newUser = users::create([
            'id' => $this->generateToken(),
            'username' => $userData->username,
            'email' => $userData->email,
            'password' => Hash::make($userData->password),
            'role' => $userData->role,
            'is_banned' => false
        ]);

        if ($newUser) {
            return response()->json([
                'status' => 200,
                'success' => true,
                'data' => [
                    'user' => [
                        'user_token' => $userData->id,
                        'username' => $userData->username,
                        'email' => $userData->email,
                        'user_privileges' => $userData->role
                    ]
                ]
            ], 200);
        } else {
            return response()->json([
                'status' => 400,
                'success' => false,
                'data' => [
                    'message' => 'User could not be created'
                ]
            ], 400);
        }
    }
    public function registerVolunteer(Request $userData)
    {

        $validatedInput = Validator::make($userData->all(), $this->volunteer_rules, $this->customMessages);

        if ($validatedInput->fails()) {
            $errors = $validatedInput->errors()->toArray();
            return response()->json([
                'status' => 400,
                'success' => false,
                'data' => [
                    'message' => $errors
                ]
            ], 400);
        }



        $newUser = volunteers::create([
            'id' => $this->generateToken(),
            'username' => $userData->username,
            'email' => $userData->email,
            'password' => $this->generatePassword(),
            'phone_number' => $userData->phone_number,
            'national_id' => $userData->national_id,
            'conduct_certificate' => $userData->conduct_certificate,
            'is_validated' => false
        ]);

        if ($newUser) {
            return response()->json([
                'status' => 200,
                'success' => true,
                'message' => 'Volunteer request is being processed...'
            ], 200);
        } else {
            return response()->json([
                'status' => 400,
                'success' => false,
                'data' => [
                    'message' => 'Volunteer request could not be created'
                ]
            ], 400);
        }
    }
    public function login(Request $userData)
    {

        $user = users::where('email', '=', $userData->email)->first();
        if ($user) {



            $passValidated = Hash::check($userData->password, $user->password);
            if ($passValidated) {
                return response()->json([
                    'status' => 200,
                    'success' => true,
                    'data' => [
                        'user' => [
                            'user_token' => $user->id,
                            'username' => $user->username,
                            'email' => $userData->email,
                            'user_privileges' => $user->role
                        ]
                    ]
                ], 200);
            } else {
                return response()->json([
                    'status' => 400,
                    'success' => false,
                    'data' => [
                        'message' => "Wrong password!"
                    ]
                ], 400);
            }
        } else {
            return response()->json([
                'status' => 400,
                'success' => false,
                'data' => [
                    'message' => "User not found! Please confirm your email."
                ]
            ], 400);
        }
    }

    public function update_user(Request $userData)
    {
        $selectedUser = users::find($userData->id);
        if (!$selectedUser) {
            return response()->json([
                'status' => 400,
                'success' => false,
                'message' => "User could not be found!"
            ], 400);
        }

        $selectedUser->fill([
            // 'username' => $userData->username ?? $selectedUser->username,
            // 'email' => $userData->email ?? $selectedUser->email,
            // 'role' => $userData->role_name ?? $selectedUser->role_name,
            'is_banned' => $userData->is_banned 
        ]);

        $newUser = $selectedUser->save();

        if (!$newUser) {
            return response()->json([
                'status' => 400,
                'success' => false,
                'message' => "User data could not be updated!"
            ], 400);
        }

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => "User role updated successfully!"
        ], 200);
    }

    public function deleteUser($id)
    {
        $selectedUser = users::where('id', '=', $id)->delete();
        if (!$selectedUser) {
            return response()->json([
                'status' => 400,
                'success' => false,
                'message' => "User not found!"
            ], 400);
        }
        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => "User deleted successfully!"
        ], 200);
    }

    public function update_volunteer(Request $userData)
    {
        $selectedUser = volunteers::find($userData->id);

        if (!$selectedUser) {
            return response()->json([
                'status' => 400,
                'success' => false,
                'message' => "User not found!"
            ], 400);
        }

        $selectedUser->fill([
            'is_validated' => $userData->is_validated,
        ]);

        $newUser = $selectedUser->save();
        if (!$newUser) {
            return response()->json([
                'status' => 400,
                'success' => false,
                'message' => "User data could not be updated"
            ], 400);
        }
        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => "User updated successfully!"
        ], 200);
    }
    public function ban_user(Request $userData)
    {
        $selectedUser = users::find($userData->id);

        if (!$selectedUser) {
            return response()->json([
                'status' => 400,
                'success' => false,
                'message' => "User not found!"
            ], 400);
        }

        $selectedUser->fill([
            'is_validated' => $userData->is_banned,
        ]);

        $newUser = $selectedUser->save();
        if (!$newUser) {
            return response()->json([
                'status' => 400,
                'success' => false,
                'message' => "User data could not be updated"
            ], 400);
        }
        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => "User updated successfully!"
        ], 200);
    }
    public function delete_volunteer($id)
    {
        $selectedUser = volunteers::where('id', '=', $id)->delete();
        if (!$selectedUser) {
            return response()->json([
                'status' => 400,
                'success' => false,
                'message' => "User not found!"
            ], 400);
        }
        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => "User deleted successfully!"
        ], 200);
    }
}
