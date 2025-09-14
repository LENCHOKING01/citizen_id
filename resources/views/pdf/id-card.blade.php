<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Citizen ID Card</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background: white;">

<table cellpadding="0" cellspacing="0" border="0" style="width: 85.6mm; height: 54mm; background-color: #4A90E2; border: 1px solid #357ABD;">
    <!-- Header Row -->
    <tr>
        <td colspan="3" style="background-color: #357ABD; padding: 2mm; text-align: center; font-size: 8px; font-weight: bold; color: white;">
            CITIZEN IDENTIFICATION DEPARTMENT
        </td>
    </tr>
    
    <!-- Main Content Row -->
    <tr>
        <!-- Photo Column -->
        <td style="width: 22mm; padding: 2mm; vertical-align: top;">
            @if($citizen->photo_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($citizen->photo_path))
                <img src="data:image/jpeg;base64,{{ base64_encode(\Illuminate\Support\Facades\Storage::disk('public')->get($citizen->photo_path)) }}" style="width: 18mm; height: 22mm; border: 1px solid white;">
            @else
                <div style="width: 18mm; height: 22mm; background-color: white; text-align: center; line-height: 22mm; color: #999; font-size: 6px; border: 1px solid white;">NO PHOTO</div>
            @endif
        </td>
        
        <!-- Info Column -->
        <td style="padding: 2mm; vertical-align: top; color: white;">
            <!-- Name -->
            <div style="font-size: 10px; font-weight: bold; color: white; margin-bottom: 1mm;">
                {{ strtoupper($citizen->first_name . ' ' . ($citizen->middle_name ? $citizen->middle_name . ' ' : '') . $citizen->last_name) }}
            </div>
            
            <!-- ID Number -->
            <div style="font-size: 8px; color: #FFD700; margin-bottom: 2mm;">
                ID: {{ $citizen->id_number }}
            </div>
            
            <!-- Personal Details -->
            <div style="font-size: 7px; color: white;">
                <strong>DOB:</strong> {{ $citizen->date_of_birth->format('d/m/Y') }}<br>
                <strong>Gender:</strong> {{ strtoupper($citizen->gender) }}<br>
                <strong>Nationality:</strong> {{ strtoupper($citizen->nationality) }}<br>
                <strong>Expires:</strong> {{ $citizen->date_of_birth->addYears(10)->format('d/m/Y') }}
            </div>
        </td>
        
        <!-- QR Code Column -->
        <td style="width: 15mm; padding: 2mm; vertical-align: top;">
            @if($citizen->qr_code_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($citizen->qr_code_path))
                <img src="data:image/png;base64,{{ base64_encode(\Illuminate\Support\Facades\Storage::disk('public')->get($citizen->qr_code_path)) }}" style="width: 12mm; height: 12mm; border: 1px solid white;">
            @else
                <div style="width: 12mm; height: 12mm; background-color: white; text-align: center; line-height: 12mm; color: #999; font-size: 5px; border: 1px solid white;">QR</div>
            @endif
        </td>
    </tr>
</table>

</body>
</html>
