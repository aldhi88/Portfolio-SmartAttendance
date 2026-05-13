@extends('components.app_layout', ['data' => $data])

@php
    $indexRoute = 'rdp.laporan.' . $module . '.index';
    $pdfRoute = 'rdp.laporan.' . $module . '.pdf';
    $routeParams = ['variant' => $filters['variant']];
    $rowCount = $report['rows']->count();
@endphp

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title', ['pass' => $data])
                <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    <a href="{{ route($pdfRoute, array_merge($routeParams, request()->query())) }}" target="_blank" class="btn btn-primary">
                        <i class="fas fa-file-pdf fa-fw"></i> Cetak PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route($indexRoute, $routeParams) }}">
                <div class="row align-items-end">
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="form-group mb-3">
                            <label>Tanggal Dari</label>
                            <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="form-group mb-3">
                            <label>Tanggal Sampai</label>
                            <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="form-group mb-3">
                            <label>Cluster</label>
                            <select name="cluster_id" class="form-control">
                                <option value="">Semua</option>
                                @foreach ($clusters as $cluster)
                                    <option value="{{ $cluster->id }}" @selected((string) ($filters['cluster_id'] ?? '') === (string) $cluster->id)>
                                        {{ $cluster->nama_cluster }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @if (count($statuses) > 0)
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <div class="form-group mb-3">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="">Semua</option>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif

                    @if (in_array($module, ['penempatan', 'aset']))
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <div class="form-group mb-3">
                                <label>Status Rumah</label>
                                <select name="status_rumah" class="form-control">
                                    <option value="">Semua</option>
                                    @foreach ($statusRumahList as $statusRumah)
                                        <option value="{{ $statusRumah }}" @selected(($filters['status_rumah'] ?? '') === $statusRumah)>{{ $statusRumah }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif

                    @if ($module === 'aset')
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <div class="form-group mb-3">
                                <label>Unit Rumah</label>
                                <select name="rumah_id" class="form-control">
                                    <option value="">Semua</option>
                                    @foreach ($rumahs as $rumah)
                                        <option value="{{ $rumah->id }}" @selected((string) ($filters['rumah_id'] ?? '') === (string) $rumah->id)>
                                            {{ \App\Repositories\RdpReportRepo::unitRumahLabel($rumah) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <div class="form-group mb-3">
                                <label>Aset</label>
                                <select name="aset_id" class="form-control">
                                    <option value="">Semua</option>
                                    @foreach ($asets as $aset)
                                        <option value="{{ $aset->id }}" @selected((string) ($filters['aset_id'] ?? '') === (string) $aset->id)>
                                            {{ $aset->perlengkapan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <div class="form-group mb-3">
                                <label>Status Aset</label>
                                <select name="status_aset" class="form-control">
                                    <option value="">Semua</option>
                                    @foreach ($statusAsetList as $statusAset)
                                        <option value="{{ $statusAset }}" @selected(($filters['status_aset'] ?? '') === $statusAset)>{{ $statusAset }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif

                    @if (in_array($module, ['perbaikan', 'pengadaan']))
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <div class="form-group mb-3">
                                <label>Vendor</label>
                                <select name="vendor_id" class="form-control">
                                    <option value="">Semua</option>
                                    @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor->id }}" @selected((string) ($filters['vendor_id'] ?? '') === (string) $vendor->id)>
                                            {{ $vendor->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-end align-items-stretch align-items-sm-center">
                            <button type="submit" class="btn btn-primary mb-2 mb-sm-0 mr-sm-2">
                                <i class="fas fa-search fa-fw"></i> Tampilkan
                            </button>
                            <a href="{{ route($indexRoute, $routeParams) }}" class="btn btn-secondary">
                                <i class="fas fa-sync fa-fw"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h5 class="mb-1">Preview Laporan</h5>
                    <div class="text-muted">{{ $report['title'] }}</div>
                </div>
                <span class="badge badge-soft-primary px-3 py-2">{{ $rowCount }} baris</span>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" style="width:60px">No</th>
                            @foreach ($report['columns'] as $column)
                                <th class="{{ ($column['align'] ?? '') === 'center' ? 'text-center' : '' }}">{{ $column['label'] }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($report['rows'] as $row)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                @foreach ($report['columns'] as $column)
                                    <td class="{{ ($column['align'] ?? '') === 'center' ? 'text-center' : '' }}">
                                        {{ $row[$column['key']] ?? '-' }}
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($report['columns']) + 1 }}" class="text-center">Data laporan tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
