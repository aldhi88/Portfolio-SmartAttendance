<div class="table-responsive mt-2">
    <table id="myTableEmployee" class="table table-bordered table-striped" style="width: 100%">
        <thead>
            <tr>
                <th class="text-center" style="max-width: 30px">
                    <button class="btn btn-success btn-sm btnAddMember" id="btnAddMember" disabled="">
                        <i class="fas fa-plus"></i>
                    </button>
                </th>
                <th class="text-center">No</th>
                <th class="text-center">Nama</th>
                <th class="text-center">Perusahaan</th>
                <th class="text-center">Jabatan</th>
                <th class="text-center">Lokasi</th>
                <th class="text-center">Fungsi</th>
            </tr>
        </thead>

        <thead id="header-filter-employee">
            <tr>
                <th class="text-center">
                    <input type="checkbox" class="check-data-all-nonmember">
                </th>
                <th class="text-center"></th>
                <th class="text-center">
                    <input type="text" class="form-control form-control-sm text-center search-col-dt">
                </th>
                <th class="text-center">
                    <select name="" class="form-control form-control-sm search-col-dt">
                        <option value="">Semua</option>
                        @foreach ($dt['organization'] as $item)
                            <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                        @endforeach
                    </select>
                </th>
                <th class="text-center">
                    <select name="" class="form-control form-control-sm search-col-dt">
                        <option value="">Semua</option>
                        @foreach ($dt['position'] as $item)
                            <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                        @endforeach
                    </select>
                </th>
                <th class="text-center">
                    <select name="" class="form-control form-control-sm search-col-dt">
                        <option value="">Semua</option>
                        @foreach ($dt['location'] as $item)
                            <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                        @endforeach
                    </select>
                </th>
                <th class="text-center" style="min-width: 80px">
                    <select name="" class="form-control form-control-sm search-col-dt">
                        <option value="">Semua</option>
                        @foreach ($dt['function'] as $item)
                            <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                        @endforeach
                    </select>
                </th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
