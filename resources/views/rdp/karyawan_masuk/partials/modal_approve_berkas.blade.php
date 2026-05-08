@php
    $rumahLabel = function ($rumah) {
        if (!$rumah) return '-';
        return collect([$rumah['block'] ?? null, $rumah['tipe'] ?? null, $rumah['nomor'] ?? null])
            ->filter()
            ->implode(' ');
    };
@endphp

<div wire:ignore.self class="modal fade" id="modalApproveBerkas" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <h5 class="msg mb-3">Setujui berkas pengajuan</h5>
                        <div class="form-group">
                            <label>Unit Rumah <span class="text-danger">*</span></label>
                            <select wire:model="approveRumahId" class="form-control @error('approveRumahId') is-invalid @enderror">
                                <option value="">Pilih Unit Rumah</option>
                                @foreach (($dt['rumahs'] ?? []) as $rumah)
                                    <option value="{{ $rumah['id'] }}">
                                        {{ $rumahLabel($rumah) }} - {{ $rumah['rdp_master_clusters']['nama_cluster'] ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('approveRumahId')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="text-right mt-4">
                    <button type="button" class="btn btn-light waves-effect px-4" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success waves-effect waves-light px-4" wire:click="wireApproveBerkas">
                        Setujui Berkas
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
