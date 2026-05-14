@php
    $employee = $item->rdp_karyawan_masuks?->data_employees;
    $rumah = $item->rdp_karyawan_masuks?->rdp_master_rumahs;
@endphp

<div class="row">
    <div class="col-md-6">
        <div class="border rounded p-3 mb-3">
            <h6>Karyawan</h6>
            <div><strong>Nama:</strong> {{ $employee?->name ?: '-' }}</div>
            <div><strong>NOPek:</strong> {{ $employee?->number ?: '-' }}</div>
            <div><strong>Jabatan:</strong> {{ $employee?->master_positions?->name ?: '-' }}</div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="border rounded p-3 mb-3">
            <h6>Unit Rumah</h6>
            <div><strong>Cluster:</strong> {{ $rumah?->rdp_master_clusters?->nama_cluster ?: '-' }}</div>
            <div><strong>Block:</strong> {{ $rumah?->block ?: '-' }}</div>
            <div><strong>Tipe:</strong> {{ $rumah?->tipe ?: '-' }}</div>
            <div><strong>Nomor:</strong> {{ $rumah?->nomor ?: '-' }}</div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="border rounded p-3 mb-3">
            <h6>Proses</h6>
            <div><strong>Tanggal Permintaan:</strong> {{ $item->created_at?->format('d/m/Y H:i') ?: '-' }}</div>
            <div><strong>Status:</strong> <span class="badge {{ $item->status === \App\Repositories\RdpPermintaanRepo::FINISHED_STATUS ? 'badge-soft-success' : 'badge-soft-primary' }} text-wrap" style="white-space:normal; line-height:1.25;">{{ $item->status }}</span></div>
            <div><strong>Tanggal Selesai:</strong> {{ $item->tanggal_selesai ? \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y H:i') : '-' }}</div>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th style="width:60px" class="text-center">No</th>
                <th>Nama Item</th>
                <th>Deskripsi</th>
                <th class="text-center">Jumlah</th>
                <th class="text-center">Satuan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($item->rdp_permintaan_items as $requestItem)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $requestItem->nama_item }}</td>
                    <td>{{ $requestItem->deskripsi_item ?: '-' }}</td>
                    <td class="text-center">{{ $requestItem->jumlah ?: '-' }}</td>
                    <td class="text-center">{{ $requestItem->satuan ?: '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
