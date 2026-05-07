<div class="table-responsive">
    <table class="table table-bordered mb-0">
        <thead>
            <tr>
                <th class="text-center" width="10">No</th>
                <th style="min-width: 240px">Aset / Perlengkapan</th>
                <th style="min-width: 220px">Jenis</th>
                <th style="min-width: 120px">Jumlah</th>
                <th style="min-width: 170px">Satuan</th>
                <th class="text-center" width="10">
                    <button type="button" wire:click="addRow" class="btn btn-success btn-sm">
                        <i class="fas fa-plus"></i>
                    </button>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detail as $key => $item)
                <tr wire:key="cluster-aset-row-{{ $key }}">
                    <td class="text-center align-middle">{{ $key + 1 }}</td>
                    <td>
                        <select wire:model="detail.{{ $key }}.aset_id" class="form-control @error('detail.'.$key.'.aset_id') is-invalid @enderror">
                            <option value="">- Pilih Aset -</option>
                            @foreach ($dt['aset'] as $aset)
                                <option value="{{ $aset['id'] }}">{{ $aset['perlengkapan'] }}</option>
                            @endforeach
                        </select>
                        @error('detail.'.$key.'.aset_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </td>
                    <td>
                        <input type="text" wire:model="detail.{{ $key }}.jenis" class="form-control @error('detail.'.$key.'.jenis') is-invalid @enderror">
                        @error('detail.'.$key.'.jenis')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </td>
                    <td>
                        <input type="text" wire:model="detail.{{ $key }}.jumlah" class="form-control @error('detail.'.$key.'.jumlah') is-invalid @enderror">
                        @error('detail.'.$key.'.jumlah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </td>
                    <td>
                        <select wire:model="detail.{{ $key }}.satuan" class="form-control @error('detail.'.$key.'.satuan') is-invalid @enderror">
                            <option value="">-</option>
                            @foreach ($dt['satuan'] as $satuan)
                                <option value="{{ $satuan }}">{{ $satuan }}</option>
                            @endforeach
                        </select>
                        @error('detail.'.$key.'.satuan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </td>
                    <td class="text-center align-middle">
                        <button type="button" wire:click="removeRow({{ $key }})" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
