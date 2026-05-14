@php
    $employee = $item->data_employees;
    $rumah = $item->rdp_master_rumahs;
    $rumahLabel = function ($rumah) {
        if (!$rumah) return '-';
        return collect([$rumah['block'] ?? null, $rumah['tipe'] ?? null, $rumah['nomor'] ?? null])
            ->filter()
            ->implode(' ');
    };

    $fileUrl = function ($file) {
        return $file ? asset('storage/' . \App\Repositories\RdpKaryawanKeluarRepo::FILE_DIR . '/' . $file) : null;
    };
    $sikRoute = null;
    if ($item->status === \App\Repositories\RdpKaryawanKeluarRepo::FINISHED_STATUS) {
        if (\App\Helpers\RdpAccess::isAdmin()) {
            $sikRoute = route('rdp.keluar-rdp.izin-keluar.sik', $item->id);
        } elseif (\App\Helpers\RdpAccess::isPimpinan()) {
            $sikRoute = route('rdp.persetujuan.izin-keluar.sik', $item->id);
        } elseif (\App\Helpers\RdpAccess::isEmployee()) {
            $sikRoute = route('rdp.pengajuan.izin-keluar.sik', $item->id);
        }
    }
@endphp
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5>Data Karyawan</h5>
                <table class="table table-sm mb-0">
                    <tr><th width="160">Nama</th><td>{{ $employee?->name ?: '-' }}</td></tr>
                    <tr><th>NOPek</th><td>{{ $employee?->number ?: '-' }}</td></tr>
                    <tr><th>Perusahaan</th><td>{{ $employee?->master_organizations?->name ?: '-' }}</td></tr>
                    <tr><th>Jabatan</th><td>{{ $employee?->master_positions?->name ?: '-' }}</td></tr>
                    <tr><th>Status</th><td>{{ $employee?->status ?: '-' }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5>Data Rumah</h5>
                <table class="table table-sm mb-0">
                    <tr><th width="160">Rumah</th><td>{{ $rumahLabel($rumah?->toArray()) }}</td></tr>
                    <tr><th>Cluster</th><td>{{ $rumah?->rdp_master_clusters?->nama_cluster ?: '-' }}</td></tr>
                    <tr><th>Status Rumah</th><td>{{ $rumah?->status ?: '-' }}</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>

@if ($rumah?->rdp_master_rumah_asets && $rumah->rdp_master_rumah_asets->count() > 0)
    <div class="card">
        <div class="card-body">
            <h5>Data Aset Rumah</h5>
            <div class="table-responsive">
                <table class="table table-sm table-bordered mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" width="60">No</th>
                            <th>Aset</th>
                            <th>Jenis</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-center">Satuan</th>
                            <th class="text-center">Status</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rumah->rdp_master_rumah_asets as $aset)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $aset->rdp_master_asets?->perlengkapan ?: '-' }}</td>
                                <td>{{ $aset->jenis ?: '-' }}</td>
                                <td class="text-center">{{ $aset->jumlah ?: '-' }}</td>
                                <td class="text-center">{{ $aset->satuan ?: '-' }}</td>
                                <td class="text-center">{{ $aset->status ?: '-' }}</td>
                                <td>{{ $aset->catatan ?: '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <h5>Data SK Keluar</h5>
        <table class="table table-sm mb-0">
            <tr><th width="220">Nomor SK Keluar</th><td>{{ $item->nomor_sk_keluar ?: '-' }}</td></tr>
            <tr><th>Tanggal SK Keluar</th><td>{{ $item->tanggal_sk_keluar ?: '-' }}</td></tr>
            <tr><th>Tanggal Keluar</th><td>{{ $item->tanggal_keluar ?: '-' }}</td></tr>
            <tr><th>File</th><td>
                @if ($fileUrl($item->file_sk_keluar))
                    <a href="{{ $fileUrl($item->file_sk_keluar) }}" target="_blank">Lihat file</a>
                @else
                    -
                @endif
            </td></tr>
            <tr><th>Catatan Revisi Berkas</th><td>{{ $item->catatan_revisi_berkas ?: '-' }}</td></tr>
            <tr><th>File SIK</th><td>
                @if ($sikRoute)
                    <a href="{{ $sikRoute }}" target="_blank" class="btn btn-sm btn-primary">
                        Lihat File SIK
                    </a>
                @else
                    -
                @endif
            </td></tr>
            <tr><th>Status</th><td>{{ $item->status }}</td></tr>
            <tr><th>Dibuat</th><td>{{ $item->created_at }}</td></tr>
            <tr><th>Diubah</th><td>{{ $item->updated_at }}</td></tr>
        </table>
    </div>
</div>
