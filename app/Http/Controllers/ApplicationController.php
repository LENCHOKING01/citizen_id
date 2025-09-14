<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Citizen;
use App\Models\Document;
use App\Models\Biometric;
use App\Models\PrintJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = Application::with(['citizen', 'registrar', 'supervisor'])
            ->latest()
            ->paginate(15);
        return view('applications.index', compact('applications'));
    }

    public function create()
    {
        $citizens = Citizen::all();
        return view('applications.create', compact('citizens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'citizen_id' => 'required|exists:citizens,id',
        ]);

        $application = Application::create([
            'citizen_id' => $request->citizen_id,
            'registrar_id' => Auth::id(),
            'status' => 'draft',
            'submitted_at' => now(),
        ]);

        return redirect()->route('applications.show', $application)
            ->with('success', 'Application created successfully!');
    }

    public function show(Application $application)
    {
        $application->load(['citizen', 'registrar', 'supervisor', 'documents', 'biometrics', 'printJobs']);
        return view('applications.show', compact('application'));
    }

    public function edit(Application $application)
    {
        return view('applications.edit', compact('application'));
    }

    public function update(Request $request, Application $application)
    {
        $request->validate([
            'status' => 'required|in:draft,pending,approved,rejected,printed',
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $updateData = $request->only(['status', 'rejection_reason']);
        
        if ($request->status === 'approved') {
            $updateData['supervisor_id'] = Auth::id();
            $updateData['approved_at'] = now();
        }
        
        if ($request->status === 'printed') {
            $updateData['printed_at'] = now();
        }

        $application->update($updateData);

        return redirect()->route('applications.show', $application)
            ->with('success', 'Application updated successfully!');
    }

    public function destroy(Application $application)
    {
        $application->delete();
        return redirect()->route('applications.index')
            ->with('success', 'Application deleted successfully!');
    }

    public function uploadDocument(Request $request, Application $application)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'document_type' => 'required|string|max:255',
        ]);

        $file = $request->file('document');
        $path = $file->store('documents', 'public');

        Document::create([
            'application_id' => $application->id,
            'document_type' => $request->document_type,
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);

        return redirect()->route('applications.show', $application)
            ->with('success', 'Document uploaded successfully!');
    }

    public function storeBiometric(Request $request, Application $application)
    {
        $biometricController = new \App\Http\Controllers\BiometricController();
        return $biometricController->store($request, $application);
            'fingerprint_data' => $request->fingerprint_data,
            'photo_path' => $photoPath,
            'captured_at' => now(),
        ]);

        return redirect()->route('applications.show', $application)
            ->with('success', 'Biometric data stored successfully!');
    }
}
