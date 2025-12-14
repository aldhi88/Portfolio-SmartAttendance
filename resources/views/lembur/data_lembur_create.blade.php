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
                            <label>Tanggal</label>
                            <input wire:model="form.tanggal" type="date" class="form-control datetime @error('form.tanggal') is-invalid @enderror" />
                            @error('form.tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md">
                        <div class="form-group">
                            <label>Jam Masuk Lembur</label>
                            <input
                                type="text"
                                placeholder="HH:mm"
                                class="form-control input-mask-time
                                    @error('form.work_time_lembur') is-invalid @enderror"
                                data-field="form.work_time_lembur"
                                wire:model.defer="form.work_time_lembur"
                            >

                            @error('form.work_time_lembur')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 col-md">
                        <div class="form-group">
                            <label>Jam Pulang Lembur</label>
                            <input
                                type="text"
                                placeholder="HH:mm"
                                class="form-control input-mask-time
                                    @error('form.checkout_time_lembur') is-invalid @enderror"
                                data-field="form.checkout_time_lembur"
                                wire:model.defer="form.checkout_time_lembur"
                            >

                            @error('form.checkout_time_lembur')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>


                </div>

                <div class="row">
                    <div class="col-12 col-md">
                        <div class="form-group position-relative">
                            <label>Pekerjaan</label>
                            <input
                                type="text"
                                class="form-control @error('form.pekerjaan') is-invalid @enderror"
                                placeholder="Ketik pekerjaan..."
                                wire:model.live.debounce.400ms="pekerjaanQuery"
                            >

                            @error('form.pekerjaan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            @if (!empty($pekerjaanResults))
                                <ul class="list-group position-absolute w-100" style="z-index: 1000;">
                                    @foreach ($pekerjaanResults as $item)
                                        <li
                                            class="list-group-item list-group-item-action"
                                            wire:click="selectPekerjaan('{{ $item }}')"
                                            style="cursor: pointer;"
                                        >
                                            {!! str_ireplace($pekerjaanQuery, '<strong>'.$pekerjaanQuery.'</strong>', $item) !!}
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
                            <select name="" class="form-control">
                                @foreach ($pengawas as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md">
                        <div class="form-group">
                            <label>Pengawas 2</label>
                            <select name="" class="form-control">
                                @foreach ($pengawas as $item)
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
                            <select name="" class="form-control">
                                @foreach ($pengawas as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md">
                        <div class="form-group">
                            <label>Koordinator Lapangan</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>

                </div>

                <hr>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Ajukan Lembur</button>
                </div>


            </form>



        </div>
    </div>


    @include('lembur.atc.lembur_create_atc')
</div>

