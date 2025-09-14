<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Citizen Report</title>
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
        .applications-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .applications-table th,
        .applications-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }
        .applications-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .status {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
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
        <div class="title">Citizen Report</div>
        <div class="subtitle">ID: {{ $citizen->id_number }}</div>
    </div>

    <div class="section">
        <div class="section-title">Personal Information</div>
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
                <div class="info-label">Age:</div>
                <div class="info-value">{{ $citizen->date_of_birth->age }} years</div>
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
            <div class="info-row">
                <div class="info-label">Registration Date:</div>
                <div class="info-value">{{ $citizen->created_at->format('F j, Y g:i A') }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Application History</div>
        @if($applications->count() > 0)
            <table class="applications-table">
                <thead>
                    <tr>
                        <th>Application ID</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Registrar</th>
                        <th>Supervisor</th>
                        <th>Last Updated</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications as $application)
                    <tr>
                        <td>{{ $application->id }}</td>
                        <td>
                            <span class="status status-{{ $application->status }}">
                                {{ ucfirst($application->status) }}
                            </span>
                        </td>
                        <td>{{ $application->submitted_at->format('M j, Y') }}</td>
                        <td>{{ $application->registrar->name ?? 'N/A' }}</td>
                        <td>{{ $application->supervisor->name ?? 'N/A' }}</td>
                        <td>{{ $application->updated_at->format('M j, Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align: center; color: #666; font-style: italic;">No applications found for this citizen.</p>
        @endif
    </div>

    <div class="footer">
        <p>This report was generated on {{ $generated_at }}</p>
        <p>Citizen ID Management System - Confidential Document</p>
    </div>
</body>
</html>
