<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Status Update</title>
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
            padding: 30px 20px;
            text-align: center;
            color: white;
        }
        .header.in_progress {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }
        .header.resolved {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        .header.closed {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        }
        .header.open {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .status-change-box {
            background: #f9fafb;
            border: 2px solid #e5e7eb;
            padding: 20px;
            margin: 20px;
            border-radius: 8px;
            text-align: center;
        }
        .status-arrow {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin: 15px 0;
            font-size: 16px;
        }
        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            display: inline-block;
        }
        .status-open {
            background: #dbeafe;
            color: #1e40af;
        }
        .status-in_progress {
            background: #fef3c7;
            color: #92400e;
        }
        .status-resolved {
            background: #d1fae5;
            color: #065f46;
        }
        .status-closed {
            background: #e5e7eb;
            color: #374151;
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
        .resolution-box {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .resolution-box h3 {
            margin: 0 0 10px 0;
            color: #065f46;
            font-size: 14px;
        }
        .resolution-text {
            color: #047857;
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
        .icon {
            font-size: 48px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header {{ $newStatus }}">
            <div class="icon">
                @if($newStatus === 'in_progress')
                    🔄
                @elseif($newStatus === 'resolved')
                    ✅
                @elseif($newStatus === 'closed')
                    🔒
                @else
                    📋
                @endif
            </div>
            <h1>Status Ticket Diupdate</h1>
            <p style="margin: 5px 0;">UIN Antasari Ticketing System</p>
        </div>

        <!-- Content -->
        <div class="content">
            <h2 style="color: #111827; margin-top: 0;">
                Ticket {{ $ticket->ticket_number }} Telah Diupdate
            </h2>

            <!-- Status Change -->
            <div class="status-change-box">
                <p style="margin: 0 0 10px 0; color: #6b7280; font-weight: 600;">Perubahan Status:</p>
                <div class="status-arrow">
                    <span class="status-badge status-{{ $oldStatus }}">
                        {{ strtoupper(str_replace('_', ' ', $oldStatus)) }}
                    </span>
                    <span style="font-size: 24px; color: #6b7280;">→</span>
                    <span class="status-badge status-{{ $newStatus }}">
                        {{ strtoupper(str_replace('_', ' ', $newStatus)) }}
                    </span>
                </div>
                
                @if($newStatus === 'resolved')
                    <p style="color: #059669; font-weight: 600; margin-top: 15px;">
                        🎉 Ticket telah diselesaikan!
                    </p>
                @elseif($newStatus === 'in_progress')
                    <p style="color: #d97706; font-weight: 600; margin-top: 15px;">
                        ⚡ Ticket sedang dalam proses penanganan
                    </p>
                @elseif($newStatus === 'closed')
                    <p style="color: #4b5563; font-weight: 600; margin-top: 15px;">
                        🔒 Ticket telah ditutup
                    </p>
                @endif
            </div>
            
            <!-- Ticket Info -->
            <div class="ticket-info">
                <div class="info-row">
                    <div class="info-label">Ticket Number:</div>
                    <div class="info-value"><strong>{{ $ticket->ticket_number }}</strong></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Kategori:</div>
                    <div class="info-value">{{ $ticket->category }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Dibuat oleh:</div>
                    <div class="info-value">{{ $ticket->admin->name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Access Point:</div>
                    <div class="info-value">{{ $ticket->accessPoint->name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Lokasi:</div>
                    <div class="info-value">
                        {{ $ticket->accessPoint->room->floor->building->name }} - 
                        {{ $ticket->accessPoint->room->floor->display_name }} - 
                        {{ $ticket->accessPoint->room->name }}
                    </div>
                </div>
                @if($ticket->resolved_at)
                <div class="info-row">
                    <div class="info-label">Diselesaikan:</div>
                    <div class="info-value">{{ $ticket->resolved_at->format('d M Y, H:i') }} WITA</div>
                </div>
                @endif
                @if($ticket->resolver)
                <div class="info-row">
                    <div class="info-label">Diselesaikan oleh:</div>
                    <div class="info-value">{{ $ticket->resolver->name }}</div>
                </div>
                @endif
            </div>

            @if($ticket->resolution_notes && $newStatus === 'resolved')
            <!-- Resolution Notes -->
            <div class="resolution-box">
                <h3>📝 Catatan Penyelesaian</h3>
                <div class="resolution-text">{{ $ticket->resolution_notes }}</div>
            </div>
            @endif

            <!-- Action Button -->
            <div style="text-align: center;">
                <a href="{{ route('tickets.show', $ticket->id) }}" class="button">
                    🔍 Lihat Detail Ticket
                </a>
            </div>

            @if($newStatus === 'resolved')
            <p style="color: #6b7280; font-size: 14px; margin-top: 30px; text-align: center;">
                Terima kasih telah melaporkan masalah. Kami senang dapat membantu menyelesaikan ticket Anda.
            </p>
            @elseif($newStatus === 'in_progress')
            <p style="color: #6b7280; font-size: 14px; margin-top: 30px; text-align: center;">
                Tim kami sedang menangani ticket Anda. Kami akan segera menginformasikan perkembangannya.
            </p>
            @endif
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