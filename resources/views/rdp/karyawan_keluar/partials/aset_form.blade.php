<div class="mt-3">
    <h5>Pendataan Aset Rumah</h5>
    @if (empty($form['rdp_master_rumah_id']))
        <div class="alert alert-warning mb-0">
            Pilih rumah terlebih dahulu untuk menampilkan data aset.
        </div>
    @elseif (count($aset) === 0)
        <div class="alert alert-warning mb-0">
            Data aset rumah belum tersedia.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped mb-0">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 60px;">No</th>
                        <th>Aset</th>
                        <th style="width: 160px;">Jenis</th>
                        <th class="text-center" style="width: 120px;">Jumlah</th>
                        <th class="text-center" style="width: 120px;">Satuan</th>
                        <th class="text-center" style="width: 180px;">Status</th>
                        <th style="width: 220px;">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($aset as $key => $itemAset)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $itemAset['nama'] }}</td>
                            <td>
                                <input type="text" wire:model="aset.{{ $key }}.jenis" class="form-control @error('aset.'.$key.'.jenis') is-invalid @enderror">
                                @error('aset.'.$key.'.jenis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </td>
                            <td>
                                <input type="text" wire:model="aset.{{ $key }}.jumlah" class="form-control text-center @error('aset.'.$key.'.jumlah') is-invalid @enderror">
                                @error('aset.'.$key.'.jumlah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </td>
                            <td>
                                <select wire:model="aset.{{ $key }}.satuan" class="form-control @error('aset.'.$key.'.satuan') is-invalid @enderror">
                                    <option value="">-</option>
                                    @foreach ($satuanList as $satuan)
                                        <option value="{{ $satuan }}">{{ $satuan }}</option>
                                    @endforeach
                                </select>
                                @error('aset.'.$key.'.satuan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </td>
                            <td>
                                <select wire:model="aset.{{ $key }}.status" class="form-control @error('aset.'.$key.'.status') is-invalid @enderror">
                                    <option value="">Pilih Status</option>
                                    @foreach ($statusList as $status)
                                        <option value="{{ $status }}">{{ $status }}</option>
                                    @endforeach
                                </select>
                                @error('aset.'.$key.'.status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </td>
                            <td>
                                <textarea wire:model="aset.{{ $key }}.catatan" rows="1" class="form-control @error('aset.'.$key.'.catatan') is-invalid @enderror"></textarea>
                                @error('aset.'.$key.'.catatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
