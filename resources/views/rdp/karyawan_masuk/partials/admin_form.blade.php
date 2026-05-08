@php
    $rumahLabel = function ($rumah) {
        if (!$rumah) return '-';
        return collect([$rumah['block'] ?? null, $rumah['tipe'] ?? null, $rumah['nomor'] ?? null])
            ->filter()
            ->implode(' ');
    };
@endphp
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Karyawan <span class="text-danger">*</span></label>
            <select wire:model.live="form.data_employee_id" class="form-control @error('form.data_employee_id') is-invalid @enderror">
                <option value="">Pilih Karyawan</option>
                @foreach ($dt['employees'] as $item)
                    <option value="{{ $item['id'] }}">{{ $item['id'] }} - {{ $item['name'] }}</option>
                @endforeach
            </select>
            @error('form.data_employee_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        @if ($this->selectedEmployee())
            @php($employee = $this->selectedEmployee())
            <div class="border rounded p-3 mb-3 bg-light">
                <div><strong>Nama:</strong> {{ $employee['name'] ?? '-' }}</div>
                <div><strong>NOPek:</strong> {{ $employee['number'] ?? '-' }}</div>
                <div><strong>Perusahaan:</strong> {{ $employee['master_organizations']['name'] ?? '-' }}</div>
                <div><strong>Jabatan:</strong> {{ $employee['master_positions']['name'] ?? '-' }}</div>
                <div><strong>Status:</strong> {{ $employee['status'] ?? '-' }}</div>
            </div>
        @endif
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Rumah <span class="text-danger">*</span></label>
            <select wire:model.live="form.rdp_master_rumah_id" class="form-control @error('form.rdp_master_rumah_id') is-invalid @enderror">
                <option value="">Pilih Rumah</option>
                @foreach ($dt['rumahs'] as $item)
                    <option value="{{ $item['id'] }}">{{ $rumahLabel($item) }} - {{ $item['rdp_master_clusters']['nama_cluster'] ?? '-' }}</option>
                @endforeach
            </select>
            @error('form.rdp_master_rumah_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        @if ($this->selectedRumah())
            @php($rumah = $this->selectedRumah())
            <div class="border rounded p-3 mb-3 bg-light">
                <div><strong>Rumah:</strong> {{ $rumahLabel($rumah) }}</div>
                <div><strong>Cluster:</strong> {{ $rumah['rdp_master_clusters']['nama_cluster'] ?? '-' }}</div>
                <div><strong>Status:</strong> {{ $rumah['status'] ?? '-' }}</div>
            </div>
        @endif
    </div>
</div>

@include('rdp.karyawan_masuk.partials.sk_form')

@if ($showAdminReviewFields ?? true)
    <div class="form-group">
        <label>Status</label>
        <select wire:model="form.status" class="form-control @error('form.status') is-invalid @enderror">
            @foreach ($statusList as $status)
                <option value="{{ $status }}">{{ $status }}</option>
            @endforeach
        </select>
        @error('form.status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
@endif
