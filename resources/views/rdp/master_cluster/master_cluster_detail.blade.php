<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title', ['pass' => $data])
                <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    <a href="{{ route('rdp.master.cluster.edit', $cluster->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit fa-fw"></i> Edit Data
                    </a>
                    <a href="{{ route('rdp.master.cluster.index') }}" class="btn btn-secondary">
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
                    <div class="form-group">
                        <label>Nama Cluster</label>
                        <h5>{{ $cluster->nama_cluster }}</h5>
                    </div>

                    <h4 class="lead mt-4">Standar Aset Cluster</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center" width="10">No</th>
                                    <th>Aset / Perlengkapan</th>
                                    <th>Jenis</th>
                                    <th>Jumlah</th>
                                    <th>Satuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($cluster->rdp_master_cluster_master_asets as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $item->rdp_master_asets->perlengkapan }}</td>
                                        <td>{{ $item->jenis ?: '-' }}</td>
                                        <td>{{ $item->jumlah ?: '-' }}</td>
                                        <td>{{ $item->satuan ?: '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Belum ada standar aset.</td>
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
