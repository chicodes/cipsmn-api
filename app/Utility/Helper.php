<?php

namespace App\Utility;

use Illuminate\Support\Facades\File;

class Helper{

    public static function fileUpload($request,$folderName)
    {
        try {
            $picName = $request->file('image')->getClientOriginalName();
            $picName = uniqid() . '_' . $picName;
            $fullPath = dir(getcwd());
            $destinationPath = $fullPath->path.'\uploads\\'.$folderName;
            File::makeDirectory($destinationPath, 0777, true, true);
            $request->file('image')->move($destinationPath, $picName);
            return [
                        'image_name' => $picName,
                        'image_path' => $destinationPath
                    ];
        }
        catch(Exception $e){
            echo $e;
        }
    }
}
