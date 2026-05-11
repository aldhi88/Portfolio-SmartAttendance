@php
    $penempatanLabel = function ($penempatan) {
        $employee = $penempatan['data_employees'] ?? [];
        $rumah = $penempatan['rdp_master_rumahs'] ?? [];
        $cluster = $rumah['rdp_master_clusters']['nama_cluster'] ?? '-';
        $unit = collect([$rumah['block'] ?? null, $rumah['tipe'] ?? null, $rumah['nomor'] ?? null])->filter()->implode(' ');
        return trim(($employee['name'] ?? '-') . ' - ' . ($unit ?: '-') . ' - ' . $cluster);
    };
@endphp

@if (($mode ?? 'admin') === 'karyawan')
    @if ($penempatan)
        <div class="border rounded p-3 mb-3 bg-light">
            <div><strong>Rumah:</strong> {{ collect([$penempatan->rdp_master_rumahs?->block, $penempatan->rdp_master_rumahs?->tipe, $penempatan->rdp_master_rumahs?->nomor])->filter()->implode(' ') ?: '-' }}</div>
            <div><strong>Cluster:</strong> {{ $penempatan->rdp_master_rumahs?->rdp_master_clusters?->nama_cluster ?: '-' }}</div>
            <div><strong>Karyawan:</strong> {{ $penempatan->data_employees?->name ?: '-' }}</div>
        </div>
    @else
        <div class="alert alert-warning">Anda belum memiliki rumah dinas aktif, sehingga belum bisa mengajukan pengadaan.</div>
    @endif
@else
    <div class="form-group">
        <label>Rumah/Karyawan <span class="text-danger">*</span></label>
        <select wire:model.live="form.rdp_karyawan_masuk_id" class="form-control @error('form.rdp_karyawan_masuk_id') is-invalid @enderror">
            <option value="">Pilih Rumah/Karyawan</option>
            @foreach ($dt['penempatans'] as $penempatan)
                <option value="{{ $penempatan['id'] }}">{{ $penempatanLabel($penempatan) }}</option>
            @endforeach
        </select>
        @error('form.rdp_karyawan_masuk_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    @if ($this->selectedPenempatan())
        @php($selected = $this->selectedPenempatan())
        <div class="border rounded p-3 mb-3 bg-light">
            <div><strong>Karyawan:</strong> {{ $selected['data_employees']['name'] ?? '-' }}</div>
            <div><strong>NOPek:</strong> {{ $selected['data_employees']['number'] ?? '-' }}</div>
            <div><strong>Jabatan:</strong> {{ $selected['data_employees']['master_positions']['name'] ?? '-' }}</div>
            <div><strong>Rumah:</strong> {{ collect([$selected['rdp_master_rumahs']['block'] ?? null, $selected['rdp_master_rumahs']['tipe'] ?? null, $selected['rdp_master_rumahs']['nomor'] ?? null])->filter()->implode(' ') ?: '-' }}</div>
            <div><strong>Cluster:</strong> {{ $selected['rdp_master_rumahs']['rdp_master_clusters']['nama_cluster'] ?? '-' }}</div>
        </div>
    @endif
@endif

@if ($showVendor ?? false)
    <div class="form-group">
        <label>Vendor <span class="text-danger">*</span></label>
        <select wire:model="form.rdp_master_vendor_id" class="form-control @error('form.rdp_master_vendor_id') is-invalid @enderror">
            <option value="">Pilih Vendor</option>
            @foreach ($dt['vendors'] as $vendor)
                <option value="{{ $vendor['id'] }}">{{ $vendor['nama'] }}</option>
            @endforeach
        </select>
        @error('form.rdp_master_vendor_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
@endif

@if ($showStatus ?? false)
    <div class="form-group">
        <label>Status <span class="text-danger">*</span></label>
        <select wire:model="form.status" class="form-control @error('form.status') is-invalid @enderror">
            @foreach ($statusList as $status)
                <option value="{{ $status }}">{{ $status }}</option>
            @endforeach
        </select>
        @error('form.status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group">
        <label>Catatan Revisi</label>
        <textarea wire:model="form.catatan_revisi" rows="3" class="form-control @error('form.catatan_revisi') is-invalid @enderror"></textarea>
        @error('form.catatan_revisi')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
@endif

@include('rdp.pengadaan.partials.item_form')
