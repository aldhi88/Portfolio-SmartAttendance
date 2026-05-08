<div>
    <div class="loading-50" wire:loading wire:target="wireSubmit">
        <div class="loader"></div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title', ['pass' => $data])
                <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    <a href="{{ route('rdp.master.rumah.index') }}" class="btn btn-secondary">
                        Kembali <i class="fas fa-angle-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <form wire:submit.prevent="wireSubmit">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        @include('rdp.master_rumah.partials.form_rumah')

                        @if ((string) ($form['rdp_master_cluster_id'] ?? '') !== (string) $originalClusterId)
                            <div class="alert alert-warning">
                                Cluster berubah. Data aset rumah tidak disinkronkan otomatis, silakan atur daftar aset rumah secara manual sebelum menyimpan.
                            </div>
                        @endif

                        <h4 class="lead mt-4">Aset Rumah</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="10">No</th>
                                        <th>Aset / Perlengkapan</th>
                                        <th>Jenis</th>
                                        <th>Jumlah</th>
                                        <th>Satuan</th>
                                        <th>Status</th>
                                        <th>Catatan</th>
                                        <th class="text-center" width="10">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($aset as $key => $item)
                                        <tr>
                                            <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                            <td>
                                                <select wire:model="aset.{{ $key }}.rdp_master_aset_id" class="form-control @error('aset.'.$key.'.rdp_master_aset_id') is-invalid @enderror">
                                                    <option value="">Pilih Aset</option>
                                                    @foreach ($dt['aset'] as $masterAset)
                                                        <option value="{{ $masterAset['id'] }}">{{ $masterAset['perlengkapan'] }}</option>
                                                    @endforeach
                                                </select>
                                                @error('aset.'.$key.'.rdp_master_aset_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="text" wire:model="aset.{{ $key }}.jenis" class="form-control @error('aset.'.$key.'.jenis') is-invalid @enderror">
                                                @error('aset.'.$key.'.jenis')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="text" wire:model="aset.{{ $key }}.jumlah" class="form-control @error('aset.'.$key.'.jumlah') is-invalid @enderror">
                                                @error('aset.'.$key.'.jumlah')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <select wire:model="aset.{{ $key }}.satuan" class="form-control @error('aset.'.$key.'.satuan') is-invalid @enderror">
                                                    <option value="">-</option>
                                                    @foreach ($dt['satuan'] as $satuan)
                                                        <option value="{{ $satuan }}">{{ $satuan }}</option>
                                                    @endforeach
                                                </select>
                                                @error('aset.'.$key.'.satuan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <select wire:model="aset.{{ $key }}.status" class="form-control @error('aset.'.$key.'.status') is-invalid @enderror">
                                                    @foreach ($dt['aset_status'] as $status)
                                                        <option value="{{ $status }}">{{ $status }}</option>
                                                    @endforeach
                                                </select>
                                                @error('aset.'.$key.'.status')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <textarea wire:model="aset.{{ $key }}.catatan" class="form-control @error('aset.'.$key.'.catatan') is-invalid @enderror" rows="1"></textarea>
                                                @error('aset.'.$key.'.catatan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td class="text-center align-middle">
                                                <button type="button" wire:click="removeAsetRow({{ $key }})" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash fa-fw"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Belum ada aset rumah.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3 text-right">
                            <button type="button" wire:click="addAsetRow" class="btn btn-success waves-effect">
                                <i class="fas fa-plus fa-fw"></i> Tambah Aset
                            </button>
                            <button type="submit" class="btn btn-primary waves-effect waves-light">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
