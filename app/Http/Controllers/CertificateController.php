<?php

namespace App\Http\Controllers;
use App\Models\Image;
use App\Models\User;
use App\Utility\Helper;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Certificate;



class CertificateController extends Controller
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
        //validate incoming request
        $this->validate($request, [
            'file' => 'required',
        ]);

        $file[] = $request->file('file');

        //dd(count($file));

        try {
                for($i = 0; $i < count($file); $i++) {
                    $uploadedFileUrl = cloudinary()->uploadFile($file[$i]->getRealPath())->getSecurePath();

                    $image = $this->fileUpload($request);
                    $uploadImage = new Image;
                    $uploadImage->type = 'certificate';
                    $uploadImage->name = $image['image_name'];
                    $uploadImage->url = $uploadedFileUrl;
                    $uploadImage->save();

                    $certificate = new Certificate;
                    $certificate->userid = $id = Auth::user()->id;;
                    $certificate->image_id = $uploadImage->id;
                    $certificate->save();
                }
            //return successful response
            return response()->json(['Certificate' => $certificate, 'message' => 'CREATED'], 201);

        } catch (\Exception $e) {
            //return error message
            return $e;
            //return response()->json(['message' => 'User Registration Failed!'], 409);
        }
    }


    public function createMultipleCertificates(Request $request)
    {
        //validate incoming request
        $this->validate($request, [
            'number_uploads' => 'required',
        ]);

        try {
            for ($i=0; $i < $request->input('number_uploads'); $i++){

                $file = $request->file('file'.$i);

                $uploadedFileUrl = cloudinary()->uploadFile($file->getRealPath())->getSecurePath();

                $image = $this->fileUpload($file);
                $uploadImage = new Image;
                $uploadImage->type = 'certificate';
                $uploadImage->name = $image['image_name'];
                $uploadImage->url = $uploadedFileUrl;
                $uploadImage->save();

                $certificate = new Certificate;
                $certificate->userid = $id = Auth::user()->id;;
                $certificate->image_id = $uploadImage->id;
                $certificate->save();
            }
            //return successful response
            return response()->json(['Certificate' => $certificate, 'message' => 'CREATED'], 201);

        } catch (\Exception $e) {
            //return error message
            return $e;
            //return response()->json(['message' => 'User Registration Failed!'], 409);
        }
    }

    public function delete($id){
        $certificate = $this->checkCertificateExist($id);
        if(!$certificate){
            return response()->json(['Certificate' => $certificate, 'message' => 'Id does not exist'], 200);
        }
        $certificate->delete();
        return response()->json(['message' => 'DELETE SUCCESSFUL'], 200);
    }

    public function getAllRecords()
    {
        return Certificate::all();
    }

    public function getuserCertificates()
    {
        $id = Auth::user()->id;
        $getCertificates =  Certificate::where('userid', $id)->paginate(20);
        $userCertificate = [];
        foreach($getCertificates as $getCertificate){

            $getCertificateImage = Image::find($getCertificate->image_id);
            $userCertificate [] = $getCertificateImage;
        }

        return $userCertificate;

    }

    public function getuserSingleCertificate($id)
    {
         $getCertificate = Certificate::find($id);
         return Image::find($getCertificate->image_id);
    }

    public function checkCertificateExist($id){
        return Certificate::find($id);
    }

    private function fileUpload($request)
    {
        try {
            $folderName = "certificate";
            return Helper::fileUpload($request,$folderName);
        }
        catch (Exception $e){
            echo $e;
        }
    }
}
