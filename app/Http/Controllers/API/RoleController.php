<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    protected $roleValidation = [
        'role_name' => 'required|string|unique:roles,role_name'
    ];
    private $customMessages = [
        'required' => 'Cannot be empty',
        'string' => 'Please use alphabet letters',
        'unique' => 'Role already exists',
    ];
    //
    public function get_roles()
    {
        //gets all roles
        $roles = roles::all();

        //if none return an error code message of 400
        if ($roles->count() > 0) {
            return response()->json([
                'status' => 200,
                'success' => true,
                'data' => $roles
            ], 200);
        }
        return  response()->json([
            'status' => 400,
            'success' => false,
            'message' => 'No records found'
        ], 400);
    }
    public function add_role(Request $roleData)
    {
        $validatedRole = Validator::make($roleData->all(), $this->roleValidation, $this->customMessages);

        if ($validatedRole->fails()) {
            return  response()->json([
                'status' => 400,
                'success' => false,
                'data' => [
                    'errors' => $validatedRole->errors()->toArray()
                ]
            ], 400);
        }

        $newRole = roles::create([
            'role_name' => $roleData->role_name
        ]);

        if (!$newRole) {
            return  response()->json([
                'status' => 400,
                'success' => false,
                'data' => [
                    'errors' => 'New role could not be created'
                ]
            ], 400);
        }

        return  response()->json([
            'status' => 200,
            'success' => true,
            'data' => [
                'message' => "New role inserted successfully"
            ]
        ], 200);
    }
}
