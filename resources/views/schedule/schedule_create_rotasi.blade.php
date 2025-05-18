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

    <form wire:submit.prevent="wireSubmitRotasi">

        <div class="row">

            <div class="col col-md-4">

                <div class="form-group">
                    <label>Kode Jadwal</label>
                    <input wire:model="dtRotasi.kode" autofocus type="text" class="form-control @error('dtRotasi.kode') is-invalid @enderror">
                    @error('dtRotasi.kode')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>
            <div class="col col-md-8">

                <div class="form-group">
                    <label>Nama Jadwal</label>
                    <input wire:model="dtRotasi.name" autofocus type="text" class="form-control @error('dtRotasi.name') is-invalid @enderror">
                    @error('dtRotasi.name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-6 col-md-3">
                <div class="form-group">
                    <label>Tgl Mulai Rotasi Pagi</label>
                    <input wire:model="dtRotasi.day_work.start_date" type="date" class="form-control @error('dtRotasi.day_work.start_date') is-invalid @enderror">
                    @error('dtRotasi.day_work.start_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="form-group">
                    <label>Jumlah Hari Kerja</label>
                    <input wire:model="dtRotasi.day_work.work_day" type="number" class="form-control @error('dtRotasi.day_work.work_day') is-invalid @enderror">
                    @error('dtRotasi.day_work.work_day')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="form-group">
                    <label>Jumlah Hari Off</label>
                    <input wire:model="dtRotasi.day_work.off_day" type="number" class="form-control @error('dtRotasi.day_work.off_day') is-invalid @enderror">
                    @error('dtRotasi.day_work.off_day')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-6 col-md-3">
                <div class="form-group">
                    <label>Jam Boleh Check-In (Pagi)</label>
                    <input placeholder="HH:MM" wire:model="dtRotasi.checkin_time" class="form-control input-mask-time
                        @error('dtRotasi.checkin_time') is-invalid @enderror"
                        id="checkin_time"
                        data-inputmask="'alias': 'datetime','inputFormat': 'HH:MM'">
                    @error('dtRotasi.checkin_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="form-group">
                    <label>Jam Akhir Check-In Ontime  (Pagi)</label>
                    <input placeholder="HH:MM" wire:model="dtRotasi.work_time" class="form-control input-mask-time
                        @error('dtRotasi.work_time') is-invalid @enderror"
                        id="work_time"
                        data-inputmask="'alias': 'datetime','inputFormat': 'HH:MM'">
                    @error('dtRotasi.work_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="form-group">
                    <label>Jam Akhir Check-In  (Pagi)</label>
                    <input placeholder="HH:MM" wire:model="dtRotasi.checkin_deadline_time" class="form-control input-mask-time
                        @error('dtRotasi.checkin_deadline_time') is-invalid @enderror"
                        id="checkin_deadline_time"
                        data-inputmask="'alias': 'datetime','inputFormat': 'HH:MM'">
                    @error('dtRotasi.checkin_deadline_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="form-group">
                    <label>Jam Boleh Checkout  (Pagi)</label>
                    <input placeholder="HH:MM" wire:model="dtRotasi.checkout_time" class="form-control input-mask-time
                        @error('dtRotasi.checkout_time') is-invalid @enderror"
                        id="checkout_time"
                        data-inputmask="'alias': 'datetime','inputFormat': 'HH:MM'">
                    @error('dtRotasi.checkout_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6 col-md-3">
                <div class="form-group">
                    <label>Jam Boleh Check-In (Sore)</label>
                    <input placeholder="HH:MM" wire:model="dtRotasi.day_work.rotasi.sore.checkin_time" class="form-control input-mask-time-sore
                        @error('dtRotasi.day_work.rotasi.sore.checkin_time') is-invalid @enderror" id="checkin_time"
                        data-inputmask="'alias': 'datetime','inputFormat': 'HH:MM'">
                    @error('dtRotasi.day_work.rotasi.sore.checkin_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="form-group">
                    <label>Jam Akhir Check-In Ontime  (Sore)</label>
                    <input placeholder="HH:MM" wire:model="dtRotasi.day_work.rotasi.sore.work_time" class="form-control input-mask-time-sore
                        @error('dtRotasi.day_work.rotasi.sore.work_time') is-invalid @enderror" id="work_time"
                        data-inputmask="'alias': 'datetime','inputFormat': 'HH:MM'">
                    @error('dtRotasi.day_work.rotasi.sore.work_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="form-group">
                    <label>Jam Akhir Check-In  (Sore)</label>
                    <input placeholder="HH:MM" wire:model="dtRotasi.day_work.rotasi.sore.checkin_deadline_time" class="form-control input-mask-time-sore
                        @error('dtRotasi.day_work.rotasi.sore.checkin_deadline_time') is-invalid @enderror" id="checkin_deadline_time"
                        data-inputmask="'alias': 'datetime','inputFormat': 'HH:MM'">
                    @error('dtRotasi.day_work.rotasi.sore.checkin_deadline_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="form-group">
                    <label>Jam Boleh Checkout  (Sore)</label>
                    <input placeholder="HH:MM" wire:model="dtRotasi.day_work.rotasi.sore.checkout_time" class="form-control input-mask-time-sore
                        @error('dtRotasi.day_work.rotasi.sore.checkout_time') is-invalid @enderror" id="checkout_time"
                        data-inputmask="'alias': 'datetime','inputFormat': 'HH:MM'">
                    @error('dtRotasi.day_work.rotasi.sore.checkout_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6 col-md-3">
                <div class="form-group">
                    <label>Jam Boleh Check-In (Malam)</label>
                    <input placeholder="HH:MM" wire:model="dtRotasi.day_work.rotasi.malam.checkin_time" class="form-control input-mask-time-malam
                        @error('dtRotasi.day_work.rotasi.malam.checkin_time') is-invalid @enderror" id="checkin_time"
                        data-inputmask="'alias': 'datetime','inputFormat': 'HH:MM'">
                    @error('dtRotasi.day_work.rotasi.malam.checkin_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="form-group">
                    <label>Jam Akhir Check-In Ontime  (Malam)</label>
                    <input placeholder="HH:MM" wire:model="dtRotasi.day_work.rotasi.malam.work_time" class="form-control input-mask-time-malam
                        @error('dtRotasi.day_work.rotasi.malam.work_time') is-invalid @enderror" id="work_time"
                        data-inputmask="'alias': 'datetime','inputFormat': 'HH:MM'">
                    @error('dtRotasi.day_work.rotasi.malam.work_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="form-group">
                    <label>Jam Akhir Check-In  (Malam)</label>
                    <input placeholder="HH:MM" wire:model="dtRotasi.day_work.rotasi.malam.checkin_deadline_time" class="form-control input-mask-time-malam
                        @error('dtRotasi.day_work.rotasi.malam.checkin_deadline_time') is-invalid @enderror" id="checkin_deadline_time"
                        data-inputmask="'alias': 'datetime','inputFormat': 'HH:MM'">
                    @error('dtRotasi.day_work.rotasi.malam.checkin_deadline_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="form-group">
                    <label>Jam Boleh Checkout  (Malam)</label>
                    <input placeholder="HH:MM" wire:model="dtRotasi.day_work.rotasi.malam.checkout_time" class="form-control input-mask-time-malam
                        @error('dtRotasi.day_work.rotasi.malam.checkout_time') is-invalid @enderror" id="checkout_time"
                        data-inputmask="'alias': 'datetime','inputFormat': 'HH:MM'">
                    @error('dtRotasi.day_work.rotasi.malam.checkout_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
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
