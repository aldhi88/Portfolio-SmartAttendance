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
@endif

@include('rdp.pengadaan.partials.item_form')
