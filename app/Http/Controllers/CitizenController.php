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
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CitizenController extends Controller
{
    protected PhotoService $photoService;
    protected QRCodeService $qrService;
    protected PDFService $pdfService;

    public function __construct(
        PhotoService $photoService,
        QRCodeService $qrService,
        PDFService $pdfService
    ) {
        $this->photoService = $photoService;
        $this->qrService = $qrService;
        $this->pdfService = $pdfService;
    }
    public function index(): View
    {
        $citizens = Citizen::with('latestApplication')->paginate(15);
        return view('citizens.index', compact('citizens'));
    }

    public function create(): View
    {
        return view('citizens.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'id_number' => 'required|unique:citizens|max:20',
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'middle_name' => 'nullable|max:255',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'nationality' => 'required|max:255',
            'address' => 'required|max:500',
            'phone' => 'required|max:20',
            'email' => 'nullable|email|max:255|unique:citizens,email',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $citizenData = collect($validatedData)->except('photo')->toArray();
            $citizen = Citizen::create($citizenData);
            
            // Handle photo upload
            if ($request->hasFile('photo')) {
                $photoPath = $this->photoService->processAndStore(
                    $request->file('photo'), 
                    (string) $citizen->id
                );
                $citizen->update(['photo_path' => $photoPath]);
            }
            
            // Generate QR code
            $qrPath = $this->qrService->generateCitizenQR($citizen);
            $citizen->update(['qr_code_path' => $qrPath]);
            
            return redirect()->route('citizens.show', $citizen)
                ->with('success', 'Citizen registered successfully with photo and QR code!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to register citizen: ' . $e->getMessage()]);
        }
    }

    public function show(Citizen $citizen): View
    {
        $citizen->load(['applications.registrar', 'applications.supervisor']);
        return view('citizens.show', compact('citizen'));
    }

    public function edit(Citizen $citizen): View
    {
        return view('citizens.edit', compact('citizen'));
    }

    public function update(Request $request, Citizen $citizen): RedirectResponse
    {
        $validatedData = $request->validate([
            'id_number' => 'required|max:20|unique:citizens,id_number,' . $citizen->id,
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'middle_name' => 'nullable|max:255',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'nationality' => 'required|max:255',
            'address' => 'required|max:500',
            'phone' => 'required|max:20',
            'email' => 'nullable|email|max:255|unique:citizens,email,' . $citizen->id,
        ]);

        try {
            $citizen->update($validatedData);
            
            return redirect()->route('citizens.show', $citizen)
                ->with('success', 'Citizen updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update citizen: ' . $e->getMessage()]);
        }
    }

    public function destroy(Citizen $citizen): RedirectResponse
    {
        try {
            // Clean up associated files
            if ($citizen->photo_path) {
                $this->photoService->deletePhoto($citizen->photo_path);
            }
            if ($citizen->qr_code_path) {
                Storage::disk('public')->delete($citizen->qr_code_path);
            }
            
            $citizen->delete();
            
            return redirect()->route('citizens.index')
                ->with('success', 'Citizen deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to delete citizen: ' . $e->getMessage()]);
        }
    }

    public function generateIdCard(Citizen $citizen): BinaryFileResponse
    {
        try {
            $pdfPath = $this->pdfService->generateIdCard($citizen);
            
            return response()->download(Storage::disk('public')->path($pdfPath))
                ->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            abort(500, 'Failed to generate ID card: ' . $e->getMessage());
        }
    }

    public function viewIdCard(Citizen $citizen): View
    {
        return $this->pdfService->generateIdCardView($citizen);
    }

    public function uploadPhoto(Request $request, Citizen $citizen): RedirectResponse
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            // Delete old photo if exists
            if ($citizen->photo_path) {
                $this->photoService->deletePhoto($citizen->photo_path);
            }
            
            $photoPath = $this->photoService->processAndStore(
                $request->file('photo'), 
                (string) $citizen->id
            );
            $citizen->update(['photo_path' => $photoPath]);
            
            return redirect()->back()->with('success', 'Photo uploaded successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['photo' => 'Failed to upload photo: ' . $e->getMessage()]);
        }
    }
}
