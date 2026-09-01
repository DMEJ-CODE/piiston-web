<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Administration\BusinessVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    public function pendingBusinesses()
    {
        return response()->json(
            BusinessVerification::where('verification_status', 'pending')->get()
        );
    }

    public function approveBusiness(Request $request, $id)
    {
        $verification = BusinessVerification::findOrFail($id);
        $verification->update([
            'verified_by' => Auth::user()->administrator->id,
            'verification_status' => 'verified',
            'verified_at' => now(),
            'verification_notes' => $request->notes,
        ]);

        return response()->json(['message' => 'Business verified successfully']);
    }
}
