<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Pengaduan</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f5f5f5;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #fff;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .header p {
            margin: 10px 0 0;
            opacity: 0.9;
        }

        .content {
            padding: 30px;
        }

        .info-box {
            background: #f8fafc;
            border-left: 4px solid #2563eb;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }

        .info-row {
            display: flex;
            margin: 8px 0;
        }

        .info-label {
            font-weight: 600;
            width: 120px;
            color: #64748b;
        }

        .info-value {
            flex: 1;
        }

        .status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-proses {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-selesai {
            background: #d1fae5;
            color: #065f46;
        }

        .message-box {
            background: #f1f5f9;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .footer {
            background: #f8fafc;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }

        .btn {
            display: inline-block;
            background: #2563eb;
            color: #fff;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🏫 Bunda PAUD Kota Surabaya</h1>
            <p>Sistem Informasi Pengaduan</p>
        </div>

        <div class="content">
            @if($type === 'new')
                <h2>📩 Pengaduan Baru Diterima</h2>
                <p>Ada pengaduan baru yang masuk ke sistem:</p>
            @else
                <h2>🔔 Update Status Pengaduan</h2>
                <p>Status pengaduan Anda telah diperbarui:</p>
            @endif

            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">Nama:</span>
                    <span class="info-value">{{ $complaint->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $complaint->email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Telepon:</span>
                    <span class="info-value">{{ $complaint->phone ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Subjek:</span>
                    <span class="info-value">{{ $complaint->subject ?? 'Tanpa Subjek' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="info-value">
                        @php
                            $statusClass = match ($complaint->status) {
                                'pending' => 'status-pending',
                                'proses' => 'status-proses',
                                'selesai' => 'status-selesai',
                                default => 'status-pending'
                            };
                        @endphp
                        <span class="status {{ $statusClass }}">{{ ucfirst($complaint->status) }}</span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal:</span>
                    <span class="info-value">{{ $complaint->created_at->format('d M Y, H:i') }}</span>
                </div>
            </div>

            <div class="message-box">
                <strong>Isi Pengaduan:</strong>
                <p>{{ $complaint->message }}</p>
            </div>

            @if($complaint->response && $type === 'status_update')
                <div class="message-box" style="background: #ecfdf5; border-left: 4px solid #10b981;">
                    <strong>Tanggapan Admin:</strong>
                    <p>{{ $complaint->response }}</p>
                </div>
            @endif
        </div>

        <div class="footer">
            <p>Email ini dikirim otomatis oleh sistem Bunda PAUD Kota Surabaya.</p>
            <p>© {{ date('Y') }} Bunda PAUD Kota Surabaya. All rights reserved.</p>
        </div>
    </div>
</body>

</html>