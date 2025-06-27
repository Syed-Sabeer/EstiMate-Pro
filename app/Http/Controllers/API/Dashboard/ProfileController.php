<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = Auth::user();
            $profile = Profile::with('user')
            ->where('user_id', $user->id)->where('company_id', $user->company_id)->first();
            return response()->json([
                'message' => 'Profile retrieved successfully',
                'profile' => $profile
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve profiles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = array(
            'user_id' => 'required|exists:users,id',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'business_name' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'profile_picture' => 'nullable|mimes:jpg,jpeg,png',
            'date_of_birth' => 'nullable|date',
            'date_of_joining' => 'nullable|date',
            'gender' => 'nullable|string|max:255',
            'marital_status' => 'nullable|string|max:255',
            'instagram_profile' => 'nullable|url',
            'facebook_profile' => 'nullable|url',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $user = Auth::user();
            $profile = Profile::where('user_id', $request->user_id)->first();
            if($profile->user->id != $user->id){
                return response()->json([
                    'message' => 'You are not authorized to update this profile',
                ], 401);
            }
            if (!$profile){
                $profile = new Profile();
            }
            $profile->first_name = $request->first_name;
            $profile->last_name = $request->last_name;
            $profile->business_name = $request->business_name;
            $profile->phone_number = $request->phone_number;
            $profile->address = $request->address;
            $profile->city = $request->city;
            $profile->state = $request->state;
            $profile->zip_code = $request->zip_code;
            $profile->country = $request->country;
            $profile->bio = $request->bio;
            $profile->date_of_birth = $request->date_of_birth;
            $profile->date_of_joining = $request->date_of_joining;
            $profile->gender = $request->gender;
            $profile->marital_status = $request->marital_status;
            $profile->instagram_profile = $request->instagram_profile;
            $profile->facebook_profile = $request->facebook_profile;
            if ($request->hasFile('profile_picture')) {
                if (isset($profile->profile_picture) && File::exists(public_path($profile->profile_picture))) {
                    File::delete(public_path($profile->profile_picture));
                }
                $profile_picture = $request->file('profile_picture');
                $profile_picture_ext = $profile_picture->getClientOriginalExtension();
                $profile_picture_name = $profile->user_id . '_profile_picture.' . $profile_picture_ext;
                $profile_picture_path = 'public/profile_pictures';
                $profile_picture->move(public_path('profile_pictures'), $profile_picture_name);
                $fullUrl = url($profile_picture_path . '/' . $profile_picture_name);

                $filePath = public_path('profile_pictures/' . $profile_picture_name);
                $fileSize = filesize($filePath);

                $profile->profile_picture = $fullUrl;
                $profile->profile_picture_size = $fileSize;
            }
            $profile->save();
            return response()->json([
                'message' => 'Profile data stored successfully',
                'profile' => $profile
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $currentUser = Auth::user();
            $user = User::findOrFail($id);
            if ($currentUser->id != $user->id){
                return response()->json([
                    'message' => 'You are not authorized to view this profile',
                ], 403);
            }
            $profile = Profile::with('user')->where('user_id',$id)->first();
            return response()->json([
                'message' => 'Profile retrieved successfully',
                'profile' => $profile
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
