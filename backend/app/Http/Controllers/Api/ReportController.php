<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Complaint;
use App\Models\BundaPaud;
use App\Models\MitraPaud;
use App\Models\Gallery;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    /**
     * Generate PDF report for articles
     */
    public function articlesReport(Request $request): Response
    {
        $request->validate([
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
            'category' => 'nullable|string'
        ]);

        $query = Article::with('author:id,name')
            ->orderBy('published_at', 'desc');

        if ($request->from_date) {
            $query->where('published_at', '>=', $request->from_date);
        }
        if ($request->to_date) {
            $query->where('published_at', '<=', $request->to_date);
        }
        if ($request->category) {
            $query->where('category', $request->category);
        }

        $articles = $query->get();

        $html = $this->generateArticlesHtml($articles, $request->all());
        $pdf = $this->generatePdf($html);

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="laporan-artikel-' . date('Y-m-d') . '.pdf"'
        ]);
    }

    /**
     * Generate PDF report for complaints
     */
    public function complaintsReport(Request $request): Response
    {
        $request->validate([
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
            'status' => 'nullable|string|in:pending,processed,resolved'
        ]);

        $query = Complaint::orderBy('created_at', 'desc');

        if ($request->from_date) {
            $query->where('created_at', '>=', $request->from_date);
        }
        if ($request->to_date) {
            $query->where('created_at', '<=', $request->to_date);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $complaints = $query->get();

        $html = $this->generateComplaintsHtml($complaints, $request->all());
        $pdf = $this->generatePdf($html);

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="laporan-pengaduan-' . date('Y-m-d') . '.pdf"'
        ]);
    }

    /**
     * Generate PDF report for Bunda PAUD
     */
    public function bundaPaudReport(Request $request): Response
    {
        $request->validate([
            'kecamatan' => 'nullable|string',
            'type' => 'nullable|string|in:kecamatan,kelurahan'
        ]);

        $query = BundaPaud::orderBy('kecamatan');

        if ($request->kecamatan) {
            $query->where('kecamatan', $request->kecamatan);
        }
        if ($request->type) {
            $query->where('type', $request->type);
        }

        $bundaPaud = $query->get();

        $html = $this->generateBundaPaudHtml($bundaPaud, $request->all());
        $pdf = $this->generatePdf($html);

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="laporan-bunda-paud-' . date('Y-m-d') . '.pdf"'
        ]);
    }

    /**
     * Generate monthly summary report
     */
    public function monthlySummary(Request $request): Response
    {
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));

        $startDate = "{$year}-{$month}-01";
        $endDate = date('Y-m-t', strtotime($startDate));

        $data = [
            'period' => [
                'month' => $month,
                'year' => $year,
                'start' => $startDate,
                'end' => $endDate
            ],
            'articles' => [
                'total' => Article::whereBetween('created_at', [$startDate, $endDate])->count(),
                'published' => Article::whereBetween('published_at', [$startDate, $endDate])->count(),
                'by_category' => Article::whereBetween('created_at', [$startDate, $endDate])
                    ->selectRaw('category, count(*) as count')
                    ->groupBy('category')
                    ->get()
            ],
            'complaints' => [
                'total' => Complaint::whereBetween('created_at', [$startDate, $endDate])->count(),
                'pending' => Complaint::whereBetween('created_at', [$startDate, $endDate])
                    ->where('status', 'pending')->count(),
                'processed' => Complaint::whereBetween('created_at', [$startDate, $endDate])
                    ->where('status', 'processed')->count(),
                'resolved' => Complaint::whereBetween('created_at', [$startDate, $endDate])
                    ->where('status', 'resolved')->count()
            ],
            'galleries' => Gallery::whereBetween('created_at', [$startDate, $endDate])->count(),
            'documents' => Document::whereBetween('created_at', [$startDate, $endDate])->count(),
            'mitra_paud' => MitraPaud::whereBetween('created_at', [$startDate, $endDate])->count()
        ];

        $html = $this->generateMonthlySummaryHtml($data);
        $pdf = $this->generatePdf($html);

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="laporan-bulanan-' . $year . '-' . $month . '.pdf"'
        ]);
    }

    /**
     * Generate PDF from HTML
     */
    private function generatePdf(string $html): string
    {
        // Using browser-based PDF generation or DomPDF
        // In production, use a library like barryvdh/laravel-dompdf

        // For now, return HTML wrapped for print
        return $html;
    }

    /**
     * Generate articles report HTML
     */
    private function generateArticlesHtml($articles, $filters): string
    {
        $filterInfo = [];
        if (!empty($filters['from_date']))
            $filterInfo[] = "Dari: {$filters['from_date']}";
        if (!empty($filters['to_date']))
            $filterInfo[] = "Sampai: {$filters['to_date']}";
        if (!empty($filters['category']))
            $filterInfo[] = "Kategori: {$filters['category']}";
        $filterText = implode(' | ', $filterInfo) ?: 'Semua data';

        $rows = '';
        foreach ($articles as $i => $article) {
            $rows .= "
            <tr>
                <td>" . ($i + 1) . "</td>
                <td>{$article->title}</td>
                <td>{$article->category}</td>
                <td>{$article->author->name}</td>
                <td>" . ($article->published_at ? $article->published_at->format('d/m/Y') : '-') . "</td>
                <td>" . ($article->is_published ? 'Ya' : 'Tidak') . "</td>
            </tr>";
        }

        return $this->getReportTemplate(
            'Laporan Artikel',
            $filterText,
            '<th>No</th><th>Judul</th><th>Kategori</th><th>Penulis</th><th>Tanggal Publish</th><th>Published</th>',
            $rows,
            count($articles)
        );
    }

    /**
     * Generate complaints report HTML
     */
    private function generateComplaintsHtml($complaints, $filters): string
    {
        $filterInfo = [];
        if (!empty($filters['from_date']))
            $filterInfo[] = "Dari: {$filters['from_date']}";
        if (!empty($filters['to_date']))
            $filterInfo[] = "Sampai: {$filters['to_date']}";
        if (!empty($filters['status']))
            $filterInfo[] = "Status: {$filters['status']}";
        $filterText = implode(' | ', $filterInfo) ?: 'Semua data';

        $rows = '';
        foreach ($complaints as $i => $complaint) {
            $statusBadge = match ($complaint->status) {
                'pending' => '🟡 Pending',
                'processed' => '🔵 Diproses',
                'resolved' => '🟢 Selesai',
                default => $complaint->status
            };

            $rows .= "
            <tr>
                <td>" . ($i + 1) . "</td>
                <td>{$complaint->name}</td>
                <td>{$complaint->subject}</td>
                <td>{$statusBadge}</td>
                <td>" . $complaint->created_at->format('d/m/Y H:i') . "</td>
            </tr>";
        }

        return $this->getReportTemplate(
            'Laporan Pengaduan',
            $filterText,
            '<th>No</th><th>Nama</th><th>Subjek</th><th>Status</th><th>Tanggal</th>',
            $rows,
            count($complaints)
        );
    }

    /**
     * Generate Bunda PAUD report HTML
     */
    private function generateBundaPaudHtml($bundaPaud, $filters): string
    {
        $filterInfo = [];
        if (!empty($filters['kecamatan']))
            $filterInfo[] = "Kecamatan: {$filters['kecamatan']}";
        if (!empty($filters['type']))
            $filterInfo[] = "Tipe: {$filters['type']}";
        $filterText = implode(' | ', $filterInfo) ?: 'Semua data';

        $rows = '';
        foreach ($bundaPaud as $i => $item) {
            $rows .= "
            <tr>
                <td>" . ($i + 1) . "</td>
                <td>{$item->name}</td>
                <td>{$item->position}</td>
                <td>{$item->kecamatan}</td>
                <td>{$item->kelurahan}</td>
                <td>{$item->phone}</td>
            </tr>";
        }

        return $this->getReportTemplate(
            'Laporan Data Bunda PAUD',
            $filterText,
            '<th>No</th><th>Nama</th><th>Jabatan</th><th>Kecamatan</th><th>Kelurahan</th><th>Telepon</th>',
            $rows,
            count($bundaPaud)
        );
    }

    /**
     * Generate monthly summary HTML
     */
    private function generateMonthlySummaryHtml($data): string
    {
        $monthName = date('F Y', strtotime($data['period']['start']));

        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Laporan Bulanan - {$monthName}</title>
            <style>
                {$this->getStyles()}
                .summary-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin: 30px 0; }
                .summary-card { background: #f8fafc; border-radius: 12px; padding: 24px; text-align: center; }
                .summary-value { font-size: 36px; font-weight: 700; color: #0ea5e9; }
                .summary-label { font-size: 14px; color: #64748b; margin-top: 8px; }
                .chart-section { margin: 30px 0; }
                .bar { height: 24px; background: #e2e8f0; border-radius: 4px; margin: 8px 0; }
                .bar-fill { height: 100%; background: #0ea5e9; border-radius: 4px; }
            </style>
        </head>
        <body>
            <div class='header'>
                <h1>Laporan Bulanan</h1>
                <div class='subtitle'>{$monthName}</div>
                <div class='meta'>Periode: {$data['period']['start']} s/d {$data['period']['end']}</div>
            </div>

            <div class='summary-grid'>
                <div class='summary-card'>
                    <div class='summary-value'>{$data['articles']['total']}</div>
                    <div class='summary-label'>Artikel Baru</div>
                </div>
                <div class='summary-card'>
                    <div class='summary-value'>{$data['complaints']['total']}</div>
                    <div class='summary-label'>Pengaduan Masuk</div>
                </div>
                <div class='summary-card'>
                    <div class='summary-value'>{$data['galleries']}</div>
                    <div class='summary-label'>Galeri Baru</div>
                </div>
                <div class='summary-card'>
                    <div class='summary-value'>{$data['documents']}</div>
                    <div class='summary-label'>Dokumen Baru</div>
                </div>
                <div class='summary-card'>
                    <div class='summary-value'>{$data['mitra_paud']}</div>
                    <div class='summary-label'>Mitra PAUD Baru</div>
                </div>
                <div class='summary-card'>
                    <div class='summary-value'>{$data['complaints']['resolved']}</div>
                    <div class='summary-label'>Pengaduan Selesai</div>
                </div>
            </div>

            <div class='chart-section'>
                <h2>Status Pengaduan</h2>
                <p>Pending: {$data['complaints']['pending']}</p>
                <div class='bar'><div class='bar-fill' style='width: " . ($data['complaints']['total'] > 0 ? ($data['complaints']['pending'] / $data['complaints']['total'] * 100) : 0) . "%'></div></div>
                
                <p>Diproses: {$data['complaints']['processed']}</p>
                <div class='bar'><div class='bar-fill' style='width: " . ($data['complaints']['total'] > 0 ? ($data['complaints']['processed'] / $data['complaints']['total'] * 100) : 0) . "%; background: #f59e0b;'></div></div>
                
                <p>Selesai: {$data['complaints']['resolved']}</p>
                <div class='bar'><div class='bar-fill' style='width: " . ($data['complaints']['total'] > 0 ? ($data['complaints']['resolved'] / $data['complaints']['total'] * 100) : 0) . "%; background: #22c55e;'></div></div>
            </div>

            <div class='footer'>
                <p>Dicetak pada: " . date('d/m/Y H:i') . "</p>
                <p>© " . date('Y') . " Bunda PAUD Kota Surabaya</p>
            </div>
        </body>
        </html>";
    }

    /**
     * Get report template
     */
    private function getReportTemplate($title, $filterText, $headers, $rows, $total): string
    {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>{$title}</title>
            <style>{$this->getStyles()}</style>
        </head>
        <body>
            <div class='header'>
                <h1>{$title}</h1>
                <div class='subtitle'>Bunda PAUD Kota Surabaya</div>
                <div class='meta'>Filter: {$filterText}</div>
            </div>

            <table>
                <thead><tr>{$headers}</tr></thead>
                <tbody>{$rows}</tbody>
            </table>

            <div class='summary'>
                <strong>Total Data: {$total}</strong>
            </div>

            <div class='footer'>
                <p>Dicetak pada: " . date('d/m/Y H:i') . "</p>
                <p>© " . date('Y') . " Bunda PAUD Kota Surabaya</p>
            </div>
        </body>
        </html>";
    }

    /**
     * Get CSS styles for reports
     */
    private function getStyles(): string
    {
        return "
            body { font-family: 'Segoe UI', Arial, sans-serif; padding: 40px; color: #1e293b; }
            .header { text-align: center; margin-bottom: 40px; border-bottom: 2px solid #0ea5e9; padding-bottom: 20px; }
            .header h1 { font-size: 24px; margin: 0; color: #0ea5e9; }
            .subtitle { font-size: 16px; color: #64748b; margin-top: 8px; }
            .meta { font-size: 12px; color: #94a3b8; margin-top: 8px; }
            table { width: 100%; border-collapse: collapse; margin: 20px 0; }
            th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e2e8f0; }
            th { background: #f8fafc; font-weight: 600; color: #475569; }
            tr:hover { background: #f8fafc; }
            .summary { text-align: right; margin-top: 20px; padding: 16px; background: #f8fafc; border-radius: 8px; }
            .footer { text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #e2e8f0; font-size: 12px; color: #94a3b8; }
            @media print { body { padding: 20px; } }
        ";
    }
}
