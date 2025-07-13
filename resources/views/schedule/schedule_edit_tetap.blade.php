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
            <div class="col-12 col-md">
                <div class="form-group">
                    <label>Checkin Start</label>
                    <input placeholder="HH:mm:ss" wire:model="dtTetap.day_work.time.checkin_time" class="form-control input-mask-time
                        @error('dtTetap.day_work.time.checkin_time') is-invalid @enderror"
                        id="checkin_time"
                        data-inputmask="'mask': '99:99:99','inputFormat': 'HH:mm:ss'">
                    @error('dtTetap.day_work.time.checkin_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-12 col-md">
                <div class="form-group">
                    <label>Checkin Ontime</label>
                    <input placeholder="HH:mm:ss" wire:model="dtTetap.day_work.time.work_time" class="form-control input-mask-time
                        @error('dtTetap.day_work.time.work_time') is-invalid @enderror"
                        id="work_time"
                        data-inputmask="'mask': '99:99:99','inputFormat': 'HH:mm:ss'">
                    @error('dtTetap.day_work.time.work_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-12 col-md">
                <div class="form-group">
                    <label>Check-In End</label>
                    <input placeholder="HH:mm:ss" wire:model="dtTetap.day_work.time.checkin_deadline_time" class="form-control input-mask-time
                        @error('dtTetap.day_work.time.checkin_deadline_time') is-invalid @enderror"
                        id="checkin_deadline_time"
                        data-inputmask="'mask': '99:99:99','inputFormat': 'HH:mm:ss'">
                    @error('dtTetap.day_work.time.checkin_deadline_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-12 col-md">
                <div class="form-group">
                    <label>Checkout Start</label>
                    <input placeholder="HH:mm:ss" wire:model="dtTetap.day_work.time.checkout_time" class="form-control input-mask-time
                        @error('dtTetap.day_work.time.checkout_time') is-invalid @enderror"
                        id="checkout_time"
                        data-inputmask="'mask': '99:99:99','inputFormat': 'HH:mm:ss'">
                    @error('dtTetap.day_work.time.checkout_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-12 col-md">
                <div class="form-group">
                    <label>Checkout End</label>
                    <input placeholder="HH:mm:ss" wire:model="dtTetap.day_work.time.checkout_deadline_time" class="form-control input-mask-time
                        @error('dtTetap.day_work.time.checkout_deadline_time') is-invalid @enderror"
                        id="checkout_deadline_time"
                        data-inputmask="'mask': '99:99:99','inputFormat': 'HH:mm:ss'">
                    @error('dtTetap.day_work.time.checkout_deadline_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mt-3">
                <h5>Hari Kerja</h5>
                @error('dtTetap.day_work.day')
                    <p class="text-danger my-0">{{ $message }}</p>
                @enderror
                @foreach ($hariIndo as $key => $item)
                <div class="form-check mb-1">
                    <input wire:model="dtTetap.day_work.day.{{ $key }}" type="checkbox"
                        class="form-check-input regular"
                        {{-- {{ in_array($key, $dtTetap['day_work']['day']) ? 'checked' : null }} --}}
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

    @include('schedule.atc.schedule_edit_tetap_atc')

</div>
