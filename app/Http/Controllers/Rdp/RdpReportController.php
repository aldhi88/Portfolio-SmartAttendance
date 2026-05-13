<?php

namespace App\Http\Controllers\Rdp;

use App\Http\Controllers\Controller;
use App\Repositories\RdpReportRepo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RdpReportController extends Controller
{
    public function asetStandarIndex(Request $request)
    {
        $filters = $this->asetStandarFilters($request);
        $data = $this->pageData(
            'Laporan Aset Standar RDP | RDP',
            'Laporan Aset Standar RDP',
            'Listing standar aset RDP berdasarkan cluster.'
        );

        return view('rdp.report.aset_standar', [
            'data' => $data,
            'filters' => $filters,
            'clusters' => RdpReportRepo::getClusters(),
            'items' => RdpReportRepo::getAsetStandar($filters),
        ]);
    }

    public function asetStandarPdf(Request $request)
    {
        $filters = $this->asetStandarFilters($request, true);
        $cluster = !empty($filters['cluster_id'])
            ? RdpReportRepo::getClusters()->firstWhere('id', (int) $filters['cluster_id'])
            : null;

        $pdf = Pdf::loadView('rdp.report.pdf.aset_standar', [
            'filters' => $filters,
            'cluster' => $cluster,
            'items' => RdpReportRepo::getAsetStandar($filters),
            'printedAt' => now(),
        ])->setPaper('A4', 'landscape');

        return $pdf->stream('laporan-aset-standar-rdp.pdf');
    }

    public function asetRealisasiIndex(Request $request)
    {
        $filters = $this->asetRealisasiFilters($request);
        $rumah = !empty($filters['rumah_id']) ? RdpReportRepo::getRumahById($filters['rumah_id']) : null;
        $data = $this->pageData(
            'Laporan Aset RDP Realisasi | RDP',
            'Laporan Aset RDP Realisasi',
            'Listing aset aktual berdasarkan unit rumah.'
        );

        return view('rdp.report.aset_realisasi', [
            'data' => $data,
            'filters' => $filters,
            'rumahs' => RdpReportRepo::getRumahs(),
            'rumah' => $rumah,
            'items' => $rumah ? RdpReportRepo::getAsetRealisasi($rumah->id) : collect(),
        ]);
    }

    public function asetRealisasiPdf(Request $request)
    {
        $filters = $this->asetRealisasiFilters($request, true);
        abort_if(empty($filters['rumah_id']), 404);

        $rumah = RdpReportRepo::getRumahById($filters['rumah_id']);
        abort_if(!$rumah, 404);

        $pdf = Pdf::loadView('rdp.report.pdf.aset_realisasi', [
            'rumah' => $rumah,
            'items' => RdpReportRepo::getAsetRealisasi($rumah->id),
            'printedAt' => now(),
        ])->setPaper('A4', 'landscape');

        return $pdf->stream('laporan-aset-realisasi-rdp-' . $rumah->id . '.pdf');
    }

    public function asetRealisasiPdfSemua()
    {
        $pdf = Pdf::loadView('rdp.report.pdf.aset_realisasi_semua', [
            'rumahs' => RdpReportRepo::getAsetRealisasiSemuaRumah(),
            'printedAt' => now(),
        ])->setPaper('A4', 'landscape');

        return $pdf->stream('laporan-aset-realisasi-rdp-semua-unit.pdf');
    }

    public function penempatanIndex(Request $request, $variant)
    {
        $filters = $this->moduleReportFilters($request, 'penempatan', false, $variant);
        $data = $this->pageData(
            'Laporan Penempatan SIP | RDP',
            RdpReportRepo::moduleTitle('penempatan'),
            RdpReportRepo::moduleDescription('penempatan')
        );

        return $this->moduleReportView('penempatan', $data, $filters);
    }

    public function penempatanPdf(Request $request, $variant)
    {
        $filters = $this->moduleReportFilters($request, 'penempatan', true, $variant);

        return $this->moduleReportPdf('penempatan', $filters, 'laporan-penempatan-sip.pdf');
    }

    public function perbaikanIndex(Request $request, $variant)
    {
        $filters = $this->moduleReportFilters($request, 'perbaikan', false, $variant);
        $data = $this->pageData(
            'Laporan Perbaikan | RDP',
            RdpReportRepo::moduleTitle('perbaikan'),
            RdpReportRepo::moduleDescription('perbaikan')
        );

        return $this->moduleReportView('perbaikan', $data, $filters);
    }

    public function perbaikanPdf(Request $request, $variant)
    {
        $filters = $this->moduleReportFilters($request, 'perbaikan', true, $variant);

        return $this->moduleReportPdf('perbaikan', $filters, 'laporan-perbaikan-rdp.pdf');
    }

    public function pengadaanIndex(Request $request, $variant)
    {
        $filters = $this->moduleReportFilters($request, 'pengadaan', false, $variant);
        $data = $this->pageData(
            'Laporan Pengadaan | RDP',
            RdpReportRepo::moduleTitle('pengadaan'),
            RdpReportRepo::moduleDescription('pengadaan')
        );

        return $this->moduleReportView('pengadaan', $data, $filters);
    }

    public function pengadaanPdf(Request $request, $variant)
    {
        $filters = $this->moduleReportFilters($request, 'pengadaan', true, $variant);

        return $this->moduleReportPdf('pengadaan', $filters, 'laporan-pengadaan-rdp.pdf');
    }

    public function asetIndex(Request $request, $variant)
    {
        $filters = $this->moduleReportFilters($request, 'aset', false, $variant);
        $data = $this->pageData(
            'Laporan Aset | RDP',
            RdpReportRepo::moduleTitle('aset'),
            RdpReportRepo::moduleDescription('aset')
        );

        return $this->moduleReportView('aset', $data, $filters);
    }

    public function asetPdf(Request $request, $variant)
    {
        $filters = $this->moduleReportFilters($request, 'aset', true, $variant);

        return $this->moduleReportPdf('aset', $filters, 'laporan-aset-rdp.pdf');
    }

    protected function pageData($tabTitle, $pageTitle, $pageDesc)
    {
        return [
            'tab_title' => $tabTitle,
            'page_title' => $pageTitle,
            'page_desc' => $pageDesc,
        ];
    }

    protected function asetStandarFilters(Request $request, $abortOnInvalid = false)
    {
        return $this->validateFilters($request, [
            'cluster_id' => ['nullable', 'integer', 'exists:rdp_master_clusters,id'],
        ], $abortOnInvalid);
    }

    protected function asetRealisasiFilters(Request $request, $abortOnInvalid = false)
    {
        return $this->validateFilters($request, [
            'rumah_id' => ['nullable', 'integer', 'exists:rdp_master_rumahs,id'],
        ], $abortOnInvalid);
    }

    protected function penempatanFilters(Request $request, $abortOnInvalid = false)
    {
        return $this->validateFilters($request, [
            'cluster_id' => ['nullable', 'integer', 'exists:rdp_master_clusters,id'],
            'status_rumah' => ['nullable', Rule::in(RdpReportRepo::STATUS_RUMAH_LIST)],
        ], $abortOnInvalid);
    }

    protected function moduleReportView($module, array $data, array $filters)
    {
        $report = RdpReportRepo::buildModuleReport($module, $filters);
        $data['tab_title'] = $report['variant_label'] . ' | RDP';
        $data['page_title'] = $report['variant_label'];
        $data['page_desc'] = $report['description'];

        return view('rdp.report.module', [
            'data' => $data,
            'module' => $module,
            'filters' => $filters,
            'variants' => RdpReportRepo::variants($module),
            'statuses' => RdpReportRepo::statuses($module),
            'clusters' => RdpReportRepo::getClusters(),
            'rumahs' => RdpReportRepo::getRumahs(),
            'vendors' => RdpReportRepo::getVendors(),
            'asets' => RdpReportRepo::getAsets(),
            'statusRumahList' => RdpReportRepo::STATUS_RUMAH_LIST,
            'statusAsetList' => RdpReportRepo::ASET_STATUS_LIST,
            'report' => $report,
        ]);
    }

    protected function moduleReportPdf($module, array $filters, $filename)
    {
        $pdf = Pdf::loadView('rdp.report.pdf.module', [
            'module' => $module,
            'filters' => $filters,
            'report' => RdpReportRepo::buildModuleReport($module, $filters),
            'printedAt' => now(),
        ])->setPaper('A4', 'landscape');

        return $pdf->stream($filename);
    }

    protected function moduleReportFilters(Request $request, $module, $abortOnInvalid = false, $variant = null)
    {
        $rules = [
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'cluster_id' => ['nullable', 'integer', 'exists:rdp_master_clusters,id'],
        ];

        if (!empty(RdpReportRepo::statuses($module))) {
            $rules['status'] = ['nullable', Rule::in(RdpReportRepo::statuses($module))];
        }

        if ($module === 'penempatan') {
            $rules['status_rumah'] = ['nullable', Rule::in(RdpReportRepo::STATUS_RUMAH_LIST)];
        }

        if (in_array($module, ['perbaikan', 'pengadaan'])) {
            $rules['vendor_id'] = ['nullable', 'integer', 'exists:rdp_master_vendors,id'];
        }

        if ($module === 'aset') {
            $rules['status_rumah'] = ['nullable', Rule::in(RdpReportRepo::STATUS_RUMAH_LIST)];
            $rules['rumah_id'] = ['nullable', 'integer', 'exists:rdp_master_rumahs,id'];
            $rules['aset_id'] = ['nullable', 'integer', 'exists:rdp_master_asets,id'];
            $rules['status_aset'] = ['nullable', Rule::in(RdpReportRepo::ASET_STATUS_LIST)];
        }

        $filters = array_merge(
            collect(array_keys($rules))->mapWithKeys(fn ($key) => [$key => null])->all(),
            $this->validateFilters($request, $rules, $abortOnInvalid)
        );
        $filters['date_from'] = $filters['date_from'] ?: now()->startOfMonth()->toDateString();
        $filters['date_to'] = $filters['date_to'] ?: now()->endOfMonth()->toDateString();
        $filters['status'] = $filters['status'] ?? null;
        $filters['variant'] = $this->normalizeVariant($module, $variant, $abortOnInvalid);

        return $filters;
    }

    protected function normalizeVariant($module, $variant, $abortOnInvalid = false)
    {
        $variants = RdpReportRepo::variants($module);
        $variant = $variant ?: array_key_first($variants);

        if (!array_key_exists($variant, $variants)) {
            abort_if($abortOnInvalid, 404);
            abort(404);
        }

        return $variant;
    }

    protected function validateFilters(Request $request, $rules, $abortOnInvalid = false)
    {
        $validator = validator($request->only(array_keys($rules)), $rules);

        if ($validator->fails()) {
            abort_if($abortOnInvalid, 404);
            return collect(array_keys($rules))->mapWithKeys(fn ($key) => [$key => null])->all();
        }

        return collect($validator->validated())
            ->map(fn ($value) => $value === '' ? null : $value)
            ->all();
    }
}
