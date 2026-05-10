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

    public function penempatanIndex(Request $request)
    {
        $filters = $this->penempatanFilters($request);
        $data = $this->pageData(
            'Laporan Penempatan RDP | RDP',
            'Laporan Penempatan RDP',
            'Detail karyawan dan unit RDP yang ditempati.'
        );

        return view('rdp.report.penempatan', [
            'data' => $data,
            'filters' => $filters,
            'clusters' => RdpReportRepo::getClusters(),
            'statusRumahList' => RdpReportRepo::STATUS_RUMAH_LIST,
            'items' => RdpReportRepo::getPenempatan($filters),
        ]);
    }

    public function penempatanPdf(Request $request)
    {
        $filters = $this->penempatanFilters($request, true);
        $cluster = !empty($filters['cluster_id'])
            ? RdpReportRepo::getClusters()->firstWhere('id', (int) $filters['cluster_id'])
            : null;

        $pdf = Pdf::loadView('rdp.report.pdf.penempatan', [
            'filters' => $filters,
            'cluster' => $cluster,
            'items' => RdpReportRepo::getPenempatan($filters),
            'printedAt' => now(),
        ])->setPaper('A4', 'landscape');

        return $pdf->stream('laporan-penempatan-rdp.pdf');
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
