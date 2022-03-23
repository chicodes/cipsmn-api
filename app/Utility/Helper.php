<?php

namespace App\Utility;

use Illuminate\Support\Facades\File;

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
}
