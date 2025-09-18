<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ethiopian Citizen ID Card</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        
        .id-card {
            width: 85.6mm;
            height: 54mm;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 20%, #334155 40%, #475569 60%, #64748b 80%, #94a3b8 100%);
            border-radius: 8px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.4);
            position: relative;
            overflow: hidden;
            font-family: 'Inter', Arial, sans-serif;
            border: 2px solid rgba(255,215,0,0.4);
        }
        
        .zigzag-pattern {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                linear-gradient(45deg, transparent 25%, rgba(255,215,0,0.1) 25%, rgba(255,215,0,0.1) 50%, transparent 50%, transparent 75%, rgba(255,215,0,0.1) 75%),
                linear-gradient(-45deg, transparent 25%, rgba(255,255,255,0.05) 25%, rgba(255,255,255,0.05) 50%, transparent 50%, transparent 75%, rgba(255,255,255,0.05) 75%);
            background-size: 8mm 8mm, 6mm 6mm;
            background-position: 0 0, 3mm 3mm;
            pointer-events: none;
            z-index: 1;
        }
        
        .ethiopian-flag {
            position: absolute;
            top: 0.5mm;
            left: 2mm;
            width: 16mm;
            height: 10mm;
            border: 1px solid rgba(255,255,255,0.6);
            border-radius: 3px;
            overflow: hidden;
            z-index: 4;
            box-shadow: 0 3px 8px rgba(0,0,0,0.4);
        }
        
        .flag-stripe {
            height: 33.33%;
            width: 100%;
            position: relative;
        }
        
        .flag-green { background-color: #009639; }
        .flag-yellow { 
            background-color: #FEDD00; 
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .flag-red { background-color: #DA020E; }
        
        .ethiopian-emblem-circle {
            width: 5.5mm;
            height: 5.5mm;
            background: #0066CC;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            border: 1px solid rgba(255,255,255,0.3);
        }
        
        .ethiopian-star {
            width: 4mm;
            height: 4mm;
            position: relative;
            background: #FFD700;
            clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
        }
        
        .header {
            background: rgba(0,0,0,0.2);
            padding: 1.5mm 3mm;
            text-align: center;
            position: relative;
            z-index: 2;
        }
        
        .header-title {
            font-size: 7px;
            font-weight: 700;
            color: #FFD700;
            letter-spacing: 0.5px;
            margin: 0;
        }
        
        .header-subtitle {
            font-size: 5px;
            color: rgba(255,255,255,0.9);
            margin: 0.5mm 0 0 0;
            font-weight: 500;
        }
        
        .main-content {
            display: flex;
            padding: 2mm;
            height: calc(54mm - 12mm);
            position: relative;
            z-index: 2;
        }
        
        .photo-section {
            width: 20mm;
            margin-right: 2mm;
        }
        
        .photo-frame {
            width: 18mm;
            height: 22mm;
            border: 2px solid #FFD700;
            border-radius: 4px;
            overflow: hidden;
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        }
        
        .photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .no-photo {
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, #f3f4f6, #e5e7eb);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            font-size: 5px;
            font-weight: 600;
        }
        
        .info-section {
            flex: 1;
            color: white;
            margin-right: 2mm;
        }
        
        .citizen-name {
            font-size: 9px;
            font-weight: 700;
            color: #FFD700;
            margin-bottom: 1mm;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
            line-height: 1.1;
        }
        
        .id-number {
            font-size: 7px;
            color: #FFF;
            background: rgba(255,215,0,0.2);
            padding: 0.5mm 1mm;
            border-radius: 2px;
            display: inline-block;
            margin-bottom: 2mm;
            font-weight: 600;
            border: 1px solid rgba(255,215,0,0.3);
        }
        
        .details {
            font-size: 6px;
            line-height: 1.3;
            color: rgba(255,255,255,0.95);
        }
        
        .detail-row {
            margin-bottom: 0.8mm;
            display: flex;
        }
        
        .detail-label {
            font-weight: 600;
            width: 12mm;
            color: #FFD700;
        }
        
        .detail-value {
            font-weight: 500;
        }
        
        .qr-section {
            width: 16mm;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .qr-frame {
            width: 14mm;
            height: 14mm;
            border: 2px solid #FFD700;
            border-radius: 4px;
            overflow: hidden;
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
            margin-bottom: 1mm;
        }
        
        .qr-code {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .no-qr {
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, #f3f4f6, #e5e7eb);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            font-size: 4px;
            font-weight: 600;
        }
        
        .qr-label {
            font-size: 4px;
            color: rgba(255,255,255,0.8);
            text-align: center;
            font-weight: 500;
        }
        
        .security-pattern {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3mm;
            background: repeating-linear-gradient(
                45deg,
                rgba(255,215,0,0.1) 0px,
                rgba(255,215,0,0.1) 2px,
                transparent 2px,
                transparent 4px
            );
        }
        
        .ethiopian-emblem {
            position: absolute;
            top: 8mm;
            right: 2mm;
            width: 8mm;
            height: 8mm;
            background: radial-gradient(circle, #FFD700 30%, transparent 30%);
            border-radius: 50%;
            opacity: 0.3;
        }
        
        /* Card Back Styles */
        .card-back {
            width: 85.6mm;
            height: 54mm;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 20%, #334155 40%, #475569 60%, #64748b 80%, #94a3b8 100%);
            border-radius: 8px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.4);
            position: relative;
            overflow: hidden;
            font-family: 'Inter', Arial, sans-serif;
            margin-top: 5mm;
            border: 2px solid rgba(255,215,0,0.4);
        }
        
        .back-header {
            background: rgba(0,0,0,0.3);
            padding: 2mm 3mm;
            text-align: center;
            color: #FFD700;
            font-size: 6px;
            font-weight: 700;
        }
        
        .biometric-section {
            padding: 3mm;
            color: white;
        }
        
        .biometric-title {
            font-size: 7px;
            font-weight: 700;
            color: #FFD700;
            margin-bottom: 2mm;
            text-align: center;
        }
        
        .biometric-numbers {
            display: flex;
            flex-direction: column;
            gap: 2mm;
        }
        
        .biometric-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255,255,255,0.1);
            padding: 1.5mm;
            border-radius: 2mm;
            border: 1px solid rgba(255,215,0,0.3);
        }
        
        .biometric-label {
            font-size: 6px;
            font-weight: 600;
            color: #FFD700;
        }
        
        .biometric-value {
            font-size: 6px;
            font-weight: 500;
            color: white;
            font-family: 'Courier New', monospace;
        }
        
        .signature-section {
            position: absolute;
            bottom: 3mm;
            left: 3mm;
            right: 3mm;
        }
        
        .signature-line {
            border-top: 1px solid rgba(255,255,255,0.5);
            padding-top: 1mm;
            text-align: center;
            font-size: 5px;
            color: rgba(255,255,255,0.8);
        }
        
        .security-text {
            position: absolute;
            bottom: 8mm;
            left: 3mm;
            right: 3mm;
            text-align: center;
            font-size: 4px;
            color: rgba(255,255,255,0.6);
            line-height: 1.2;
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background: white;">

<!-- FRONT SIDE -->
<div class="id-card">
    <!-- Zigzag Pattern -->
    <div class="zigzag-pattern"></div>
    
    <!-- Ethiopian Flag -->
    <div class="ethiopian-flag">
        <div class="flag-stripe flag-green"></div>
        <div class="flag-stripe flag-yellow">
            <div class="ethiopian-emblem-circle">
                <div class="ethiopian-star"></div>
            </div>
        </div>
        <div class="flag-stripe flag-red"></div>
    </div>
    
    <!-- Header -->
    <div class="header">
        <div class="header-title">FEDERAL DEMOCRATIC REPUBLIC OF ETHIOPIA</div>
        <div class="header-subtitle">CITIZEN IDENTIFICATION CARD</div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Photo Section -->
        <div class="photo-section">
            <div class="photo-frame">
                @if($citizen->photo_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($citizen->photo_path))
                    <img src="data:image/jpeg;base64,{{ base64_encode(\Illuminate\Support\Facades\Storage::disk('public')->get($citizen->photo_path)) }}" class="photo" alt="Citizen Photo">
                @else
                    <div class="no-photo">NO PHOTO</div>
                @endif
            </div>
        </div>
        
        <!-- Information Section -->
        <div class="info-section">
            <div class="citizen-name">
                {{ strtoupper($citizen->first_name . ' ' . ($citizen->middle_name ? $citizen->middle_name . ' ' : '') . $citizen->last_name) }}
            </div>
            
            <div class="id-number">
                ID: {{ $citizen->id_number }}
            </div>
            
            <div class="details">
                <div class="detail-row">
                    <span class="detail-label">DOB:</span>
                    <span class="detail-value">{{ $citizen->date_of_birth->format('d/m/Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Gender:</span>
                    <span class="detail-value">{{ strtoupper($citizen->gender) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Nationality:</span>
                    <span class="detail-value">ETHIOPIAN</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Expires:</span>
                    <span class="detail-value">{{ $citizen->date_of_birth->addYears(10)->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>
        
        <!-- QR Code Section -->
        <div class="qr-section">
            <div class="qr-frame">
                @if($citizen->qr_code_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($citizen->qr_code_path))
                    <img src="data:image/png;base64,{{ base64_encode(\Illuminate\Support\Facades\Storage::disk('public')->get($citizen->qr_code_path)) }}" class="qr-code" alt="QR Code">
                @else
                    <div class="no-qr">QR CODE</div>
                @endif
            </div>
            <div class="qr-label">SCAN TO VERIFY</div>
        </div>
    </div>
    
    <!-- Security Elements -->
    <div class="security-pattern"></div>
    <div class="ethiopian-emblem"></div>
</div>

<!-- BACK SIDE -->
<div class="card-back">
    <!-- Zigzag Pattern -->
    <div class="zigzag-pattern"></div>
    
    <!-- Back Header -->
    <div class="back-header">
        BIOMETRIC IDENTIFICATION DATA
    </div>
    
    <!-- Biometric Section -->
    <div class="biometric-section">
        <div class="biometric-title">SECURITY IDENTIFIERS</div>
        
        <div class="biometric-numbers">
            <div class="biometric-row">
                <span class="biometric-label">FIN (Fingerprint ID):</span>
                <span class="biometric-value">{{ strtoupper(substr(md5($citizen->id_number . 'FIN'), 0, 12)) }}</span>
            </div>
            
            <div class="biometric-row">
                <span class="biometric-label">FAN (Facial Auth No):</span>
                <span class="biometric-value">{{ strtoupper(substr(md5($citizen->id_number . 'FAN'), 0, 12)) }}</span>
            </div>
            
            <div class="biometric-row">
                <span class="biometric-label">Issue Date:</span>
                <span class="biometric-value">{{ $citizen->created_at->format('d/m/Y') }}</span>
            </div>
            
            <div class="biometric-row">
                <span class="biometric-label">Authority:</span>
                <span class="biometric-value">ETH-CID-{{ strtoupper(substr($citizen->id_number, -4)) }}</span>
            </div>
        </div>
    </div>
    
    <!-- Security Text -->
    <div class="security-text">
        This card contains sensitive biometric data protected by Ethiopian Federal Law.<br>
        Unauthorized duplication or misuse is strictly prohibited.<br>
        Report lost or stolen cards immediately to authorities.
    </div>
    
    <!-- Signature Section -->
    <div class="signature-section">
        <div class="signature-line">
            AUTHORIZED SIGNATURE
        </div>
    </div>
    
    <!-- Security Elements -->
    <div class="security-pattern"></div>
    <div class="ethiopian-emblem"></div>
</div>

</body>
</html>
