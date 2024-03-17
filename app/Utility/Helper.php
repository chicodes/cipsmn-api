<?php

namespace App\Utility;

use App\Models\Permission;
use App\Models\RolePermission;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class Helper{

    public static function fileUpload($request,$folderName)
    {
        try {
            $picName = $request->file('file')->getClientOriginalName();
            $picName = uniqid() . '_' . $picName;
            $fullPath = dir(getcwd());
            $destinationPath = $fullPath->path.'\uploads\\'.$folderName;
            File::makeDirectory($destinationPath, 0777, true, true);
            $request->file('file')->move($destinationPath, $picName);
            return [
                        'image_name' => $picName,
                        'image_path' => $destinationPath
                    ];
        }
        catch(Exception $e){
            echo $e;
        }
    }

    public static function doAsset($path){
        if (!function_exists('urlGenerator')) {
            /**
             * @return \Laravel\Lumen\Routing\UrlGenerator
             */
            function urlGenerator() {
                return new \Laravel\Lumen\Routing\UrlGenerator(app());
            }
        }

        if (!function_exists('asset')) {
            /**
             * @param $path
             * @param bool $secured
             *
             * @return string
             */
            function asset($path, $secured = false) {
                return urlGenerator()->asset($path, $secured);
            }
        }
    }

    public static function getUserPermissions($id)
    {
        Log::info("userid received is". $id);
        $findUserPermissions = RolePermission::where('userid', $id)
            ->join('permission', 'role_permission.permission_id', '=', 'permission.id')
            ->pluck('permission.name');

        Log::info("permissions returned is" . $findUserPermissions);

        if($findUserPermissions == null){
            return response()->json(['Permissions' => null, 'message' => 'no permissions for this user'], 200);
        }


//        $permissions = [];
//        foreach ($findUserPermissions as $findUserPermission) {
//            $permissions[] = $findUserPermission->name;
//        }
//
//        dd($permissions); exit;

        return $findUserPermissions;
    }

//    private static function checkUserExist($id)
//    {
//        return User::find($id);
//    }

    private static function checkUserExist($id)
    {
        return User::find($id);
    }
}
