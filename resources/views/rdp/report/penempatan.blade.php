@extends('components.app_layout', ['data' => $data])

@php
    $formatDate = fn ($value) => $value ? \Carbon\Carbon::parse($value)->format('d/m/Y') : '-';
@endphp

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title', ['pass' => $data])
                <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    <a href="{{ route('rdp.laporan.penempatan.pdf', request()->query()) }}" target="_blank" class="btn btn-primary">
                        <i class="fas fa-file-pdf fa-fw"></i> Cetak PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('rdp.laporan.penempatan.index') }}">
                <div class="row align-items-end">
                    <div class="col-md-5">
                        <div class="form-group mb-md-0">
                            <label>Cluster</label>
                            <select name="cluster_id" class="form-control">
                                <option value="">Semua Cluster</option>
                                @foreach ($clusters as $cluster)
                                    <option value="{{ $cluster->id }}" @selected((string) ($filters['cluster_id'] ?? '') === (string) $cluster->id)>
                                        {{ $cluster->nama_cluster }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-md-0">
                            <label>Status Rumah</label>
                            <select name="status_rumah" class="form-control">
                                <option value="">Semua</option>
                                @foreach ($statusRumahList as $status)
                                    <option value="{{ $status }}" @selected(($filters['status_rumah'] ?? '') === $status)>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search fa-fw"></i> Tampilkan
                        </button>
                        <a href="{{ route('rdp.laporan.penempatan.index') }}" class="btn btn-secondary">
                            <i class="fas fa-sync fa-fw"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" style="width:60px">No</th>
                            <th>Nama Karyawan</th>
                            <th>NOPek</th>
                            <th>Organisasi</th>
                            <th>Jabatan</th>
                            <th>Lokasi</th>
                            <th>Fungsi</th>
                            <th>Cluster</th>
                            <th>Blok</th>
                            <th>Tipe</th>
                            <th>Nomor</th>
                            <th>Tanggal Mulai</th>
                            <th>Nomor SK Mutasi</th>
                            <th>Tanggal SK Mutasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                            @php
                                $employee = $item->data_employees;
                                $rumah = $item->rdp_master_rumahs;
                            @endphp
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $employee?->name ?: '-' }}</td>
                                <td>{{ $employee?->number ?: '-' }}</td>
                                <td>{{ $employee?->master_organizations?->name ?: '-' }}</td>
                                <td>{{ $employee?->master_positions?->name ?: '-' }}</td>
                                <td>{{ $employee?->master_locations?->name ?: '-' }}</td>
                                <td>{{ $employee?->master_functions?->name ?: '-' }}</td>
                                <td>{{ $rumah?->rdp_master_clusters?->nama_cluster ?: '-' }}</td>
                                <td>{{ $rumah?->block ?: '-' }}</td>
                                <td>{{ $rumah?->tipe ?: '-' }}</td>
                                <td>{{ $rumah?->nomor ?: '-' }}</td>
                                <td>{{ $formatDate($item->tanggal_mulai) }}</td>
                                <td>{{ $item->nomor_sk_mutasi ?: '-' }}</td>
                                <td>{{ $formatDate($item->tanggal_sk_mutasi) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="text-center">Data penempatan RDP tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
