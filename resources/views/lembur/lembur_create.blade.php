<div>
    {{-- <div class="loading-50" wire:loading><div class="loader"></div></div> --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title')
                <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    <a href="{{ route('lembur.indexLembur') }}" class="btn btn-secondary">
                        Kembali <i class="fas fa-angle-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">

            <form wire:submit.prevent="wireSubmit">

                <div class="row">
                    <div class="col-12 col-md-4">
                        <div style="position: relative;" class="form-group">
                            <label>Nama Karyawan</label>
                            <input type="text" class="form-control @error('form.mask') is-invalid @enderror @error('employee_invalid') is-invalid @enderror" placeholder="Ketik nama..." wire:model.live.debounce.400ms="query">
                            <input type="hidden" wire:model="form.data_employee_id">
                            @error('form.mask')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @error('employee_invalid')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if (!empty($results))
                                <ul class="list-group position-absolute w-100" style="z-index: 1000;">
                                    @foreach ($results as $item)
                                        <li class="list-group-item list-group-item-action" wire:click="selectNama('{{ $item['id'] }}')" style="cursor: pointer;">
                                            {!! str_ireplace($query, '<strong>' . $query . '</strong>', $item['name']) !!}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label>Tanggal</label>
                            <input wire:model="form.tanggal" type="date" class="form-control datetime @error('form.tanggal') is-invalid @enderror" />
                            @error('form.tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>


            </form>



        </div>
    </div>


    @include('lembur.atc.lembur_create_atc')
</div>

