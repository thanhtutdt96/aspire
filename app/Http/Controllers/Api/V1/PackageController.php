<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $packages = Package::whereEnabled(1)->get();

        return response()->json([
            'packages' => $packages
        ]);
    }

    /**
     * Create package
     */
    public function store(Request $request)
    {
        // Validate request data
        $validators = [
            'interest_rate' => 'required|numeric',
            'months' => 'required|numeric',
            'arrangement_fee_rate' => 'required|numeric'
        ];

        $validator = Validator::make($request->all(), $validators);

        // Return validation results
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->messages()
            ]);
        } else {
            // Create new package
            $package = Package::create($request->all());
        }

        return response()->json([
            'message' => 'Package has been created successfully'
        ]);
    }

    /**
     * Display the specified package
     */
    public function show($id)
    {
        $package = Package::findOrFail($id);

        if (!$package->enabled) {
            return response()->json([
                'message' => 'Package is not available'
            ]);
        }

        return response()->json([
            'package' => $package
        ]);
    }

    /**
     * Update the specified package
     */
    public function update(Request $request, $id)
    {
        // Validate request data
        $validators = [
            'interest_rate' => 'numeric',
            'months' => 'numeric',
            'arrangement_fee_rate' => 'numeric'
        ];

        $validator = Validator::make($request->all(), $validators);

        // Return validation results
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->messages()
            ]);
        } else {
            $loan = Package::findOrFail($id);
            $loan->update($request->all());
        }

        return response()->json([
            'message' => 'Package updated successfully'
        ]);
    }

    /**
     * Remove the specified package
     */
    public function destroy($id)
    {
        $package = Package::findOrFail($id);

        $package->delete();

        return response()->json([
            'message' => 'Pakage has been deleted successfully'
        ]);
    }
}
