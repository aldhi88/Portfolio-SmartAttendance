@php
    $penempatanLabel = function ($penempatan) {
        $employee = $penempatan['data_employees'] ?? [];
        $rumah = $penempatan['rdp_master_rumahs'] ?? [];
        $cluster = $rumah['rdp_master_clusters']['nama_cluster'] ?? '-';
        $unit = collect([$rumah['block'] ?? null, $rumah['tipe'] ?? null, $rumah['nomor'] ?? null])->filter()->implode(' ');
        return trim(($employee['name'] ?? '-') . ' - ' . ($unit ?: '-') . ' - ' . $cluster);
    };
@endphp

<div class="form-group">
    <label>Karyawan/Rumah Aktif <span class="text-danger">*</span></label>
    <select wire:model.live="form.rdp_karyawan_masuk_id" class="form-control @error('form.rdp_karyawan_masuk_id') is-invalid @enderror">
        <option value="">Pilih Karyawan/Rumah</option>
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

<div class="alert alert-info">
    Permintaan yang dibuat admin akan langsung berstatus <strong>Selesai</strong>.
</div>

@include('rdp.permintaan.partials.item_form')
