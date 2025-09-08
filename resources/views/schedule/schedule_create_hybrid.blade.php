<div>
    <div class="loading-50" wire:loading wire:target="addShift">
        <div class="loader"></div>
    </div>



    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title')
                <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    <a href="{{ route('jadwal-kerja.index') }}" class="btn btn-secondary">
                        Kembali <i class="fas fa-angle-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <form wire:submit.prevent="wireSubmitHybrid">

        <div class="row">
            <div class="col-12 col-md-9">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label>Kode Jadwal</label>
                            <input wire:model="dtRotasi.kode" autofocus type="text"
                                class="form-control @error('dtRotasi.kode') is-invalid @enderror">
                            @error('dtRotasi.kode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>Nama Jadwal</label>
                            <input wire:model="dtRotasi.name" autofocus type="text"
                                class="form-control @error('dtRotasi.name') is-invalid @enderror">
                            @error('dtRotasi.name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 col-md">
                        <div class="form-group">
                            <label>Tgl Rotasi Awal</label>
                            <input wire:model="dtRotasi.day_work.start_date" type="date"
                                class="form-control @error('dtRotasi.day_work.start_date') is-invalid @enderror">
                            @error('dtRotasi.day_work.start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md">
                        <div class="form-group">
                            <label>Jlh Rotasi Hari Kerja per Shift</label>
                            <input min="1" wire:model="dtRotasi.day_work.work_day" type="number"
                                class="form-control @error('dtRotasi.day_work.work_day') is-invalid @enderror">
                            @error('dtRotasi.day_work.work_day')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md">
                        <div class="form-group">
                            <label>Jlh Rotasi Off per Shift</label>
                            <input min="0" wire:model="dtRotasi.day_work.off_day" type="number"
                                class="form-control @error('dtRotasi.day_work.off_day') is-invalid @enderror">
                            @error('dtRotasi.day_work.off_day')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

            </div>

            <div class="col">
                <h5>Hari Kerja</h5>
                @error('dtRotasi.day_work.day')
                    <p class="text-danger my-0">{{ $message }}</p>
                @enderror
                @foreach ($hariIndo as $key => $item)
                <div class="form-check mb-1">
                    <input wire:model="dtRotasi.day_work.day.{{ $key }}" type="checkbox"
                           class="form-check-input regular"
                           value="{{ $key }}"
                           id="regular-{{ $key }}">
                    <label class="form-check-label" for="regular-{{ $key }}" style="padding-top: 2px">
                        {{ $item }}
                    </label>
                </div>
                @endforeach
            </div>
        </div>
        <hr>

        <div class="row">
            <div class="col-12 col-md-2">
                <div class="form-group mb-0">
                    <label>Nama Shift</label>
                </div>
            </div>
            <div class="col-12 col-md">
                <div class="form-group mb-0">
                    <label>Checkin Start</label>
                </div>
            </div>
            <div class="col-12 col-md">
                <div class="form-group mb-0">
                    <label>Checkin Ontime</label>
                </div>
            </div>
            <div class="col-12 col-md">
                <div class="form-group mb-0">
                    <label>Checkin End</label>
                </div>
            </div>
            <div class="col-12 col-md">
                <div class="form-group mb-0">
                    <label>Checkout Start</label>
                </div>
            </div>
            <div class="col-12 col-md">
                <div class="form-group mb-0">
                    <label>Checkout End</label>
                </div>
            </div>
        </div>

        @for ($i = 0; $i < $jlhShift; $i++)
            <div class="row">
                <div class="col-12 col-md-2">
                    <div class="form-group mb-1 position-relative">
                        @if ($i > 1)
                            <i wire:click="delShift({{ $i }})"
                                class="fas fa-times text-danger position-absolute top-50 end-0 translate-middle-y me-2"
                                style="cursor: pointer"></i>
                        @endif

                        <input placeholder="Nama Shift" wire:model="dtRotasi.day_work.time.{{ $i }}.name"
                            class="form-control pe-5 @error("dtRotasi.day_work.time.$i.name") is-invalid @enderror">

                        @error("dtRotasi.day_work.time.$i.name")
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-12 col-md">
                    <div class="form-group mb-1">
                        <input placeholder="HH:mm:ss"
                            wire:model="dtRotasi.day_work.time.{{ $i }}.checkin_time"
                            class="form-control input-mask-time @error("dtRotasi.day_work.time.$i.checkin_time") is-invalid @enderror"
                            id="{{ $i }}.checkin_time"
                            data-inputmask="'mask': '99:99:99','inputFormat': 'HH:mm:ss'">
                        @error("dtRotasi.day_work.time.$i.checkin_time")
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12 col-md">
                    <div class="form-group mb-1">
                        <input placeholder="HH:mm:ss" wire:model="dtRotasi.day_work.time.{{ $i }}.work_time"
                            class="form-control input-mask-time @error("dtRotasi.day_work.time.$i.work_time") is-invalid @enderror"
                            id="{{ $i }}.work_time"
                            data-inputmask="'mask': '99:99:99','inputFormat': 'HH:mm:ss'">
                        @error("dtRotasi.day_work.time.$i.work_time")
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12 col-md">
                    <div class="form-group mb-1">
                        <input placeholder="HH:mm:ss"
                            wire:model="dtRotasi.day_work.time.{{ $i }}.checkin_deadline_time"
                            class="form-control input-mask-time @error("dtRotasi.day_work.time.$i.checkin_deadline_time") is-invalid @enderror"
                            id="{{ $i }}.checkin_deadline_time"
                            data-inputmask="'mask': '99:99:99','inputFormat': 'HH:mm:ss'">
                        @error("dtRotasi.day_work.time.$i.checkin_deadline_time")
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12 col-md">
                    <div class="form-group mb-1">
                        <input placeholder="HH:mm:ss"
                            wire:model="dtRotasi.day_work.time.{{ $i }}.checkout_time"
                            class="form-control input-mask-time @error("dtRotasi.day_work.time.$i.checkout_time") is-invalid @enderror"
                            id="{{ $i }}.checkout_time"
                            data-inputmask="'mask': '99:99:99','inputFormat': 'HH:mm:ss'">
                        @error("dtRotasi.day_work.time.$i.checkout_time")
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12 col-md">
                    <div class="form-group mb-1">
                        <input placeholder="HH:mm:ss"
                            wire:model="dtRotasi.day_work.time.{{ $i }}.checkout_deadline_time"
                            class="form-control input-mask-time @error("dtRotasi.day_work.time.$i.checkout_deadline_time") is-invalid @enderror"
                            id="{{ $i }}.checkout_deadline_time"
                            data-inputmask="'mask': '99:99:99','inputFormat': 'HH:mm:ss'">
                        @error("dtRotasi.day_work.time.$i.checkout_deadline_time")
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        @endfor

        <div class="row mt-2">
            <div class="col">
                <button wire:click="addShift()" type="button" class="btn btn-success"><i class="fas fa-plus"></i>
                    Tambah Shift</button>
            </div>
        </div>


        <hr>
        <div class="row">
            <div class="col">
                <button type="submit" class="btn btn-primary">
                    <i class=" fas fa-check  mr-1"></i> Simpan Data
                </button>
                <a href="{{ route('jadwal-kerja.index') }}" class="btn btn-secondary">
                    Kembali <i class="fas fa-angle-right ml-1"></i>
                </a>
            </div>
        </div>

    </form>

    @include('schedule.atc.schedule_create_rotasi_atc');


</div>
