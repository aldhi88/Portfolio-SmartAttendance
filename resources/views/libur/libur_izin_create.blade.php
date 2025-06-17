<div>
    {{-- <div class="loading-50" wire:loading><div class="loader"></div></div> --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title')
                <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    <a href="{{ route('izin.indexIzin') }}" class="btn btn-secondary">
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
                            <input type="text" class="form-control @error('form.data_employee_id') is-invalid @enderror @error('employee_invalid') is-invalid @enderror" placeholder="Ketik nama..." wire:model.live.debounce.400ms="query">
                            <input type="hidden" wire:model="form.data_employee_id">
                            @error('form.data_employee_id')
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
                            <label>Jenis Izin</label>
                            <select wire:model="form.jenis" class="form-control @error('form.jenis') is-invalid @enderror">
                                <option value="">Pilih</option>
                                @foreach ($izinList as $item)
                                <option value="{{$item}}">{{$item}}</option>
                                @endforeach
                            </select>
                            @error('form.jenis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-5">
                        <div class="form-group">
                            <label>Tanggal Waktu</label>
                            <div>
                                <div class="input-daterange input-group">
                                    <input wire:model="form.from" placeholder="Dari kapan.." type="text" class="form-control datetime @error('form.from') is-invalid @enderror" name="start" />
                                    <input wire:model="form.to" placeholder="Sampai kapan.." type="text" class="form-control datetime @error('form.to') is-invalid @enderror" name="end" />
                                </div>
                                @if ($errors->has('form.from') || $errors->has('form.to'))
                                    <div class="text-danger">
                                        <small>{{ $errors->first('form.from') ?: $errors->first('form.to') }}</small>
                                    </div>
                                @endif

                                @error('tgl_range_invalid')
                                    <div class="text-danger">
                                        <small>{{ $message }}</small>
                                    </div>
                                @enderror

                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md">
                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea wire:model="form.desc" class="form-control" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label>Upload Bukti <small>(PDF, JPG, JPEG, PNG) Maks. 10MB</small></label>
                            <input wire:model="form.bukti" type="file" class="form-control">
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>


            </form>



        </div>
    </div>


    @include('libur.atc.libur_izin_create_atc')
</div>

