<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\BuilderPricing;
use App\Models\ClientSurvey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ClientSurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = Auth::user();
            $buildersClientSurveys = ClientSurvey::where('user_id', '=', $user->id)->latest()->get();
            return response()->json([
                'message' => 'Builders Client Surveys Retrieved Successfully.',
                'buildersClientSurveys' => $buildersClientSurveys
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
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
    public function store(Request $request, $id)
    {
        $rules = array(
            'client_name' => 'required|string',
            'client_phone' => 'required|string',
            'total_area' => 'nullable|numeric',
            'floor_length' => 'nullable|numeric',
            'floor_width' => 'nullable|numeric',
            'wall_height' => 'nullable|numeric',
            'bathroom_type' => 'nullable|string',
            'tiling_level' => 'required|in:Budget,Standard,Premium',
            'design_style' => 'nullable|string',
            'home_age_category' => 'nullable|string',
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5048',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $floorArea = $request->floor_length * $request->floor_width;
            $wallArea = 2 * ($request->floor_length * $request->wall_height) + 2 * ($request->floor_width * $request->wall_height);
            $totalArea = $request->total_area ?? ($floorArea + $wallArea);

            // Tiled area logic
            $budgetArea = $floorArea + ($wallArea * 0.30);
            $standardArea = $floorArea + ($wallArea * 0.50);
            $premiumArea = $floorArea + ($wallArea * 1.00);

            // Estimate calculation
            $pricingItems = BuilderPricing::where('user_id', $id)->get();
            $estimateTotal = 0;

            foreach ($pricingItems as $item) {
                if ($item->price_type === 'm2') {
                    $area = match ($request->tiling_level) {
                        'Budget' => $budgetArea,
                        'Standard' => $standardArea,
                        'Premium' => $premiumArea,
                    };
                    $estimateTotal += $area * $item->final_price;
                } else {
                    $estimateTotal += $item->final_price;
                }
            }

            $highEstimate = $estimateTotal * 1.35;

            $survey = new ClientSurvey();
            $survey->user_id = $id;
            $survey->client_name = $request->client_name;
            $survey->client_phone = $request->client_phone;
            $survey->total_area = $totalArea;
            $survey->floor_length = $request->floor_length;
            $survey->floor_width = $request->floor_width;
            $survey->wall_height = $request->wall_height;
            $survey->bathroom_type = $request->bathroom_type;
            $survey->tiling_level = $request->tiling_level;
            $survey->design_style = $request->design_style;
            $survey->home_age_category = $request->home_age_category;
            $survey->calculated_floor_area = $floorArea;
            $survey->calculated_wall_area = $wallArea;
            $survey->calculated_total_area = $totalArea;
            $survey->budget_area = $budgetArea;
            $survey->standard_area = $standardArea;
            $survey->premium_area = $premiumArea;
            $survey->base_estimate = $estimateTotal;
            $survey->high_estimate = $highEstimate;

            $surveyPhotos = [];

            if ($request->hasFile('photos') && count($request->photos) > 0) {
                foreach ($request->photos as $photo) {
                    $photo_ext = $photo->getClientOriginalExtension();
                    $photo_name = $survey->id . '_' . time() . '_' . uniqid() . '.' . $photo_ext;
                    $photo_path = 'public/client_surveys';
                    $photo->move(public_path('client_surveys'), $photo_name);
                    $fullUrl = url(str_replace('public/', '', $photo_path) . '/' . $photo_name);
                    $surveyPhotos[] = $fullUrl;
                }
            }

            $survey->photos = json_encode($surveyPhotos);
            $survey->save();


            return response()->json([
                'message' => 'Client Survey stored successfully',
                'survey' => $survey
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
            $clientSurvey = ClientSurvey::findOrFail($id);
            if ($clientSurvey->user_id != Auth::user()->id) {
                return response()->json([
                    'message' => 'You are not authorized to view this survey'
                ], 403);
            }
            return response()->json([
                'message' => 'Client Survey retrieved successfully',
                'clientSurvey' => $clientSurvey
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
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
    public function update(Request $request, $id)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $survey = ClientSurvey::findOrFail($id);
            if ($survey->user_id != Auth::user()->id) {
                return response()->json([
                    'message' => 'You are not authorized to delete this survey'
                ], 403);
            }
            $survey->delete();
            return response()->json([
                'message' => 'Client Survey deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $rules = array(
            'status' => 'required|in:New,Contacted,Site Visit Done,Quote Sent,Quote Accepted,Quote Unsuccessful,Client Not Interested,Client Uncontactable',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        try {
            $survey = ClientSurvey::findOrFail($id);
            if ($survey->user_id != Auth::user()->id) {
                return response()->json([
                    'message' => 'You are not authorized to update this survey'
                ], 403);
            }
            $survey->status = $request->status;
            $survey->save();
            return response()->json([
                'message' => 'Client Survey status updated successfully',
                'survey' => $survey
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getAllSurveys()
    {
        try {
            $allSurveys = ClientSurvey::with('user')->latest()->get();
            return response()->json([
                'message' => 'All Client Surveys Retrieved Successfully.',
                'currentUser' => $allSurveys
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
