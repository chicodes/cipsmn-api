<?php

namespace App\Http\Controllers;
use App\Models\BadgeUploaded;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Badge;



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
        //validate incoming request
        $this->validate($request, [
            'name' => 'required|string',
            'description' => 'required|string',
            'image_id' => 'required|string',
        ]);

        try {

            $user = new Badge;
            $user->name = $request->input('name');
            $user->description = $request->input('description');
            $user->image_id = $request->input('image_id');
            $user->save();

            //return successful response
            return response()->json(['Badge' => $user, 'message' => 'CREATED'], 201);

        } catch (\Exception $e) {
            //return error message
            return $e;
            //return response()->json(['message' => 'User Registration Failed!'], 409);
        }
    }

    public function edit(Request $request, $id)
    {
        try {
            $badge = $this->checkBadgeExist($id);
            if (!$badge) {
                return response()->json(['Badge' => $badge, 'message' => 'Id does not exist'], 200);
            }
            $badge->name = $request->input('name');
            $badge->description = $request->input('description');
            $badge->image_id = $request->input('image_id');
            $badge->save();
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
        return Badge::all();
    }

    public function checkBadgeExist($id)
    {
        return Badge::find($id);
    }

    public function getUserBadge()
    {
        $userid = Auth::user()->id;
        BadgeUploaded::where('userid', $userid)->get();
    }


    public function getAllUserPayment()
    {
        $userid = Auth::user()->id;
        return BadgeUploaded::where('userid', $userid)->get();
    }
}
