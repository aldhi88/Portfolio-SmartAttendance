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
                    <div class="col-12 col-md-5">
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

                    <div class="col-12 col-md">
                        <div class="form-group">
                            <label>Tanggal Waktu</label>
                            <div>
                                <div class="input-daterange input-group">
                                    <input wire:model="form.work_time_lembur" placeholder="Dari kapan.." type="text" class="form-control datetime @error('form.work_time_lembur') is-invalid @enderror" name="start" />
                                    <input wire:model="form.checkout_time_lembur" placeholder="Sampai kapan.." type="text" class="form-control datetime @error('form.checkout_time_lembur') is-invalid @enderror" name="end" />
                                </div>
                                @if ($errors->has('form.work_time_lembur') || $errors->has('form.checkout_time_lembur'))
                                    <div class="text-danger">
                                        <small>{{ $errors->first('form.work_time_lembur') ?: $errors->first('form.checkout_time_lembur') }}</small>
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


                </div>

                <div class="row">
                    <div class="col-12 col-md">
                        <div style="position: relative;" class="form-group">
                            <label>Pekerjaan</label>
                            <input type="text" class="form-control @error('form.pekerjaan') is-invalid @enderror" placeholder="Ketik pekerjaan..." wire:model.live.debounce.400ms="queryPekerjaan">
                            @error('form.pekerjaan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <input type="hidden" wire:model="form.pekerjaan">
                            @error('employee_invalid')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if (!empty($resultsPekerjaan))
                                <ul class="list-group position-absolute w-100" style="z-index: 1000;">
                                    @foreach ($resultsPekerjaan as $item)
                                        <li class="list-group-item list-group-item-action" wire:click="selectPekerjaan('{{ $item }}')" style="cursor: pointer;">
                                            {!! str_ireplace($queryPekerjaan, '<strong>' . $queryPekerjaan . '</strong>', $item) !!}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>

                <hr>
                <div class="row">
                    <div class="col text-center">
                        <h4>Data Penandatangan</h4>
                        <span>(Isi bagian yang perlu saja)</span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-12 col-md">
                        <div class="form-group">
                            <label>Pengawas 1 (Wajib)</label>
                            <select wire:model="form.pengawas1" class="form-control @error('form.pengawas1') is-invalid @enderror">
                                <option value="">- Pilih -</option>
                                @foreach ($ttd['pengawas'] as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                @endforeach
                            </select>
                            @error('form.pengawas1')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md">
                        <div class="form-group">
                            <label>Pengawas 2</label>
                            <select wire:model="form.pengawas2" class="form-control">
                                <option value="">- Pilih -</option>
                                @foreach ($ttd['pengawas'] as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md">
                        <div class="form-group">
                            <label>Security</label>
                            <select wire:model="form.security" class="form-control">
                                <option value="">- Pilih -</option>
                                @foreach ($ttd['security'] as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md">
                        <div class="form-group">
                            <label>Koordinator Lapangan</label>
                            <input type="text" class="form-control" wire:model="form.korlap">
                        </div>
                    </div>

                </div>

                <hr>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>


            </form>



        </div>
    </div>


    @include('lembur.atc.lembur_create_atc')
</div>

