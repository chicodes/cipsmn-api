<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Settings;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use App\Utility\Helper;
use Exception;


class SettingsController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string'
        ]);

        $settings = new Settings;
        $settings->name = $request->input('name');
        $settings->status = '0';
        $settings->save();

        //return successful response
        return response()->json(['Settings' => $settings, 'message' => 'CREATED'], 201);
    }

    public function edit(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string'
        ]);

        $settings = $this->checkSettingsExist($id);

        if (!$settings) {
            return response()->json(['Settings' => $settings, 'message' => 'Settings does not exist'], 200);
        }

        try {

            $settings->name = $request->input('name');
            $settings->save();
            return response()->json(['Settings' => $settings, 'message' => 'UPDATED'], 200);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function enableDisable(Request $request, $id)
    {
        //validate incoming request
        $this->validate($request, [
            'status' => 'required|string'
        ]);

        $settings = $this->checkSettingsExist($id);

        try {
            $settings->status = $request->input('status');
            $settings->save();
            return response()->json(['Settings' => $settings, 'message' => 'UPDATED'], 200);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function checkSettingsExist($id)
    {
        return Settings::find($id);
    }
}
