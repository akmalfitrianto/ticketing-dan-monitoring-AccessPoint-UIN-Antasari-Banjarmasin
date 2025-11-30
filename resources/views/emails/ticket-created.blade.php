<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Notification</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #14b8a6 0%, #10b981 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin-top: 10px;
            background: #3b82f6;
            color: white;
        }
        .content {
            padding: 30px 20px;
        }
        .ticket-info {
            background: #f9fafb;
            border-left: 4px solid #14b8a6;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-row {
            display: flex;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #6b7280;
            width: 140px;
            flex-shrink: 0;
        }
        .info-value {
            color: #111827;
            flex: 1;
        }
        .location-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .location-box h3 {
            margin: 0 0 10px 0;
            color: #1e40af;
            font-size: 14px;
        }
        .location-item {
            padding: 5px 0;
            color: #1e3a8a;
        }
        .description-box {
            background: #fef3c7;
            border: 1px solid #fde68a;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .description-box h3 {
            margin: 0 0 10px 0;
            color: #92400e;
            font-size: 14px;
        }
        .description-text {
            color: #78350f;
            white-space: pre-wrap;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #14b8a6;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
        }
        .footer {
            background: #f9fafb;
            padding: 20px;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
            border-top: 1px solid #e5e7eb;
        }
        .alert-box {
            background: #fef2f2;
            border: 2px solid #ef4444;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            text-align: center;
        }
        .alert-box p {
            margin: 5px 0;
            color: #991b1b;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Ticket Notification</h1>
            <p style="margin: 5px 0;">UIN Antasari Ticketing System</p>
            <span class="status-badge">
                NEW TICKET
            </span>
        </div>

        <!-- Content -->
        <div class="content">
            <h2 style="color: #111827; margin-top: 0;">Ticket Baru Telah Dibuat</h2>
            
            <div class="ticket-info">
                <div class="info-row">
                    <div class="info-label">Ticket Number:</div>
                    <div class="info-value"><strong>{{ $ticket->ticket_number }}</strong></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Kategori:</div>
                    <div class="info-value"><strong>{{ $ticket->category }}</strong></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Status:</div>
                    <div class="info-value">
                        <span style="color: #3b82f6; font-weight: 600;">{{ strtoupper($ticket->status_label) }}</span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Dilaporkan oleh:</div>
                    <div class="info-value">{{ $ticket->admin->name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Unit Kerja:</div>
                    <div class="info-value">{{ $ticket->admin->unit_kerja ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Waktu:</div>
                    <div class="info-value">{{ $ticket->created_at->format('d M Y, H:i') }} WITA</div>
                </div>
            </div>

            <!-- Location -->
            <div class="location-box">
                <h3>📍 Lokasi Access Point</h3>
                <div class="location-item">
                    <strong>Gedung:</strong> {{ $ticket->accessPoint->room->floor->building->name }}
                </div>
                <div class="location-item">
                    <strong>Lantai:</strong> {{ $ticket->accessPoint->room->floor->display_name }}
                </div>
                <div class="location-item">
                    <strong>Ruangan:</strong> {{ $ticket->accessPoint->room->name }}
                </div>
                <div class="location-item">
                    <strong>Access Point:</strong> {{ $ticket->accessPoint->name }}
                </div>
                <div class="location-item">
                    <strong>Status AP:</strong> 
                    <span style="color: {{ $ticket->accessPoint->status_color }}; font-weight: 600;">
                        {{ strtoupper($ticket->accessPoint->status_label) }}
                    </span>
                </div>
            </div>

            <!-- Description -->
            <div class="description-box">
                <h3>💬 Deskripsi Masalah</h3>
                <div class="description-text">{{ $ticket->description }}</div>
            </div>

            <!-- Action Button -->
            <div style="text-align: center;">
                <a href="{{ route('tickets.show', $ticket->id) }}" class="button">
                    🔍 Lihat Detail Ticket
                </a>
            </div>

            <p style="color: #6b7280; font-size: 14px; margin-top: 30px;">
                Silakan segera tindak lanjuti ticket ini untuk memastikan layanan tetap berjalan optimal.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>UIN Antasari Ticketing System</strong></p>
            <p>Sistem Manajemen Ticket Access Point</p>
            <p style="margin-top: 10px;">
                Email ini dikirim otomatis oleh sistem. Jangan membalas email ini.
            </p>
        </div>
    </div>
</body>
</html>