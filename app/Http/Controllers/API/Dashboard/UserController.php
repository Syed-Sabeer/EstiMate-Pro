<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getCurrentUser()
    {
        try {
            $user = Auth::user();
            $currentUser = User::with(
                'profile',
                'role'
            )->where('id', '=', $user->id)->first();
            return response()->json([
                'message' => 'Current User Retrieved Successfully.',
                'currentUser' => $currentUser
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getUsers()
    {
        try {
            $allUsers = User::with('profile','role')->get();
            return response()->json([
                'message' => 'All Users Retrieved Successfully.',
                'currentUser' => $allUsers
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getBuilders()
    {
        try {
            $allBuilders = User::with('profile','role','userPlan')->where('role_id', 2)->get();
            return response()->json([
                'message' => 'All Builders Retrieved Successfully.',
                'currentUser' => $allBuilders
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function toggleStatus($id)
    {
        try {
            $user = User::findOrFail($id);
            if($user->is_active = 'active'){
                $user->is_active = 'inactive';
                $message  = 'User Deactivated Successfully.';
            }else{
                $user->is_active = 'active';
                $message  = 'User Activated Successfully.';
            }
            $user->save();
            return response()->json([
                'message' => $message,
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
