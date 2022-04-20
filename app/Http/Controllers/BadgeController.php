<?php

namespace App\Http\Controllers;
use App\Models\BadgeUploaded;
use App\Utility\Helper;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Badge;
use App\Models\Image;



class BadgeController extends Controller
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

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'description' => 'required|string',
            'exam_id' => 'required|string'
        ]);

        try {

            $uploadedFileUrl = cloudinary()->uploadFile($request->file('file')->getRealPath())->getSecurePath();

            $image = $this->fileUpload($request);
            $uploadImage = new Image;
            $uploadImage->type = 'badge';
            $uploadImage->name = $image['image_name'];
            $uploadImage->url = $uploadedFileUrl;
            $uploadImage->save();



            $badge = new Badge;
            $badge->name = $request->input('name');
            $badge->description = $request->input('description');
            $badge->image_id = $uploadImage->id;
            $badge->exam_id = $request->input('exam_id');
            $badge->save();

            //return successful response
            return response()->json(['Badge' => $badge, 'message' => 'CREATED'], 201);

        } catch (\Exception $e) {
            //return error message
            return $e;
            //return response()->json(['message' => 'User Registration Failed!'], 409);
        }
    }

    public function edit(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'description' => 'required|string',
            'exam_id' => 'required|string',
        ]);

        try {
            //$image = $this->fileUpload($request);
            $badge = $this->checkBadgeExist($id);
            if (!$badge) {
                return response()->json(['Badge' => $badge, 'message' => 'Badge does not exist'], 200);
            }

            $uploadedFileUrl = cloudinary()->uploadFile($request->file('file')->getRealPath())->getSecurePath();

            $image = $this->fileUpload($request);
            $uploadImage = new Image;
            $uploadImage->type = 'badge';
            $uploadImage->name = $image['image_name'];
            $uploadImage->url = $uploadedFileUrl;
            $uploadImage->save();

//            $badge->name = $request->input('name');
//            $badge->description = $request->input('description');
//            $badge->image_id = $uploadImage->id;
//            $badge->exam_id = $request->input('exam_id');
//            $badge->save();
            return response()->json(['Badge' => $badge, 'message' => 'UPDATED'], 200);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function delete($id)
    {
        $badge = $this->checkBadgeExist($id);
        if (!$badge) {
            return response()->json(['Badge' => $badge, 'message' => 'Id does not exist'], 200);
        }
        $badge->delete();
        return response()->json(['message' => 'DELETE SUCCESSFUL'], 200);
    }

    public function getAllRecords()
    {
        //return Badge::all();
        return Badge::paginate(20);
    }

    public function checkBadgeExist($id)
    {
        return Badge::find($id);
    }

    public function checkImageExist($id)
    {
        return Image::find($id);
    }

    private function fileUpload($request)
    {
        try {
            $folderName = "badge";
            return Helper::fileUpload($request,$folderName);
        }
        catch (Exception $e){
            echo $e;
        }
    }
}
