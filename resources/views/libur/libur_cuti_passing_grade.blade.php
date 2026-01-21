<div>
    {{-- <div class="loading-50" wire:loading><div class="loader"></div></div> --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title')
                <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modalCreate">
                        <i class="fas fa-plus fa-fw"></i> Tambah Data Baru
                    </button>
                </div>
            </div>
        </div>
    </div>

     <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <form class="p-3 bg-light rounded" method="get">

                                <div class="row">
                                    <div class="col-12 col-md-2">
                                        <div class="form-group">
                                            <label>Bulan</label>
                                            <select name="month" class="form-control ex-filter">
                                                @foreach ($dt['indoMonthList'] as $key => $item)
                                                    <option value="{{ $key }}"
                                                        {{ $dt['month'] == $key ? 'selected' : '' }}>
                                                        {{ $item }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-2">
                                        <div class="form-group">
                                            <label>Tahun</label>
                                            <select name="year" class="form-control ex-filter">
                                                @for ($i = date('Y') + 1; $i >= date('Y') - 10; $i--)
                                                    <option value="{{ $i }}"
                                                        {{ $dt['year'] == $i ? 'selected' : '' }}>
                                                        {{ $i }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md">
                                        <div class="form-group">
                                            <label>Perusahaan</label>
                                            @php
                                                $selectedOrg = request('master_organization_id', 0);
                                            @endphp
                                            <select name="master_organization_id" class="form-control ex-filter">
                                                <option value="0" {{ $selectedOrg == 0 ? 'selected' : '' }}>Semua</option>
                                                @foreach ($dt['organization'] as $item)
                                                    <option value="{{ $item['id'] }}"
                                                        {{ (string)$selectedOrg === (string)$item['id'] ? 'selected' : '' }}>
                                                        {{ $item['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-12 col-md">
                                        <div class="form-group mb-0 pb-0">
                                            <label style="visibility: hidden">Action</label>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-7">
                                                <button type="submit" class="btn btn-info btn-block">Tampilkan Data</button>
                                            </div>
                                            <div class="col">
                                                <a href="{{ route('lembur.rekapBulanan') }}" type="button" class="btn btn-secondary btn-block">Reset</a>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row" wire:ignore>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h4 class="text-right">
                                {{ $dt['indoMonthList'][str_pad($dt['month'], 2, '0', STR_PAD_LEFT)] }}
                                {{ $dt['year'] }}
                            </h4>
                        </div>
                    </div>
                    <div class="table-responsive mt-2">
                        <table id="myTable" class="table table-bordered table-striped" style="width: 100%">
                            <thead>
                            <tr>
                                <th class="text-center" width="10">No</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Perusahaan</th>
                                <th class="text-center">Total Cuti</th>
                            </tr>
                            </thead>
                            <thead id="header-filter">
                                <tr>
                                    <th class="text-center"></th>
                                    <th class="text-center">
                                        <input type="text" class="form-control form-control-sm search-col-dt">
                                    </th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
    @include('libur.atc.libur_cuti_passing_grade_atc')
</div>
