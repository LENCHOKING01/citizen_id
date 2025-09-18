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
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function index(): View
    {
        $applications = Application::with(['citizen', 'registrar', 'supervisor'])
            ->latest()
            ->paginate(15);
        return view('applications.index', compact('applications'));
    }

    public function create(): View
    {
        $citizens = Citizen::all();
        return view('applications.create', compact('citizens'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'citizen_id' => 'required|exists:citizens,id',
        ]);

        try {
            $application = Application::create([
                'citizen_id' => $validatedData['citizen_id'],
                'registrar_id' => Auth::id(),
                'status' => 'draft',
                'submitted_at' => now(),
            ]);

            return redirect()->route('applications.show', $application)
                ->with('success', 'Application created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create application: ' . $e->getMessage()]);
        }
    }

    public function show(Application $application): View
    {
        // Load all relationships including documents
        $application->load([
            'citizen', 
            'registrar', 
            'supervisor', 
            'documents', 
            'biometrics', 
            'printJobs'
        ]);
        
        return view('applications.show', compact('application'));
    }

    public function edit(Application $application): View
    {
        return view('applications.edit', compact('application'));
    }

    public function update(Request $request, Application $application): RedirectResponse
    {
        $validatedData = $request->validate([
            'status' => 'required|in:draft,pending,approved,rejected,printed',
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        try {
            $updateData = collect($validatedData)->only(['status', 'rejection_reason'])->toArray();
            
            if ($validatedData['status'] === 'approved') {
                $updateData['supervisor_id'] = Auth::id();
                $updateData['approved_at'] = now();
            }
            
            if ($validatedData['status'] === 'printed') {
                $updateData['printed_at'] = now();
            }

            $application->update($updateData);

            return redirect()->route('applications.show', $application)
                ->with('success', 'Application updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update application: ' . $e->getMessage()]);
        }
    }

    public function destroy(Application $application): RedirectResponse
    {
        try {
            $application->delete();
            return redirect()->route('applications.index')
                ->with('success', 'Application deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to delete application: ' . $e->getMessage()]);
        }
    }

    public function uploadDocument(Request $request, Application $application): RedirectResponse
    {
        $validatedData = $request->validate([
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'document_type' => 'required|string|max:255',
        ]);

        try {
            $file = $request->file('document');
            $path = $file->store('documents', 'public');

            Document::create([
                'application_id' => $application->id,
                'document_type' => $validatedData['document_type'],
                'file_path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);

            return redirect()->route('applications.show', $application)
                ->with('success', 'Document uploaded successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['document' => 'Failed to upload document: ' . $e->getMessage()]);
        }
    }

    public function storeBiometric(Request $request, Application $application): RedirectResponse
    {
        $biometricController = new \App\Http\Controllers\BiometricController();
        return $biometricController->store($request, $application);
    }
}
