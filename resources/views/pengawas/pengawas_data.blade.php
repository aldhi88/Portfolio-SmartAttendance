<div>
    {{-- <div class="loading-50" wire:loading><div class="loader"></div></div> --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title')
                {{-- <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    <a class="btn btn-primary" href="{{ route('karyawan.create') }}">
                        <i class="fas fa-plus fa-fw"></i> Tambah Data Baru
                    </a>
                </div> --}}
            </div>
        </div>
    </div>

    <div class="row" wire:ignore>
        <div class="col">
            <div class="card">
                <div class="card-body">


                    <div class="row">
                        <div class="col-12">
                            @if (!Auth::user()->is_pengawas)
                                <form class="p-3 bg-light rounded" method="get">
                                    <div class="row">
                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <label>Nama Pengawas</label>
                                                <select name="pengawas" wire:model="dt.pengawas_selected_id" class="form-control">
                                                    <option value="">- Pilih Pengawas -</option>
                                                    @foreach ($dt['pengawas'] as $key=>$item)
                                                    <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group mb-0 pb-0">
                                                <label style="visibility: hidden">Action</label>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <button type="submit" class="btn btn-info btn-block">Tampilkan Data Anggota</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            @endif

                            @if (Auth::user()->is_pengawas)
                                <div class="bg-light rounded p-2">
                                    <label>Nama Pengawas</label>
                                    <h5>{{ $dt['pengawas_selected_name'] }}</h5>
                                </div>
                            @endif

                        </div>
                    </div>


                    @if (!Auth::user()->is_pengawas && !request()->has('pengawas'))
                        <div class="alert alert-info alert-dismissible fade show mt-5 text-center" role="alert">
                            <h2>
                                <i class="mdi mdi-alert-circle-outline mr-2"></i>
                            </h2>
                            <h5>
                                Silahkan Pilih data pengawas
                            </h5>
                        </div>
                    @else

                    <div class="mt-5">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#home" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                    <span class="d-none d-sm-block">Anggota Karyawan</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#profile" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                    <span class="d-none d-sm-block">Tambah Anggota</span>
                                </a>
                            </li>

                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content p-3 text-muted">
                            <div class="tab-pane active" id="home" role="tabpanel">
                                <p class="mb-0"> Semua data anggota dibawah pengawasan kamu. </p>
                                @include('pengawas.atc.member_table')
                            </div>
                            <div class="tab-pane" id="profile" role="tabpanel">
                                <p class="mb-0"> Pilih data karyawan yang akan ditambahkan menjadi anggota kamu. </p>
                                @include('pengawas.atc.nonmember_table')
                            </div>
                        </div>
                    </div>

                    @endif



                </div>
            </div>

        </div>
    </div>
    @include('pengawas.atc.pengawas_data_atc')
    {{-- @include('employee.atc.modal_confirm') --}}
</div>
