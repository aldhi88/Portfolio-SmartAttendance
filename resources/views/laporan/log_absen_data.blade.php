<div>
    <div class="row">
        <div class="col">

            <div class="card">
                <div class="card-body">

                    <div class="table-responsive mt-2">
                        <table id="myTable" class="table table-bordered table-striped" style="width: 100%">
                            <thead>
                            <tr>
                                <th class="text-center" width="10"></th>
                                <th class="text-center">ID Karyawan</th>
                                <th class="text-center">Nama Karyawan</th>
                                <th class="text-center">Mesin Absen</th>
                                <th class="text-center">Lokasi Absen</th>
                                <th class="text-center">Cara Absen</th>
                                <th class="text-center">Waktu Absen</th>
                            </tr>
                            </thead>

                            <thead id="header-filter">
                                <tr>
                                    <th class="text-center"></th>
                                    <th class="text-center"><input type="text" class="form-control form-control-sm text-center search-col-dt"></th>
                                    <th class="text-center"><input type="text" class="form-control form-control-sm text-center search-col-dt"></th>
                                    <th class="text-center"><input type="text" class="form-control form-control-sm text-center search-col-dt"></th>
                                    <th class="text-center"><input type="text" class="form-control form-control-sm text-center search-col-dt"></th>
                                    <th class="text-center"><input type="text" class="form-control form-control-sm text-center search-col-dt"></th>
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
    @include('laporan.atc.log_absen_data_atc')

</div>
