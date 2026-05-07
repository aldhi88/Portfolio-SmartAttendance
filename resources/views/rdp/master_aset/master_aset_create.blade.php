<div>
    <div class="loading-50" wire:loading wire:target="wireSubmit">
        <div class="loader"></div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title', ['pass' => $data])
                <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    <a href="{{ route('rdp.master.aset.index') }}" class="btn btn-secondary">
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
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="10">No</th>
                                        <th>Nama Perlengkapan</th>
                                        <th class="text-center" width="10">
                                            <button type="button" wire:click="addRow" class="btn btn-success btn-sm">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($form as $key => $item)
                                        <tr wire:key="aset-row-{{ $key }}">
                                            <td class="text-center align-middle">{{ $key + 1 }}</td>
                                            <td>
                                                <input type="text" wire:model="form.{{ $key }}.perlengkapan" class="form-control @error('form.'.$key.'.perlengkapan') is-invalid @enderror" autofocus>
                                                @error('form.'.$key.'.perlengkapan')
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

                        <div class="mt-3 text-right">
                            <button type="button" wire:click="addRow" class="btn btn-success waves-effect">
                                <i class="fas fa-plus fa-fw"></i> Tambah Baris
                            </button>
                            <button type="submit" class="btn btn-primary waves-effect waves-light">
                                Simpan Semua Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
