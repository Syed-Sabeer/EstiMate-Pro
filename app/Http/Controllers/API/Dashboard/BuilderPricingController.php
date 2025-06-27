<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\BuilderPricing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BuilderPricingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = Auth::user();
            $builderPricings = BuilderPricing::where('user_id', '=', $user->id)->get();
            return response()->json([
                'message' => 'Builder Prices Retrieved Successfully.',
                'builderPricings' => $builderPricings
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
    public function store(Request $request)
    {
        $rules = array(
            'item_name' => 'required|string|max:255',
            'applicability' => 'required|string|max:255',
            'price_type' => 'required|in:m2,fixed',
            'base_price' => 'nullable|numeric',
            'markup_percent' => 'nullable|numeric',
            'final_price' => 'nullable|numeric',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $user = Auth::user();
            $basePrice = $request->input('base_price');
            $markupPercent = $request->input('markup_percent');
            $finalPrice = $request->input('final_price');

            // If final_price is not provided, calculate it
            if (is_null($finalPrice) && !is_null($basePrice) && !is_null($markupPercent)) {
                $finalPrice = $basePrice * (1 + ($markupPercent / 100));
            }

            $builderPricing = new BuilderPricing();
            $builderPricing->user_id = $user->id;
            $builderPricing->item_name = $request->input('item_name');
            $builderPricing->applicability = $request->input('applicability');
            $builderPricing->price_type = $request->input('price_type');
            $builderPricing->base_price = $basePrice;
            $builderPricing->markup_percent = $markupPercent;
            $builderPricing->final_price = $finalPrice;
            $builderPricing->save();

            return response()->json([
                'message' => 'Builder Pricing stored successfully',
                'builderPricing' => $builderPricing
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
        //
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
        $rules = array(
            'item_name' => 'required|string|max:255',
            'applicability' => 'required|string|max:255',
            'price_type' => 'required|in:m2,fixed',
            'base_price' => 'nullable|numeric',
            'markup_percent' => 'nullable|numeric',
            'final_price' => 'nullable|numeric',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $builderPricing = BuilderPricing::findOrFail($id);

            // Optional: make sure the logged-in user owns this pricing
            if ($builderPricing->user_id !== Auth::id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $basePrice = $request->input('base_price');
            $markupPercent = $request->input('markup_percent');
            $finalPrice = $request->input('final_price');

            // If final_price is not provided, calculate it
            if (is_null($finalPrice) && !is_null($basePrice) && !is_null($markupPercent)) {
                $finalPrice = $basePrice * (1 + ($markupPercent / 100));
            }

            $builderPricing->item_name = $request->input('item_name');
            $builderPricing->applicability = $request->input('applicability');
            $builderPricing->price_type = $request->input('price_type');
            $builderPricing->base_price = $basePrice;
            $builderPricing->markup_percent = $markupPercent;
            $builderPricing->final_price = $finalPrice;
            $builderPricing->save();

            return response()->json([
                'message' => 'Builder Pricing updated successfully',
                'builderPricing' => $builderPricing
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $builderPricing = BuilderPricing::findOrFail($id);

            // Optional: Ensure only the owner can delete their pricing
            if ($builderPricing->user_id !== Auth::id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $builderPricing->delete();

            return response()->json([
                'message' => 'Builder Pricing deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
