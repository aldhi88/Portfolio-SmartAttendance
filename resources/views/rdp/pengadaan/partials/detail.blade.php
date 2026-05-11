@php
    $proposalUrl = $item->file_proposal ? asset('storage/' . \App\Repositories\RdpPengadaanRepo::FILE_DIR_PROPOSAL . '/' . $item->file_proposal) : null;
    $spkVisible = in_array($item->status, [
        \App\Repositories\RdpPengadaanRepo::WORK_RUNNING_STATUS,
        \App\Repositories\RdpPengadaanRepo::VENDOR_FINISHED_STATUS,
        \App\Repositories\RdpPengadaanRepo::RESULT_SPV_APPROVED_STATUS,
        \App\Repositories\RdpPengadaanRepo::FINISHED_STATUS,
    ], true);
    $spkRoute = null;
    if ($spkVisible) {
        if (\App\Helpers\RdpAccess::isAdmin()) {
            $spkRoute = route('rdp.pengadaan.spk', $item->id);
        } elseif (\App\Helpers\RdpAccess::isPimpinan()) {
            $spkRoute = route('rdp.persetujuan.pengadaan.spk', $item->id);
        } elseif (\App\Helpers\RdpAccess::isVendor()) {
            $spkRoute = route('rdp.vendor.pengadaan.spk', $item->id);
        } elseif (\App\Helpers\RdpAccess::isEmployee()) {
            $spkRoute = route('rdp.pengajuan.pengadaan.spk', $item->id);
        }
    }
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
            <div><strong>Status:</strong> <span class="badge badge-soft-primary text-wrap" style="white-space:normal; line-height:1.25;">{{ $item->status }}</span></div>
            <div><strong>Vendor:</strong> {{ $item->rdp_master_vendors?->nama ?: '-' }}</div>
            @if ($showCatatanRevisi ?? true)
                <div><strong>Catatan Revisi:</strong> {{ $item->catatan_revisi ?: '-' }}</div>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="border rounded p-3 mb-3">
            <h6>Dokumen</h6>
            <div><strong>Proposal:</strong> {!! $proposalUrl ? '<a href="' . $proposalUrl . '" target="_blank">Lihat proposal</a>' : '-' !!}</div>
            <div><strong>SPK:</strong> {!! $spkRoute ? '<a href="' . $spkRoute . '" target="_blank">Lihat SPK</a>' : '-' !!}</div>
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
                <th>Hasil Pengadaan</th>
                <th>Foto Hasil</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($item->rdp_pengadaan_items as $procurementItem)
                @php
                    $fotoHasilUrl = $procurementItem->foto_hasil_pengadaan
                        ? asset('storage/' . \App\Repositories\RdpPengadaanRepo::FILE_DIR_HASIL . '/' . $procurementItem->foto_hasil_pengadaan)
                        : null;
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $procurementItem->nama_item }}</td>
                    <td>{{ $procurementItem->deskripsi_item }}</td>
                    <td class="text-center">{{ $procurementItem->jumlah ?: '-' }}</td>
                    <td class="text-center">{{ $procurementItem->satuan ?: '-' }}</td>
                    <td>{{ $procurementItem->narasi_hasil_pengadaan ?: '-' }}</td>
                    <td>
                        @if ($fotoHasilUrl)
                            <a href="javascript:void(0)" class="preview-pengadaan-foto" data-src="{{ $fotoHasilUrl }}" data-title="Foto Hasil - {{ $procurementItem->nama_item }}">
                                <img src="{{ $fotoHasilUrl }}" alt="Foto hasil {{ $procurementItem->nama_item }}" class="img-thumbnail" style="width:90px; height:70px; object-fit:cover;">
                            </a>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div wire:ignore.self class="modal fade" id="modalPreviewPengadaanFoto" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview Foto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img src="" alt="Preview foto pengadaan" class="img-fluid rounded preview-image">
            </div>
        </div>
    </div>
</div>

@push('push-script')
    <script>
        $(document).on('click', '.preview-pengadaan-foto', function() {
            const src = $(this).data('src');
            const title = $(this).data('title') || 'Preview Foto';
            $('#modalPreviewPengadaanFoto .modal-title').text(title);
            $('#modalPreviewPengadaanFoto .preview-image').attr('src', src);
            $('#modalPreviewPengadaanFoto').modal('show');
        });
    </script>
@endpush
