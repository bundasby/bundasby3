<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Mitra PAUD</title>
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
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
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
            border-left: 4px solid #8b5cf6;
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
            width: 150px;
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

        .status-approved {
            background: #d1fae5;
            color: #065f46;
        }

        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .footer {
            background: #f8fafc;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }

        .highlight {
            background: #faf5ff;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }

        .highlight.success {
            background: #ecfdf5;
        }

        .highlight.error {
            background: #fef2f2;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🤝 Mitra PAUD Kota Surabaya</h1>
            <p>Sistem Pendaftaran Kemitraan</p>
        </div>

        <div class="content">
            @if($type === 'new')
                <h2>📋 Pendaftaran Mitra Baru</h2>
                <p>Ada pendaftaran mitra baru yang masuk:</p>
            @elseif($type === 'approved')
                <div class="highlight success">
                    <h2>✅ Selamat! Pendaftaran Disetujui</h2>
                    <p>Pendaftaran organisasi Anda sebagai Mitra PAUD telah disetujui.</p>
                </div>
            @else
                <div class="highlight error">
                    <h2>❌ Pendaftaran Ditolak</h2>
                    <p>Mohon maaf, pendaftaran organisasi Anda belum dapat disetujui saat ini.</p>
                </div>
            @endif

            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">Nama Organisasi:</span>
                    <span class="info-value">{{ $mitra->nama_organisasi }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Kategori:</span>
                    <span class="info-value">{{ $mitra->kategori_organisasi }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Nama Pimpinan:</span>
                    <span class="info-value">{{ $mitra->nama_pimpinan }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $mitra->email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Telepon:</span>
                    <span class="info-value">{{ $mitra->telepon }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Alamat:</span>
                    <span class="info-value">{{ $mitra->alamat }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Jenis Kerjasama:</span>
                    <span class="info-value">
                        @if(is_array($mitra->jenis_kerjasama))
                            {{ implode(', ', $mitra->jenis_kerjasama) }}
                        @else
                            {{ $mitra->jenis_kerjasama }}
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="info-value">
                        @php
                            $statusClass = match ($mitra->status) {
                                'pending' => 'status-pending',
                                'approved' => 'status-approved',
                                'rejected' => 'status-rejected',
                                default => 'status-pending'
                            };
                        @endphp
                        <span class="status {{ $statusClass }}">{{ ucfirst($mitra->status) }}</span>
                    </span>
                </div>
            </div>

            @if($mitra->tujuan_organisasi)
                <div style="background: #f1f5f9; padding: 15px; border-radius: 8px; margin: 20px 0;">
                    <strong>Tujuan Organisasi:</strong>
                    <p>{{ $mitra->tujuan_organisasi }}</p>
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