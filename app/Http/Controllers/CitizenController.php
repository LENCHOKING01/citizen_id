<?php

namespace App\Http\Controllers;

use App\Models\Citizen;
use App\Services\PhotoService;
use App\Services\QRCodeService;
use App\Services\PDFService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CitizenController extends Controller
{
    public function index()
    {
        $citizens = Citizen::with('latestApplication')->paginate(15);
        return view('citizens.index', compact('citizens'));
    }

    public function create()
    {
        return view('citizens.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_number' => 'required|unique:citizens|max:20',
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'middle_name' => 'nullable|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'nationality' => 'required|max:255',
            'address' => 'required|max:500',
            'phone' => 'required|max:20',
            'email' => 'nullable|email|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $citizenData = $request->except('photo');
        $citizen = Citizen::create($citizenData);
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photoService = new PhotoService();
            $photoPath = $photoService->processAndStore($request->file('photo'), $citizen->id);
            $citizen->update(['photo_path' => $photoPath]);
        }
        
        // Generate QR code
        $qrService = new QRCodeService();
        $qrPath = $qrService->generateCitizenQR($citizen);
        $citizen->update(['qr_code_path' => $qrPath]);
        
        return redirect()->route('citizens.show', $citizen)
            ->with('success', 'Citizen registered successfully with photo and QR code!');
    }

    public function show(Citizen $citizen)
    {
        $citizen->load(['applications.registrar', 'applications.supervisor']);
        return view('citizens.show', compact('citizen'));
    }

    public function edit(Citizen $citizen)
    {
        return view('citizens.edit', compact('citizen'));
    }

    public function update(Request $request, Citizen $citizen)
    {
        $validator = Validator::make($request->all(), [
            'id_number' => 'required|max:20|unique:citizens,id_number,' . $citizen->id,
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'middle_name' => 'nullable|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'nationality' => 'required|max:255',
            'address' => 'required|max:500',
            'phone' => 'required|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $citizen->update($request->all());
        
        return redirect()->route('citizens.show', $citizen)
            ->with('success', 'Citizen updated successfully!');
    }

    public function destroy(Citizen $citizen)
    {
        // Clean up associated files
        if ($citizen->photo_path) {
            Storage::disk('public')->delete($citizen->photo_path);
        }
        if ($citizen->qr_code_path) {
            Storage::disk('public')->delete($citizen->qr_code_path);
        }
        
        $citizen->delete();
        return redirect()->route('citizens.index')
            ->with('success', 'Citizen deleted successfully!');
    }

    public function generateIdCard(Citizen $citizen)
    {
        $pdfService = new PDFService();
        $pdfPath = $pdfService->generateIdCard($citizen);
        
        return response()->download(Storage::disk('public')->path($pdfPath))
            ->deleteFileAfterSend(true);
    }

    public function viewIdCard(Citizen $citizen)
    {
        $pdfService = new PDFService();
        return $pdfService->generateIdCardView($citizen);
    }

    public function uploadPhoto(Request $request, Citizen $citizen)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $photoService = new PhotoService();
        
        // Delete old photo if exists
        if ($citizen->photo_path) {
            $photoService->deletePhoto($citizen->photo_path);
        }
        
        $photoPath = $photoService->processAndStore($request->file('photo'), $citizen->id);
        $citizen->update(['photo_path' => $photoPath]);
        
        return redirect()->back()->with('success', 'Photo uploaded successfully!');
    }
}
