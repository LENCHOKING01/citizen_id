<?php

namespace App\Services;

use App\Models\Citizen;
use App\Models\Application;
use Illuminate\Support\Facades\Storage;

class PDFService
{
    public function generateIdCard(Citizen $citizen)
    {
        $data = [
            'citizen' => $citizen,
            'generated_at' => now()->format('F j, Y \a\t g:i A'),
        ];

        $html = view('pdf.id-card', $data)->render();
        
        // Create TCPDF instance with ID card dimensions
        $pdf = new \TCPDF('L', 'mm', [85.6, 54], true, 'UTF-8', false);
        
        // Set document information
        $pdf->SetCreator('Citizen ID System');
        $pdf->SetAuthor('Government');
        $pdf->SetTitle('Citizen ID Card - ' . $citizen->id_number);
        
        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        // Set margins
        $pdf->SetMargins(0, 0, 0);
        $pdf->SetAutoPageBreak(false, 0);
        
        // Add a page
        $pdf->AddPage();
        
        // Write HTML content
        $pdf->writeHTML($html, true, false, true, false, '');
        
        // Generate filename
        $filename = 'id-card-' . $citizen->id_number . '-' . now()->format('Y-m-d-H-i-s') . '.pdf';
        $filepath = 'id-cards/' . $filename;
        
        // Save PDF to storage
        Storage::disk('public')->put($filepath, $pdf->Output('', 'S'));
        
        return $filepath;
    }

    public function generateIdCardView(Citizen $citizen)
    {
        $data = [
            'citizen' => $citizen,
            'generated_at' => now()->format('F j, Y \a\t g:i A'),
        ];

        return view('pdf.id-card', $data);
    }

    public function generateApplicationReport(Application $application)
    {
        // Temporary implementation - return HTML view for now
        $data = [
            'application' => $application,
            'citizen' => $application->citizen,
            'registrar' => $application->registrar,
            'generated_at' => now()->format('F j, Y \a\t g:i A'),
        ];

        return view('pdf.application-report', $data);
    }

    public function generateCitizenReport(Citizen $citizen)
    {
        // Temporary implementation - return HTML view for now
        $data = [
            'citizen' => $citizen,
            'applications' => $citizen->applications()->with('registrar')->get(),
            'generated_at' => now()->format('F j, Y \a\t g:i A'),
        ];

        return view('pdf.citizen-report', $data);
    }

    public function downloadIdCard(Citizen $citizen)
    {
        // For now, return HTML response with print-friendly styling
        $view = $this->generateIdCard($citizen);
        
        return response($view->render())
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'inline; filename="id-card-' . $citizen->id_number . '.html"');
    }

    public function downloadApplicationReport(Application $application)
    {
        // For now, return HTML response with print-friendly styling
        $view = $this->generateApplicationReport($application);
        
        return response($view->render())
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'inline; filename="application-report-' . $application->id . '.html"');
    }

    public function downloadCitizenReport(Citizen $citizen)
    {
        // For now, return HTML response with print-friendly styling
        $view = $this->generateCitizenReport($citizen);
        
        return response($view->render())
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'inline; filename="citizen-report-' . $citizen->id_number . '.html"');
    }
}
