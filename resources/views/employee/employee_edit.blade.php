<div>
    <div class="loading-50" wire:loading wire:target="wireSubmit">
        <div class="loader"></div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title')
                <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    <a href="{{ route('karyawan.index') }}" class="btn btn-secondary">
                        Kembali <i class="fas fa-angle-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <form wire:submit.prevent="wireSubmit">

        <div class="row d-flex align-items-stretch">

            <div class="col-12">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Kode</label>
                            <input wire:model="dtForm.id" autofocus type="text"
                                class="form-control @error('dtForm.id') is-invalid @enderror">
                            @error('dtForm.id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Nomor</label>
                            <input wire:model="dtForm.number" type="text"
                                class="form-control @error('dtForm.number') is-invalid @enderror">
                            @error('dtForm.number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Nama</label>
                            <input wire:model="dtForm.name" type="text"
                                class="form-control @error('dtForm.name') is-invalid @enderror">
                            @error('dtForm.name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <label>Perusahaan</label>
                            <select wire:model="dtForm.master_organization_id"
                                class="form-control @error('dtForm.master_organization_id') is-invalid @enderror">
                                <option value="">-</option>
                                @foreach ($dtEdit['organization'] as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                @endforeach
                            </select>
                            @error('dtForm.master_organization_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Jabatan</label>
                            <select wire:model="dtForm.master_position_id"
                                class="form-control @error('dtForm.master_position_id') is-invalid @enderror">
                                <option value="">-</option>
                                @foreach ($dtEdit['position'] as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                @endforeach
                            </select>
                            @error('dtForm.master_position_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <label>Lokasi</label>
                            <select wire:model="dtForm.master_location_id"
                                class="form-control @error('dtForm.master_location_id') is-invalid @enderror">
                                <option value="">-</option>
                                @foreach ($dtEdit['location'] as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                @endforeach
                            </select>
                            @error('dtForm.master_location_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Fungsi</label>
                            <select wire:model="dtForm.master_function_id"
                                class="form-control @error('dtForm.master_function_id') is-invalid @enderror">
                                <option value="">-</option>
                                @foreach ($dtEdit['function'] as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                @endforeach
                            </select>
                            @error('dtForm.master_function_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                </div>

            </div>

            <div class="col-12">
                <div class="row">
                    <div class="col-md">
                        <div class="form-group" x-data>
                            <label>Username Login</label>
                            <input
                                wire:model="dtForm.username"
                                type="text"
                                class="form-control @error('dtForm.username') is-invalid @enderror"
                                x-on:keydown.space.prevent
                                x-on:input="$event.target.value = $event.target.value.replace(/\s/g, '')"
                            >
                            @error('dtForm.username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label>Password Login</label>
                            <input wire:model="dtForm.password" type="text" placeholder="isi jika ingin mengganti password"
                                class="form-control @error('dtForm.password') is-invalid @enderror">
                            @error('dtForm.password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Aktifasi</label>
                            <select wire:model="dtForm.status"
                                class="form-control @error('dtForm.status') is-invalid @enderror">
                                <option value="Aktif">Aktif</option>
                                <option value="Belum Aktif">Belum Aktif</option>
                                <option value="Tidak Aktif">Tidak Aktif</option>
                            </select>
                            @error('dtForm.status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    @if (Auth::user()->user_roles->id == 100)
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Role</label>
                                <select wire:model="dtForm.role"
                                    class="form-control @error('dtForm.role') is-invalid @enderror">
                                    @foreach ($dtEdit['roles'] as $item)
                                        <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                    @endforeach
                                </select>
                                @error('dtForm.role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    @endif

                </div>
            </div>

            <div class="col">
                <label>Pilih Jadwal Kerja :</label>
                @error('dtForm.master_schedule_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
                @error('multi_schedule')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
                <div class="table-responsive bg-white">
                    <table class="table mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Kode Jadwal</th>
                                <th>Nama Jadwal</th>
                                <th>Tanggal Berlaku </th>
                                <th>Tanggal Selesai <br>
                                    <small>(biarkan kosong jika masih berlaku)</small>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dtEdit['schedule'] as $item)
                            <tr>
                                <td>
                                    <input
                                        name="check-{{$item['id']}}"
                                        type="checkbox"
                                        wire:model="dtForm.master_schedule_id.{{$item['id']}}"
                                        wire:click="checkSchedule({{$item['id']}})"
                                        style="transform: scale(1.2);"
                                    >
                                </td>
                                <td>{{ $item['kode'] }}</td>
                                <td>{{ $item['name'] }}</td>
                                <td><input type="date" wire:model="dtForm.effective_at.{{$item['id']}}" class="form-control form-control-sm"></td>
                                <td><input type="date" wire:model="dtForm.expired_at.{{$item['id']}}" class="form-control form-control-sm"></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>


        </div>

        <hr>
        <div class="row">
            <div class="col">
                <button type="submit" class="btn btn-primary">
                    <i class=" fas fa-check  mr-1"></i> Simpan Data
                </button>
                <a href="{{ route('karyawan.index') }}" class="btn btn-secondary">
                    Kembali <i class="fas fa-angle-right ml-1"></i>
                </a>
            </div>
        </div>

    </form>

</div>
