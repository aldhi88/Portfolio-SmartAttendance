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
                                placeholder="isi jika ingin mengganti password"
                                class="form-control @error('dtForm.password') is-invalid @enderror">
                            @error('dtForm.password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Status</label>
                            <select wire:model="dtForm.status"
                                class="form-control @error('dtForm.status') is-invalid @enderror">
                                <option value="Aktif">Aktif</option>
                                <option value="Tidak Aktif">Tidak Aktif</option>
                            </select>
                            @error('dtForm.status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- @if (Auth::user()->user_roles->id == 100) --}}
                    @if (in_array(Auth::user()->user_roles->id, [100]))
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
            <div class="col-12">
                <div class="row">
                    <div class="col-md">
                        <div class="form-group">
                            <label>Tanda Tangan</label>
                            <div class="custom-file">
                                <input wire:model="dtForm.ttd" type="file" class="custom-file-input @error('dtForm.ttd') is-invalid @enderror">
                                <input type="hidden" wire:model="dtForm.ttd_old">
                                <label class="custom-file-label" for="customFile">{{ $pass['ttd'] }}</label>
                                @error('dtForm.ttd')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if (!empty($dtForm['ttd_old']))
                                <img src="{{ asset('storage/employees/ttd/' . $dtForm['ttd_old']) }}" alt="" class="img-fluid mt-2" width="100">
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label>Paraf</label>
                            <div class="custom-file">
                                <input wire:model="dtForm.paraf" type="file" class="custom-file-input @error('dtForm.paraf') is-invalid @enderror">
                                <input type="hidden" wire:model="dtForm.paraf_old">
                                <label class="custom-file-label" for="customFile">{{ $pass['paraf'] }}</label>
                            </div>
                            @error('dtForm.paraf')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if (!empty($dtForm['paraf_old']))
                            <img src="{{ asset('storage/employees/paraf/' . $dtForm['paraf_old']) }}" alt="" class="img-fluid mt-2" width="100">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 text-right my-3">
                <button type="submit" class="btn btn-primary">
                    <i class=" fas fa-check  mr-1"></i> Simpan Data
                </button>
                <a href="{{ route('karyawan.index') }}" class="btn btn-secondary">
                    Kembali <i class="fas fa-angle-right ml-1"></i>
                </a>
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
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dtEdit['schedule'] as $item)
                                <tr>
                                    <td>
                                        <input type="checkbox" wire:click="toggleSchedule({{ $item['id'] }})"
                                            @checked(in_array($item['id'], $activedSchedules['id'] ?? [])) style="transform: scale(1.2);">

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
        <div class="row pb-5">
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
    @include('employee.employee_edit_jam_kerja')
    @include('employee.atc.employee_edit_atc')

</div>
