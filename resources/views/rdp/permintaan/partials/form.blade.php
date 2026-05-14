@if ($penempatan)
    <div class="border rounded p-3 mb-3 bg-light">
        <div><strong>Rumah:</strong> {{ collect([$penempatan->rdp_master_rumahs?->block, $penempatan->rdp_master_rumahs?->tipe, $penempatan->rdp_master_rumahs?->nomor])->filter()->implode(' ') ?: '-' }}</div>
        <div><strong>Cluster:</strong> {{ $penempatan->rdp_master_rumahs?->rdp_master_clusters?->nama_cluster ?: '-' }}</div>
        <div><strong>Karyawan:</strong> {{ $penempatan->data_employees?->name ?: '-' }}</div>
    </div>
@else
    <div class="alert alert-warning">Anda belum memiliki rumah dinas aktif, sehingga belum bisa mengajukan permintaan.</div>
@endif

@include('rdp.permintaan.partials.item_form')
