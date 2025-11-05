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
                                <th class="text-center">ID User</th>
                                <th class="text-center">Nama Karyawan</th>
                                <th class="text-center">Koordinat</th>
                                <th class="text-center">Area</th>
                                <th class="text-center">Waktu</th>
                            </tr>
                            </thead>

                            <thead id="header-filter">
                                <tr>
                                    <th class="text-center"></th>
                                    <th class="text-center"><input type="text" class="form-control form-control-sm text-center search-col-dt"></th>
                                    <th class="text-center"><input type="text" class="form-control form-control-sm text-center search-col-dt"></th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                    <th class="text-center">
                                        <input type="date" class="form-control form-control-sm text-center search-col-dt">
                                    </th>

                                </tr>
                            </thead>


                            <tbody></tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
    @include('laporan.atc.log_gps_data_atc')

</div>
