@php
    $penempatan = $item->rdp_karyawan_masuks;
    $employee = $penempatan?->data_employees;
    $rumah = $penempatan?->rdp_master_rumahs;
    $unit = collect([$rumah?->block, $rumah?->tipe, $rumah?->nomor])->filter()->implode(' ');
    $proposalUrl = $item->file_proposal ? asset('storage/' . \App\Repositories\RdpPerbaikanRepo::FILE_DIR_PROPOSAL . '/' . $item->file_proposal) : null;
    $spkVisible = in_array($item->status, [
        \App\Repositories\RdpPerbaikanRepo::WORK_RUNNING_STATUS,
        \App\Repositories\RdpPerbaikanRepo::VENDOR_FINISHED_STATUS,
        \App\Repositories\RdpPerbaikanRepo::RESULT_SPV_APPROVED_STATUS,
        \App\Repositories\RdpPerbaikanRepo::FINISHED_STATUS,
    ], true);
@endphp

<div class="row">
    <div class="col-md-6">
        <div class="border rounded p-3 mb-3 bg-light">
            <h6>Data Karyawan</h6>
            <div><strong>Nama:</strong> {{ $employee?->name ?: '-' }}</div>
            <div><strong>NOPek:</strong> {{ $employee?->number ?: '-' }}</div>
            <div><strong>Jabatan:</strong> {{ $employee?->master_positions?->name ?: '-' }}</div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="border rounded p-3 mb-3 bg-light">
            <h6>Data Rumah</h6>
            <div><strong>Unit:</strong> {{ $unit ?: '-' }}</div>
            <div><strong>Cluster:</strong> {{ $rumah?->rdp_master_clusters?->nama_cluster ?: '-' }}</div>
            <div><strong>Status:</strong> {{ $rumah?->status ?: '-' }}</div>
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
            <div><strong>SPK:</strong> {!! $spkVisible ? '<a href="' . route('rdp.perbaikan.spk', $item->id) . '" target="_blank">Lihat SPK</a>' : '-' !!}</div>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th style="width:60px" class="text-center">No</th>
                <th>Nama Item</th>
                <th>Kerusakan</th>
                <th>Foto Kerusakan</th>
                <th>Hasil Perbaikan</th>
                <th>Foto Hasil</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($item->rdp_perbaikan_items as $repairItem)
                @php
                    $fotoKerusakanUrl = $repairItem->foto_kerusakan
                        ? asset('storage/' . \App\Repositories\RdpPerbaikanRepo::FILE_DIR_KERUSAKAN . '/' . $repairItem->foto_kerusakan)
                        : null;
                    $fotoHasilUrl = $repairItem->foto_hasil_perbaikan
                        ? asset('storage/' . \App\Repositories\RdpPerbaikanRepo::FILE_DIR_HASIL . '/' . $repairItem->foto_hasil_perbaikan)
                        : null;
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $repairItem->nama_item }}</td>
                    <td>{{ $repairItem->deskripsi_kerusakan }}</td>
                    <td>
                        @if ($fotoKerusakanUrl)
                            <a href="javascript:void(0)" class="preview-perbaikan-foto" data-src="{{ $fotoKerusakanUrl }}" data-title="Foto Kerusakan - {{ $repairItem->nama_item }}">
                                <img src="{{ $fotoKerusakanUrl }}" alt="Foto kerusakan {{ $repairItem->nama_item }}" class="img-thumbnail" style="width:90px; height:70px; object-fit:cover;">
                            </a>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $repairItem->narasi_hasil_perbaikan ?: '-' }}</td>
                    <td>
                        @if ($fotoHasilUrl)
                            <a href="javascript:void(0)" class="preview-perbaikan-foto" data-src="{{ $fotoHasilUrl }}" data-title="Foto Hasil - {{ $repairItem->nama_item }}">
                                <img src="{{ $fotoHasilUrl }}" alt="Foto hasil {{ $repairItem->nama_item }}" class="img-thumbnail" style="width:90px; height:70px; object-fit:cover;">
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

<div wire:ignore.self class="modal fade" id="modalPreviewPerbaikanFoto" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview Foto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img src="" alt="Preview foto perbaikan" class="img-fluid rounded preview-image">
            </div>
        </div>
    </div>
</div>

@push('push-script')
    <script>
        $(document).on('click', '.preview-perbaikan-foto', function() {
            const src = $(this).data('src');
            const title = $(this).data('title') || 'Preview Foto';
            $('#modalPreviewPerbaikanFoto .modal-title').text(title);
            $('#modalPreviewPerbaikanFoto .preview-image').attr('src', src);
            $('#modalPreviewPerbaikanFoto').modal('show');
        });
    </script>
@endpush
