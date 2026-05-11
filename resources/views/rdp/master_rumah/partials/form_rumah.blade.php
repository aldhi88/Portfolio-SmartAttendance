<div class="form-group">
    <label>Cluster</label>
    <select wire:model="form.rdp_master_cluster_id" class="form-control @error('form.rdp_master_cluster_id') is-invalid @enderror">
        <option value="">Pilih Cluster</option>
        @foreach ($dt['cluster'] as $cluster)
            <option value="{{ $cluster['id'] }}">{{ $cluster['nama_cluster'] }}</option>
        @endforeach
    </select>
    @error('form.rdp_master_cluster_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>Block</label>
            <input type="text" wire:model="form.block" class="form-control @error('form.block') is-invalid @enderror">
            @error('form.block')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Tipe</label>
            <input type="text" wire:model="form.tipe" class="form-control @error('form.tipe') is-invalid @enderror">
            @error('form.tipe')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Nomor</label>
            <input type="text" wire:model="form.nomor" class="form-control @error('form.nomor') is-invalid @enderror">
            @error('form.nomor')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="form-group">
    <label>Status</label>
    @if ($dt['status_readonly'] ?? false)
        <input type="text" value="Terisi (otomatis dari penempatan)" class="form-control" readonly>
    @else
        <select wire:model="form.status" class="form-control @error('form.status') is-invalid @enderror">
            @foreach ($dt['status'] as $status)
                <option value="{{ $status }}">{{ $status }}</option>
            @endforeach
        </select>
    @endif
    @error('form.status')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
