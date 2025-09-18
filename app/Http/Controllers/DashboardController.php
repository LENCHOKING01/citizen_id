<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Citizen;
use App\Models\User;
use App\Models\PrintJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        
        $stats = [
            'total_citizens' => Citizen::count(),
            'total_applications' => Application::count(),
            'pending_applications' => Application::where('status', 'pending')->count(),
            'approved_applications' => Application::where('status', 'approved')->count(),
            'rejected_applications' => Application::where('status', 'rejected')->count(),
            'print_jobs_pending' => PrintJob::where('status', 'pending')->count(),
        ];
        
        $recent_applications = Application::with(['citizen', 'registrar'])
            ->latest()
            ->take(10)
            ->get();
            
        $recent_citizens = Citizen::latest()->take(5)->get();
        
        return view('dashboard', compact('stats', 'recent_applications', 'recent_citizens'));
    }
}
