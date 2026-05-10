@extends('components.app_layout', ['data' => $data])

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title', ['pass' => $data])
                <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    <a href="{{ route('rdp.laporan.aset-standar.pdf', request()->query()) }}" target="_blank" class="btn btn-primary">
                        <i class="fas fa-file-pdf fa-fw"></i> Cetak PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('rdp.laporan.aset-standar.index') }}">
                <div class="row align-items-end">
                    <div class="col-md-6">
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
                    <div class="col-md-6 text-md-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search fa-fw"></i> Tampilkan
                        </button>
                        <a href="{{ route('rdp.laporan.aset-standar.index') }}" class="btn btn-secondary">
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
                            <th>Cluster</th>
                            <th>Aset</th>
                            <th>Jenis</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-center">Satuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $item->rdp_master_clusters?->nama_cluster ?: '-' }}</td>
                                <td>{{ $item->rdp_master_asets?->perlengkapan ?: '-' }}</td>
                                <td>{{ $item->jenis ?: '-' }}</td>
                                <td class="text-center">{{ $item->jumlah ?: '-' }}</td>
                                <td class="text-center">{{ $item->satuan ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Data aset standar tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
