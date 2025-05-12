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
                    <label>Jam Boleh Absen Masuk</label>
                    <input wire:model="dtRotasi.checkin_time" type="time" class="form-control @error('dtRotasi.checkin_time') is-invalid @enderror">
                    @error('dtRotasi.checkin_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="form-group">
                    <label>Jam Mulai Kerja</label>
                    <input wire:model="dtRotasi.work_time" type="time" class="form-control @error('dtRotasi.work_time') is-invalid @enderror">
                    @error('dtRotasi.work_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="form-group">
                    <label>Jam Batas Toleransi</label>
                    <input wire:model="dtRotasi.checkin_deadline_time" type="time" class="form-control @error('dtRotasi.checkin_deadline_time') is-invalid @enderror">
                    @error('dtRotasi.checkin_deadline_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="form-group">
                    <label>Jam Pulang</label>
                    <input wire:model="dtRotasi.checkout_time" type="time" class="form-control @error('dtRotasi.checkout_time') is-invalid @enderror">
                    @error('dtRotasi.checkout_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6 col-md-3">
                <div class="form-group">
                    <label>Tanggal Mulai Rotasi</label>
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




</div>
