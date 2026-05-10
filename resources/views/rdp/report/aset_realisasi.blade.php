@extends('components.app_layout', ['data' => $data])

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title', ['pass' => $data])
                <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    <a href="{{ route('rdp.laporan.aset-realisasi.pdf-semua') }}" target="_blank" class="btn btn-success">
                        <i class="fas fa-file-pdf fa-fw"></i> Cetak PDF Semua Unit
                    </a>
                    @if ($rumah)
                        <a href="{{ route('rdp.laporan.aset-realisasi.pdf', request()->query()) }}" target="_blank" class="btn btn-primary">
                            <i class="fas fa-file-pdf fa-fw"></i> Cetak PDF Unit Ini
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('rdp.laporan.aset-realisasi.index') }}">
                <div class="row align-items-end">
                    <div class="col-md-8">
                        <div class="form-group mb-md-0">
                            <label>Unit Rumah <span class="text-danger">*</span></label>
                            <select name="rumah_id" class="form-control">
                                <option value="">Pilih Unit Rumah</option>
                                @foreach ($rumahs as $rumahOption)
                                    <option value="{{ $rumahOption->id }}" @selected((string) ($filters['rumah_id'] ?? '') === (string) $rumahOption->id)>
                                        {{ \App\Repositories\RdpReportRepo::unitRumahLabel($rumahOption) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search fa-fw"></i> Tampilkan
                        </button>
                        <a href="{{ route('rdp.laporan.aset-realisasi.index') }}" class="btn btn-secondary">
                            <i class="fas fa-sync fa-fw"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if (!$rumah)
        <div class="alert alert-info">Silakan pilih unit rumah terlebih dahulu.</div>
    @else
        <div class="card">
            <div class="card-body">
                <h5>Informasi Unit Rumah</h5>
                <div class="row">
                    <div class="col-md-4"><strong>Cluster:</strong> {{ $rumah->rdp_master_clusters?->nama_cluster ?: '-' }}</div>
                    <div class="col-md-2"><strong>Blok:</strong> {{ $rumah->block ?: '-' }}</div>
                    <div class="col-md-2"><strong>Tipe:</strong> {{ $rumah->tipe ?: '-' }}</div>
                    <div class="col-md-2"><strong>Nomor:</strong> {{ $rumah->nomor ?: '-' }}</div>
                    <div class="col-md-2"><strong>Status:</strong> {{ $rumah->status ?: '-' }}</div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:60px">No</th>
                                <th>Aset</th>
                                <th>Jenis</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-center">Satuan</th>
                                <th class="text-center">Status</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $item->rdp_master_asets?->perlengkapan ?: '-' }}</td>
                                    <td>{{ $item->jenis ?: '-' }}</td>
                                    <td class="text-center">{{ $item->jumlah ?: '-' }}</td>
                                    <td class="text-center">{{ $item->satuan ?: '-' }}</td>
                                    <td class="text-center">{{ $item->status ?: '-' }}</td>
                                    <td>{{ $item->catatan ?: '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Data aset realisasi untuk unit rumah ini tidak ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection
