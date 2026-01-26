<div>
    {{-- <div class="loading-50" wire:loading><div class="loader"></div></div> --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title')
                {{-- <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modalCreate">
                        <i class="fas fa-plus fa-fw"></i> Tambah Data Baru
                    </button>
                </div> --}}
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col col-md-6">
            <form wire:submit="simpan">

                <div class="form-group">
                    <label>List Alamat Email</label>
                    <button class="btn btn-sm btn-success" wire:click="addEmail">Tambah List Email</button>
                    @foreach ($form as $key => $item)
                        <input type="text" class="form-control mb-2 @error('form.{{$key}}') is-invalid @enderror" wire:model="form.{{ $key }}">
                    @endforeach
                </div>

                <div class="form-group">

                    <button type="submit" class="btn btn-primary">Simpan</button>

                </div>

            </form>
        </div>
    </div>

</div>
