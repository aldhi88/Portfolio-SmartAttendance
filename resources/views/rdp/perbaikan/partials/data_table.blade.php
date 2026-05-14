@php
    $blockOptions = \App\Repositories\RdpMasterRumahRepo::getFilterBlocks();
    $tipeOptions = \App\Repositories\RdpMasterRumahRepo::getFilterTipes();
    $vendorOptions = \App\Repositories\RdpMasterVendorRepo::getFilterNames();
    $positionOptions = \App\Repositories\MasterPositionRepo::getFilterNames();
@endphp
<div class="row" wire:ignore>
    <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive mt-2">
                    <table id="myTable" class="table table-bordered table-striped" style="width: 100%">
                        <thead>
                            <tr>
                                <th class="text-center"></th>
                                <th class="text-center">No</th>
                                <th class="text-center">Nama Karyawan</th>
                                <th class="text-center">NOPek</th>
                                <th class="text-center">Jabatan</th>
                                <th class="text-center">Blok</th>
                                <th class="text-center">Tipe</th>
                                <th class="text-center">Nomor</th>
                                <th class="text-center">Vendor</th>
                                <th class="text-center">Item</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">SPK</th>
                            </tr>
                        </thead>
                        <thead id="header-filter">
                            <tr>
                                <th></th>
                                <th></th>
                                <th><input type="text" class="form-control form-control-sm search-col-dt"></th>
                                <th><input type="text" class="form-control form-control-sm search-col-dt"></th>
                                <th>
                                    <select class="form-control form-control-sm search-col-dt">
                                        <option value="">Semua</option>
                                        @foreach ($positionOptions as $option)
                                            <option value="{{ $option }}">{{ $option }}</option>
                                        @endforeach
                                    </select>
                                </th>
                                <th>
                                    <select class="form-control form-control-sm search-col-dt">
                                        <option value="">Semua</option>
                                        @foreach ($blockOptions as $option)
                                            <option value="{{ $option }}">{{ $option }}</option>
                                        @endforeach
                                    </select>
                                </th>
                                <th>
                                    <select class="form-control form-control-sm search-col-dt">
                                        <option value="">Semua</option>
                                        @foreach ($tipeOptions as $option)
                                            <option value="{{ $option }}">{{ $option }}</option>
                                        @endforeach
                                    </select>
                                </th>
                                <th><input type="text" class="form-control form-control-sm search-col-dt"></th>
                                <th>
                                    <select class="form-control form-control-sm search-col-dt">
                                        <option value="">Semua</option>
                                        @foreach ($vendorOptions as $option)
                                            <option value="{{ $option }}">{{ $option }}</option>
                                        @endforeach
                                    </select>
                                </th>
                                <th></th>
                                <th>
                                    <select class="form-control form-control-sm search-col-dt">
                                        <option value="">Semua</option>
                                        @foreach ($statusList as $status)
                                            <option value="{{ $status }}">{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </th>
                                <th><input type="text" class="form-control form-control-sm search-col-dt"></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
