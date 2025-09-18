<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citizen ID Card - {{ $citizen->full_name }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        
        body {
            margin: 0;
            padding: 20px;
            font-family: 'Inter', sans-serif;
            background: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .id-card-container {
            perspective: 1000px;
            margin: 20px;
        }

        .id-card {
            width: 340px;
            height: 214px;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 20%, #334155 40%, #475569 60%, #64748b 80%, #94a3b8 100%);
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
            position: relative;
            overflow: hidden;
            transform-style: preserve-3d;
            transition: transform 0.3s ease;
            border: 3px solid rgba(255,215,0,0.5);
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
            background-size: 32px 32px, 24px 24px;
            background-position: 0 0, 12px 12px;
            pointer-events: none;
            z-index: 1;
        }
        
        /* Ethiopian Flag */
        .ethiopian-flag {
            position: absolute;
            top: 2px;
            left: 10px;
            width: 64px;
            height: 40px;
            border: 2px solid rgba(255,255,255,0.8);
            border-radius: 4px;
            overflow: hidden;
            z-index: 4;
            box-shadow: 0 3px 12px rgba(0,0,0,0.4);
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
            width: 22px;
            height: 22px;
            background: #0066CC;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            border: 1px solid rgba(255,255,255,0.3);
        }
        
        .ethiopian-star {
            width: 16px;
            height: 16px;
            position: relative;
            background: #FFD700;
            clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
        }

        .id-card:hover {
            transform: rotateY(5deg) rotateX(5deg);
        }

        /* Background Pattern */
        .id-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }

        /* Header with Logo */
        .card-header {
            position: absolute;
            top: 32px;
            left: 0;
            right: 0;
            height: 45px;
            background: rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            padding: 0 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            z-index: 2;
        }

        .logo {
            width: 32px;
            height: 32px;
            background: #FFD700;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 12px;
            color: #1e40af;
            margin-right: 10px;
        }

        .org-name {
            color: #FFD700;
            font-size: 11px;
            font-weight: 700;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
            letter-spacing: 0.5px;
        }

        /* Main Content Area */
        .card-content {
            position: absolute;
            top: 77px;
            left: 0;
            right: 0;
            bottom: 0;
            padding: 15px;
            display: flex;
            gap: 15px;
        }

        /* Photo Section */
        .photo-section {
            flex-shrink: 0;
        }

        .citizen-photo {
            width: 85px;
            height: 110px;
            border-radius: 8px;
            border: 3px solid rgba(255, 255, 255, 0.9);
            object-fit: cover;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .photo-placeholder {
            width: 85px;
            height: 110px;
            border-radius: 8px;
            border: 3px solid rgba(255, 255, 255, 0.9);
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #6b7280;
            text-align: center;
        }

        /* Info Section */
        .info-section {
            flex: 1;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .citizen-name {
            font-size: 16px;
            font-weight: 700;
            margin: 0 0 8px 0;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
            line-height: 1.2;
        }

        .citizen-id {
            font-size: 14px;
            font-weight: 600;
            margin: 0 0 12px 0;
            color: #fbbf24;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }

        .citizen-details {
            font-size: 10px;
            line-height: 1.4;
            margin-bottom: 8px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }

        .detail-label {
            font-weight: 500;
            opacity: 0.9;
        }

        .detail-value {
            font-weight: 600;
        }

        /* QR Code Section */
        .qr-section {
            position: absolute;
            bottom: 15px;
            right: 15px;
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 6px;
            padding: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .qr-code {
            width: 100%;
            height: 100%;
            border-radius: 2px;
        }

        /* Security Features */
        .security-strip {
            position: absolute;
            top: 0;
            right: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(to bottom, 
                #fbbf24 0%, 
                #f59e0b 25%, 
                #d97706 50%, 
                #b45309 75%, 
                #92400e 100%);
        }

        .hologram {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 20px;
            height: 20px;
            background: conic-gradient(from 0deg, 
                rgba(255, 255, 255, 0.8), 
                rgba(255, 255, 255, 0.3), 
                rgba(255, 255, 255, 0.8));
            border-radius: 50%;
            animation: hologram-shine 3s ease-in-out infinite;
        }

        @keyframes hologram-shine {
            0%, 100% { opacity: 0.6; }
            50% { opacity: 1; }
        }

        /* Print Styles */
        @media print {
            body {
                background: white;
                padding: 0;
                margin: 0;
            }
            
            .id-card-container {
                margin: 0;
                page-break-inside: avoid;
            }
            
            .id-card {
                box-shadow: none;
                transform: none !important;
            }
        }

        /* Controls */
        .controls {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: #3b82f6;
            color: white;
        }

        .btn-primary:hover {
            background: #2563eb;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background: #4b5563;
        }
    </style>
</head>
<body>
    <div class="controls">
        <button onclick="window.print()" class="btn btn-primary">Print ID Card</button>
        <a href="{{ route('citizens.show', $citizen) }}" class="btn btn-secondary">Back to Profile</a>
    </div>

    <div class="id-card-container">
        <div class="id-card">
            <!-- Zigzag Pattern -->
            <div class="zigzag-pattern"></div>
            
            <!-- Security Features -->
            <div class="security-strip"></div>
            <div class="hologram"></div>
            
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
            
            <!-- Header with Organization Logo -->
            <div class="card-header">
                <div class="logo">ETH</div>
                <div class="org-name">FEDERAL DEMOCRATIC REPUBLIC OF ETHIOPIA</div>
            </div>
            
            <!-- Main Content -->
            <div class="card-content">
                <!-- Photo Section -->
                <div class="photo-section">
                    @if($citizen->photo_path)
                        <img src="{{ asset('storage/' . $citizen->photo_path) }}" 
                             alt="Citizen Photo" 
                             class="citizen-photo">
                    @else
                        <div class="photo-placeholder">
                            NO PHOTO<br>AVAILABLE
                        </div>
                    @endif
                </div>
                
                <!-- Information Section -->
                <div class="info-section">
                    <div>
                        <h2 class="citizen-name">{{ strtoupper($citizen->full_name) }}</h2>
                        <div class="citizen-id">ID: {{ $citizen->id_number }}</div>
                        
                        <div class="citizen-details">
                            <div class="detail-row">
                                <span class="detail-label">DOB:</span>
                                <span class="detail-value">{{ $citizen->date_of_birth->format('d/m/Y') }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Gender:</span>
                                <span class="detail-value">{{ strtoupper(substr($citizen->gender, 0, 1)) }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Nationality:</span>
                                <span class="detail-value">{{ strtoupper($citizen->nationality) }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Expires:</span>
                                <span class="detail-value">{{ $citizen->id_expiry_date->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- QR Code Section -->
            <div class="qr-section">
                @if($citizen->qr_code)
                    <img src="{{ asset('storage/' . $citizen->qr_code) }}" 
                         alt="QR Code" 
                         class="qr-code">
                @else
                    <div class="qr-code" style="background: #f3f4f6; display: flex; align-items: center; justify-content: center; font-size: 8px; color: #6b7280;">
                        QR
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            const card = document.querySelector('.id-card');
            
            card.addEventListener('mousemove', function(e) {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                
                const rotateX = (y - centerY) / 10;
                const rotateY = (centerX - x) / 10;
                
                card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
            });
            
            card.addEventListener('mouseleave', function() {
                card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg)';
            });
        });
    </script>
</body>
</html>
