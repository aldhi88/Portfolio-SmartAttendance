<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title', ['pass' => $data])
                <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    <a href="{{ route('rdp.master.rumah.edit', $rumah->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit fa-fw"></i> Edit Data
                    </a>
                    <a href="{{ route('rdp.master.rumah.index') }}" class="btn btn-secondary">
                        Kembali <i class="fas fa-angle-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cluster</label>
                                <h5>{{ $rumah->rdp_master_clusters?->nama_cluster ?: '-' }}</h5>
                            </div>
                            <div class="form-group">
                                <label>Block</label>
                                <h5>{{ $rumah->block ?: '-' }}</h5>
                            </div>
                            <div class="form-group">
                                <label>Tipe</label>
                                <h5>{{ $rumah->tipe ?: '-' }}</h5>
                            </div>
                            <div class="form-group">
                                <label>Nomor</label>
                                <h5>{{ $rumah->nomor }}</h5>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status</label>
                                <h5>{{ $rumah->status }}</h5>
                            </div>
                            <div class="form-group">
                                <label>Tanggal Dibuat</label>
                                <h5>{{ $rumah->created_at?->format('d/m/Y H:i') }}</h5>
                            </div>
                            <div class="form-group">
                                <label>Tanggal Terakhir Diubah</label>
                                <h5>{{ $rumah->updated_at?->format('d/m/Y H:i') }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h4 class="lead">Aset Rumah</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center" width="10">No</th>
                                    <th>Aset / Perlengkapan</th>
                                    <th>Jenis</th>
                                    <th>Jumlah</th>
                                    <th>Satuan</th>
                                    <th>Status</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rumah->rdp_master_rumah_asets as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $item->rdp_master_asets?->perlengkapan ?: '-' }}</td>
                                        <td>{{ $item->jenis ?: '-' }}</td>
                                        <td>{{ $item->jumlah ?: '-' }}</td>
                                        <td>{{ $item->satuan ?: '-' }}</td>
                                        <td>{{ $item->status }}</td>
                                        <td>{{ $item->catatan ?: '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Belum ada aset rumah.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
