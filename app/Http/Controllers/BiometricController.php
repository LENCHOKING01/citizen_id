<?php

namespace App\Http\Controllers;

use App\Models\Biometric;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BiometricController extends Controller
{
    public function store(Request $request, Application $application)
    {
        $request->validate([
            'fingerprint_data' => 'nullable|string',
            'facial_recognition_data' => 'nullable|string',
            'iris_scan_data' => 'nullable|string',
            'signature_image' => 'nullable|image|mimes:jpeg,png,jpg|max:1024'
        ]);

        $biometricData = [];
        
        if ($request->fingerprint_data) {
            $biometricData['fingerprint'] = $request->fingerprint_data;
        }
        
        if ($request->facial_recognition_data) {
            $biometricData['facial_recognition'] = $request->facial_recognition_data;
        }
        
        if ($request->iris_scan_data) {
            $biometricData['iris_scan'] = $request->iris_scan_data;
        }
        
        // Handle signature upload
        if ($request->hasFile('signature_image')) {
            $signaturePath = $request->file('signature_image')->store('signatures', 'public');
            $biometricData['signature_path'] = $signaturePath;
        }
        
        Biometric::create([
            'citizen_id' => $application->citizen_id,
            'application_id' => $application->id,
            'fingerprint_data' => $biometricData['fingerprint'] ?? null,
            'facial_recognition_data' => $biometricData['facial_recognition'] ?? null,
            'iris_scan_data' => $biometricData['iris_scan'] ?? null,
            'signature_path' => $biometricData['signature_path'] ?? null,
            'captured_by' => auth()->id()
        ]);
        
        // Update application with biometric data
        $application->update([
            'biometric_data' => $biometricData
        ]);
        
        return redirect()->back()->with('success', 'Biometric data captured successfully!');
    }
    
    public function show(Biometric $biometric)
    {
        return view('biometrics.show', compact('biometric'));
    }
    
    public function destroy(Biometric $biometric)
    {
        // Delete signature file if exists
        if ($biometric->signature_path) {
            Storage::disk('public')->delete($biometric->signature_path);
        }
        
        $biometric->delete();
        
        return redirect()->back()->with('success', 'Biometric data deleted successfully!');
    }
}
