<?php

namespace App\Http\Controllers;
use App\Models\BadgeUploaded;
use App\Models\Payment;
use App\Models\PaymentMade;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\User;
use App\Utility\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\Error;


class RolesPermissionsController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function addRole(Request $request)
    {
        //validate incoming request
        $this->validate($request, [
            'name' => 'required|string',
            'description' => 'required|string'
        ]);

        try {

            $role = new Role;
            $role->name = $request->input('name');
            $role->description = $request->input('description');
            $role->save();

            //return successful response
            return response()->json(['Role' => $role, 'message' => 'Role Added Successful'], 201);

        } catch (\Exception $e) {
            //return error message
            return $e;
            //return response()->json(['message' => 'User Registration Failed!'], 409);
        }
    }

    public function editRole(Request $request, $id)
    {
        try {
            $role = $this->checkRoletExist($id);
            if (!$role) {
                return response()->json(['Role' => $role, 'message' => 'Id does not exist'], 200);
            }
            $role->name = $request->input('name');
            $role->description = $request->input('description');
            $role->save();
            return response()->json(['Role' => $role, 'message' => 'UPDATED'], 200);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public static function getAllRoles()
    {
        return Role::paginate(20);
    }

    public function addPermission(Request $request)
    {
        //validate incoming request
        $this->validate($request, [
            'name' => 'required|string',
            'description' => 'required|string'
        ]);

        try {


            $role = $this->checkRoletExist($request->input('role_id'));
            if (!$role) {
                return response()->json(['Role' => $role, 'message' => 'Id does not exist'], 200);
            }

            $permission = new Permission();
            $permission->name = $request->input('name');
            $permission->description = $request->input('description');
            $permission->role_id = $request->input('role_id');
            $permission->save();

            //return successful response
            return response()->json(['Permission' => $permission, 'message' => 'Permission Added Successful'], 201);

        } catch (\Exception $e) {
            //return error message
            return $e;
            //return response()->json(['message' => 'User Registration Failed!'], 409);
        }
    }

    public function editPermission(Request $request, $id)
    {
        try {
            $permission = $this->checkPermissionExist($request->input('role_id'));
            if (!$permission) {
                return response()->json(['Permission' => $permission, 'message' => 'Id does not exist'], 200);
            }
            $permission->name = $request->input('name');
            $permission->description = $request->input('description');
            $permission->role_id = $request->input('role_id');
            $permission->save();
            return response()->json(['Permission' => $permission, 'message' => 'UPDATED'], 200);
        } catch (\Exception $e) {
            return $e;
        }
    }


    public static function getAllPermissions()
    {
        return Permission::paginate(20);
    }

    public function assignUserPermissionToRole(Request $request)
    {
        try {
            $role = $this->checkRoletExist($request->input('role_id'));
            if (!$role) {
                return response()->json(['Role' => $role, 'message' => 'Role Id does not exist'], 200);
            }

            $user = $this->checkUserExist($request->input('userid'));
            if (!$user) {
                return response()->json(['User' => $user, 'message' => 'User Id does not exist'], 200);
            }

            $roleAlreadyAssigned = $this->checkRoleAlreadyAssigned($request->input('userid'));

            if($roleAlreadyAssigned){
                return response()->json(['Permission' => null, 'message' => 'Role already assigned to user'], 201);
            }

//            $rolePermission = new RolePermission();
            $user->role_id = $request->input('role_id');
            //$rolePermission->userid = $request->input('userid');
            $user->save();
            return response()->json(['User' => $user, 'message' => 'CREATED'], 201);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function checkRoletExist($id)
    {
        return Role::find($id);
    }

    public function checkPermissionExist($id)
    {
        return Permission::find($id);
    }

    public function checkUserExist($id)
    {
        return User::find($id);
    }

    public function checkRoleAlreadyAssigned($id)
    {
        $user =  User::find($id);

        if($user->role_id == null){
            return false;
        }
        else{
            return true;
        }
    }

    public function getUserPermissions($id)
    {
        $permissionNames = Helper::getUserPermissions(Auth::user()->id);

        //dd($permissions); exit;
        return response()->json(['Permissions' => $permissionNames, 'message' => 'CREATED'], 200);
    }
}
