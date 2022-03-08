<?php

namespace App\Http\Controllers;
use App\Models\BadgeUploaded;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Badge;



class BadgeUploadedController extends Controller
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

    public function create(Request $request, $id)
    {
        //validate incoming request
        $this->validate($request, [
            'badge_type' => 'required|string',
            'image_id' => 'required|string',
        ]);

        try {

            $user = new BadgeUploaded;
            $user->userid = $$id;
            $user->badge_type = $request->input('badge_type');
            $user->image_id = $request->input('image_id');
            $user->save();

            //return successful response
            return response()->json(['Badge' => $user, 'message' => 'User badge successfully uploaded'], 201);

        } catch (\Exception $e) {
            return $e;
            //return response()->json(['message' => 'User Registration Failed!'], 409);
        }
    }

    public function edit(Request $request, $id)
    {
        try {
            $badgeUploaded = $this->checkBadgeExist($id);
            if(!$badgeUploaded){
                return response()->json(['Badge' => $badgeUploaded, 'message' => 'Id does not exist'], 200);
            }
            $badgeUploaded->name = $request->input('name');
            $badgeUploaded->description = $request->input('description');
            $badgeUploaded->image_id = $request->input('image_id');
            $badgeUploaded->save();
            return response()->json(['Badge' => $badgeUploaded, 'message' => 'UPDATED'], 200);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function delete($id){
        $badgeUploaded = $this->checkBadgeExist($id);
        if(!$badgeUploaded){
            return response()->json(['Badge' => $badgeUploaded, 'message' => 'Id does not exist'], 200);
        }
        $badgeUploaded->delete();
        return response()->json(['message' => 'DELETE SUCCESSFUL'], 200);
    }

    public function getAll($id)
    {
        return BadgeUploaded::where('userid', $id)->get();
    }

    public function view($id)
    {
        return BadgeUploaded::find($id);
    }

    public function checkBadgeUploadedExist($id){
        return BadgeUploaded::find($id);
    }

    public function getUserBadge(){
        //Badge::where('userid','like','%John%') -> first();
        BadgeUploaded::where('userid', 1)
//            ->orderBy('name')
//            ->take(10)
            ->get();
    }
}
