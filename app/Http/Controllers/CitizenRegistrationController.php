<?php

namespace App\Http\Controllers;

use App\Models\Citizen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
// QR Code will be generated using a web service
use Illuminate\Support\Str;

class CitizenRegistrationController extends Controller
{
    public function index()
    {
        $citizens = Citizen::latest()->paginate(15);
        return view('citizens.index', compact('citizens'));
    }

    public function create()
    {
        return view('citizens.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'nationality' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|unique:citizens,email',
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle photo upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = time() . '_' . Str::random(10) . '.' . $photo->getClientOriginalExtension();
            $photoPath = $photo->storeAs('citizens/photos', $photoName, 'public');
        }

        // Create citizen record
        $citizen = Citizen::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_name' => $request->middle_name,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'nationality' => $request->nationality,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'photo_path' => $photoPath,
        ]);

        // Generate QR code for verification using Google Charts API
        try {
            $qrData = urlencode(json_encode([
                'id' => $citizen->id,
                'id_number' => $citizen->id_number,
                'name' => $citizen->full_name,
                'verification_url' => route('citizen.verify', $citizen->id_number)
            ]));

            // Generate QR code using Google Charts API
            $qrCodeUrl = "https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=" . $qrData;
            $qrCodeContent = @file_get_contents($qrCodeUrl);
            
            if ($qrCodeContent) {
                $qrCodePath = 'citizens/qr_codes/' . $citizen->id_number . '.png';
                Storage::disk('public')->put($qrCodePath, $qrCodeContent);
                
                // Update citizen with QR code path
                $citizen->update(['qr_code' => $qrCodePath]);
            }
        } catch (\Exception $e) {
            // QR code generation failed, continue without it
            \Log::info('QR code generation failed: ' . $e->getMessage());
        }

        return redirect()->route('citizens.show', $citizen)
            ->with('success', 'Citizen registered successfully! ID Number: ' . $citizen->id_number);
    }

    public function show(Citizen $citizen)
    {
        return view('citizens.show', compact('citizen'));
    }

    public function edit(Citizen $citizen)
    {
        return view('citizens.edit', compact('citizen'));
    }

    public function update(Request $request, Citizen $citizen)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'nationality' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|unique:citizens,email,' . $citizen->id,
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $updateData = $request->only([
            'first_name', 'last_name', 'middle_name', 'date_of_birth',
            'gender', 'nationality', 'address', 'phone', 'email', 'status'
        ]);

        // Handle photo update
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($citizen->photo_path) {
                Storage::disk('public')->delete($citizen->photo_path);
            }

            $photo = $request->file('photo');
            $photoName = time() . '_' . Str::random(10) . '.' . $photo->getClientOriginalExtension();
            $updateData['photo_path'] = $photo->storeAs('citizens/photos', $photoName, 'public');
        }

        $citizen->update($updateData);

        return redirect()->route('citizens.show', $citizen)
            ->with('success', 'Citizen information updated successfully!');
    }

    public function destroy(Citizen $citizen)
    {
        // Delete associated files
        if ($citizen->photo_path) {
            Storage::disk('public')->delete($citizen->photo_path);
        }
        if ($citizen->qr_code) {
            Storage::disk('public')->delete($citizen->qr_code);
        }

        $citizen->delete();

        return redirect()->route('citizens.index')
            ->with('success', 'Citizen record deleted successfully!');
    }

    public function verify($idNumber)
    {
        $citizen = Citizen::where('id_number', $idNumber)->first();
        
        if (!$citizen) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid ID number'
            ], 404);
        }

        return response()->json([
            'valid' => true,
            'citizen' => [
                'id_number' => $citizen->id_number,
                'name' => $citizen->full_name,
                'status' => $citizen->status,
                'expiry_date' => $citizen->id_expiry_date->format('Y-m-d'),
                'is_expired' => $citizen->id_expiry_date->isPast()
            ]
        ]);
    }

    public function generateIdCard(Citizen $citizen)
    {
        return view('citizens.id-card', compact('citizen'));
    }

    public function downloadIdCardPdf(Citizen $citizen)
    {
        // Simple HTML to PDF conversion using browser print
        $html = view('citizens.id-card-pdf', compact('citizen'))->render();
        
        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'inline; filename="id-card-' . $citizen->id_number . '.html"');
    }
}
