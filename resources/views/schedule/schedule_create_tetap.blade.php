<div>

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

    <form wire:submit.prevent="wireSubmitTetap">

        <div class="row">
            <div class="col col-md-4">

                <div class="form-group">
                    <label>Kode Jadwal</label>
                    <input wire:model="dtTetap.kode" autofocus type="text" class="form-control @error('dtTetap.kode') is-invalid @enderror">
                    @error('dtTetap.kode')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>
            <div class="col col-md-8">

                <div class="form-group">
                    <label>Nama Jadwal</label>
                    <input wire:model="dtTetap.name" autofocus type="text" class="form-control @error('dtTetap.name') is-invalid @enderror">
                    @error('dtTetap.name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-6 col-md-3">
                <div class="form-group">
                    <label>Jam Boleh Absen Masuk</label>
                    <input placeholder="HH:MM" wire:model="dtTetap.checkin_time" class="form-control input-mask-time
                        @error('dtTetap.checkin_time') is-invalid @enderror"
                        id="checkin_time"
                        data-inputmask="'alias': 'datetime','inputFormat': 'HH:MM'">
                    @error('dtTetap.checkin_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="form-group">
                    <label>Jam Mulai Kerja</label>
                    <input placeholder="HH:MM" wire:model="dtTetap.work_time" class="form-control input-mask-time
                        @error('dtTetap.work_time') is-invalid @enderror"
                        id="work_time"
                        data-inputmask="'alias': 'datetime','inputFormat': 'HH:MM'">
                    @error('dtTetap.work_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="form-group">
                    <label>Jam Batas Toleransi</label>
                    <input placeholder="HH:MM" wire:model="dtTetap.checkin_deadline_time" class="form-control input-mask-time
                        @error('dtTetap.checkin_deadline_time') is-invalid @enderror"
                        id="checkin_deadline_time"
                        data-inputmask="'alias': 'datetime','inputFormat': 'HH:MM'">
                    @error('dtTetap.checkin_deadline_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="form-group">
                    <label>Jam Pulang</label>
                    <input placeholder="HH:MM" wire:model="dtTetap.checkout_time" class="form-control input-mask-time
                        @error('dtTetap.checkout_time') is-invalid @enderror"
                        id="checkout_time"
                        data-inputmask="'alias': 'datetime','inputFormat': 'HH:MM'">
                    @error('dtTetap.checkout_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mt-3">
                <h5>Hari Kerja Regular</h5>
                @error('dtTetap.day_work.regular')
                    <p class="text-danger my-0">{{ $message }}</p>
                @enderror
                @foreach ($hariIndo as $key => $item)
                <div class="form-check mb-1">
                    <input wire:model="dtTetap.day_work.regular.{{ $key+1 }}" type="checkbox"
                           class="form-check-input regular"
                           value="{{ $key+1 }}"
                           id="regular-{{ $key+1 }}">
                    <label class="form-check-label" for="regular-{{ $key+1 }}" style="padding-top: 2px">
                        {{ $item }}
                    </label>
                </div>
                @endforeach
            </div>

            <div class="col col-md-4 mt-3">
                <h5>Hari Kerja Lembur</h5>
                @error('dtTetap.day_work.lembur')
                    <p class="text-danger my-0">{{ $message }}</p>
                @enderror

                @foreach ($hariIndo as $key => $item)
                <div class="form-check mb-1">
                    <input wire:model="dtTetap.day_work.lembur.{{ $key+1 }}" type="checkbox"
                           class="form-check-input lembur"
                           value="{{ $key+1 }}"
                           id="lembur-{{ $key+1 }}">
                    <label class="form-check-label" for="lembur-{{ $key+1 }}" style="padding-top: 2px">
                        {{ $item }}
                    </label>
                </div>
                @endforeach
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

    @include('schedule.atc.schedule_create_tetap_atc');
</div>
