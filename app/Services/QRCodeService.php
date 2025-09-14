<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use App\Models\Citizen;

class QRCodeService
{
    public function generateCitizenQR(Citizen $citizen): string
    {
        // Create verification URL with citizen ID
        $verificationUrl = url('/verify/' . $citizen->id_number);
        
        // Use Google Charts API to generate QR code (no GD extension required)
        $qrCodeUrl = 'https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=' . urlencode($verificationUrl);
        
        // Download QR code image
        $qrCodeData = file_get_contents($qrCodeUrl);
        
        if ($qrCodeData === false) {
            throw new \Exception('Failed to generate QR code');
        }

        // Generate filename
        $filename = 'qr_' . $citizen->id_number . '_' . time() . '.png';
        $path = 'qrcodes/' . $filename;

        // Store QR code
        Storage::disk('public')->put($path, $qrCodeData);

        return $path;
    }

    public function generateApplicationQR(string $applicationId): string
    {
        // Create application tracking URL
        $trackingUrl = url('/track/' . $applicationId);
        
        // Use Google Charts API to generate QR code
        $qrCodeUrl = 'https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=' . urlencode($trackingUrl);
        
        // Download QR code image
        $qrCodeData = file_get_contents($qrCodeUrl);
        
        if ($qrCodeData === false) {
            throw new \Exception('Failed to generate QR code');
        }

        $filename = 'app_qr_' . $applicationId . '_' . time() . '.png';
        $path = 'qrcodes/' . $filename;

        Storage::disk('public')->put($path, $qrCodeData);

        return $path;
    }

    public function deleteQRCode(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }
}
