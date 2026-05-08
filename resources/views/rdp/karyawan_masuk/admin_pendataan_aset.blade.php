<div>
    <div class="loading-50" wire:loading wire:target="wireSubmit">
        <div class="loader"></div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title', ['pass' => $data])
                <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    <a href="{{ route('rdp.penempatan.izin-penempatan.detail', $item->id) }}" class="btn btn-secondary">
                        Kembali <i class="fas fa-angle-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if (!$canViewAset)
                <div class="alert alert-warning mb-0">
                    Pendataan aset tidak bisa dilakukan pada status saat ini.
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
                                        <input type="text" wire:model="aset.{{ $key }}.jenis" class="form-control @error('aset.'.$key.'.jenis') is-invalid @enderror" @disabled(!$isEditable)>
                                        @error('aset.'.$key.'.jenis')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" wire:model="aset.{{ $key }}.jumlah" class="form-control text-center @error('aset.'.$key.'.jumlah') is-invalid @enderror" @disabled(!$isEditable)>
                                        @error('aset.'.$key.'.jumlah')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" wire:model="aset.{{ $key }}.satuan" class="form-control text-center @error('aset.'.$key.'.satuan') is-invalid @enderror" @disabled(!$isEditable)>
                                        @error('aset.'.$key.'.satuan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <select wire:model="aset.{{ $key }}.status" class="form-control @error('aset.'.$key.'.status') is-invalid @enderror" @disabled(!$isEditable)>
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
                                        <textarea wire:model="aset.{{ $key }}.catatan" rows="1" class="form-control @error('aset.'.$key.'.catatan') is-invalid @enderror" @disabled(!$isEditable)></textarea>
                                        @error('aset.'.$key.'.catatan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($isEditable)
                    <div class="text-right mt-3">
                        <button type="button" class="btn btn-primary"
                            data-toggle="modal" data-target="#modalConfirmDelete"
                            data-json='@json(["msg" => "Apakah anda yakin mengirim pendataan aset ini?", "id" => $item->id])'
                            data-dispatch="wireSubmit()"
                            data-submit-label="Kirim">
                            Kirim Pendataan Aset
                        </button>
                    </div>
                @endif
            @endif
        </div>
    </div>
    @include('components.modal.modal_confirm_delete')
</div>
