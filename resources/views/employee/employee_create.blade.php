<div>
    <style>
        .schedule-scroll {
            max-height: 420px;
            overflow-y: scroll;
            scrollbar-gutter: stable;
            border: 1px solid #e9eef3;
        }

        .schedule-scroll::-webkit-scrollbar {
            width: 14px;
        }

        .schedule-scroll::-webkit-scrollbar-track {
            background: #f1f5f7;
            border-left: 1px solid #e2e8ef;
        }

        .schedule-scroll::-webkit-scrollbar-thumb {
            background: #9aa7b5;
            border: 3px solid #f1f5f7;
            border-radius: 8px;
        }

        .schedule-scroll::-webkit-scrollbar-thumb:hover {
            background: #7b8794;
        }

        .schedule-scroll {
            scrollbar-color: #9aa7b5 #f1f5f7;
            scrollbar-width: auto;
        }
    </style>
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
                                @foreach ($dtCreate['organization'] as $item)
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
                                @foreach ($dtCreate['position'] as $item)
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
                                @foreach ($dtCreate['location'] as $item)
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
                                @foreach ($dtCreate['function'] as $item)
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
                            <input wire:model="dtForm.username" type="text"
                                class="form-control @error('dtForm.username') is-invalid @enderror"
                                x-on:keydown.space.prevent
                                x-on:input="$event.target.value = $event.target.value.replace(/\s/g, '')">
                            @error('dtForm.username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label>Password Login</label>
                            <input wire:model="dtForm.password" type="text"
                                class="form-control @error('dtForm.password') is-invalid @enderror">
                            @error('dtForm.password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="row">
                    <div class="col-md">
                        <div class="form-group">
                            <label>Tanda Tangan</label>
                            <div class="custom-file">
                                <input wire:model="dtForm.ttd" type="file" class="custom-file-input @error('dtForm.ttd') is-invalid @enderror">
                                <label class="custom-file-label" for="customFile">{{ $pass['ttd'] }}</label>
                                @error('dtForm.ttd')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label>Paraf</label>
                            <div class="custom-file">
                                <input wire:model="dtForm.paraf" type="file" class="custom-file-input @error('dtForm.paraf') is-invalid @enderror">
                                <label class="custom-file-label" for="customFile">{{ $pass['paraf'] }}</label>
                                @error('dtForm.paraf')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <label>Pilih Jadwal Kerja :</label>
                @error('activedSchedules.id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
                @error('multi_schedule')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
                <div class="table-responsive bg-white schedule-scroll">
                    <table class="table mb-0">
                        <thead class="thead-light" style="position: sticky; top: 0; z-index: 2;">
                            <tr>
                                <th>#</th>
                                <th>Kode Jadwal</th>
                                <th>Nama Jadwal</th>
                                <th>Tanggal Berlaku </th>
                                <th>Tanggal Selesai <br>
                                    <small>(biarkan kosong jika masih berlaku)</small>
                                </th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dtCreate['schedule'] as $item)
                            <tr>
                                <td>
                                    <input
                                        type="checkbox"
                                        wire:click="toggleSchedule({{ $item['id'] }})"
                                        @checked(in_array($item['id'], $activedSchedules['id'] ?? []))
                                        style="transform: scale(1.2);"
                                    >
                                </td>
                                <td>{{ $item['kode'] }}</td>
                                <td>{{ $item['name'] }}</td>
                                <td>
                                    <input type="date"
                                        wire:model.live="activedSchedules.effective_at.{{ $item['id'] }}"
                                        class="form-control form-control-sm"
                                        min="{{ $activedSchedules['effective_at'][$item['id']] ?? '' }}"
                                        {{ in_array($item['id'], $activedSchedules['id'] ?? []) ? '' : 'readonly' }}>
                                </td>
                                <td>
                                    <input type="date"
                                        wire:model.live="activedSchedules.expired_at.{{ $item['id'] }}"
                                        class="form-control form-control-sm"
                                        min="{{ $activedSchedules['effective_at'][$item['id']] ?? '' }}"
                                        {{ in_array($item['id'], $activedSchedules['id'] ?? []) ? '' : 'readonly' }}>
                                </td>
                                <td>
                                    @if ($item['type'] === 'Bebas')
                                        <button @disabled(!$this->isReady($item['id'])) type="button"
                                            class="btn btn-success btn-sm" data-toggle="modal"
                                            data-target="#modalJamKerja{{ $item['id'] }}">
                                            Set Jam Kerja
                                        </button>
                                    @endif
                                </td>
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
    @include('employee.employee_create_jam_kerja')

</div>
