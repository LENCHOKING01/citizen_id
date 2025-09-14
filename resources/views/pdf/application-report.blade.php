<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Application Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #667eea;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 10px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 12px;
            color: #666;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #667eea;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        .info-grid {
            display: table;
            width: 100%;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            font-weight: bold;
            width: 150px;
            padding: 5px 10px 5px 0;
            vertical-align: top;
        }
        .info-value {
            display: table-cell;
            padding: 5px 0;
            vertical-align: top;
        }
        .status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-draft { background: #f3f4f6; color: #374151; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-approved { background: #d1fae5; color: #065f46; }
        .status-rejected { background: #fee2e2; color: #991b1b; }
        .status-printed { background: #dbeafe; color: #1e40af; }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">CITIZEN ID MANAGEMENT SYSTEM</div>
        <div class="title">Application Report</div>
        <div class="subtitle">Application ID: {{ $application->id }}</div>
    </div>

    <div class="section">
        <div class="section-title">Application Information</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Application ID:</div>
                <div class="info-value">{{ $application->id }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Status:</div>
                <div class="info-value">
                    <span class="status status-{{ $application->status }}">{{ ucfirst($application->status) }}</span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Submitted:</div>
                <div class="info-value">{{ $application->submitted_at->format('F j, Y g:i A') }}</div>
            </div>
            @if($application->approved_at)
            <div class="info-row">
                <div class="info-label">Approved:</div>
                <div class="info-value">{{ $application->approved_at->format('F j, Y g:i A') }}</div>
            </div>
            @endif
            @if($application->rejected_at)
            <div class="info-row">
                <div class="info-label">Rejected:</div>
                <div class="info-value">{{ $application->rejected_at->format('F j, Y g:i A') }}</div>
            </div>
            @endif
            @if($application->rejection_reason)
            <div class="info-row">
                <div class="info-label">Rejection Reason:</div>
                <div class="info-value">{{ $application->rejection_reason }}</div>
            </div>
            @endif
        </div>
    </div>

    <div class="section">
        <div class="section-title">Citizen Information</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Full Name:</div>
                <div class="info-value">{{ $citizen->first_name }} {{ $citizen->middle_name }} {{ $citizen->last_name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">ID Number:</div>
                <div class="info-value">{{ $citizen->id_number }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Date of Birth:</div>
                <div class="info-value">{{ $citizen->date_of_birth->format('F j, Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Gender:</div>
                <div class="info-value">{{ ucfirst($citizen->gender) }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Nationality:</div>
                <div class="info-value">{{ $citizen->nationality }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Phone:</div>
                <div class="info-value">{{ $citizen->phone }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value">{{ $citizen->email ?: 'Not provided' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Address:</div>
                <div class="info-value">{{ $citizen->address }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Processing Staff</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Registrar:</div>
                <div class="info-value">{{ $registrar->name }} ({{ $registrar->email }})</div>
            </div>
            @if($supervisor)
            <div class="info-row">
                <div class="info-label">Supervisor:</div>
                <div class="info-value">{{ $supervisor->name }} ({{ $supervisor->email }})</div>
            </div>
            @endif
        </div>
    </div>

    <div class="footer">
        <p>This report was generated on {{ $generated_at }}</p>
        <p>Citizen ID Management System - Confidential Document</p>
    </div>
</body>
</html>
