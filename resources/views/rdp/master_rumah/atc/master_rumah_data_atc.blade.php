@section('style')
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('script')
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
@endsection

@push('push-script')
<script>
    var dtTable = $('#myTable').DataTable({
        processing: true,serverSide: true,pageLength: 25,dom: 'lrtip',
        order: [[5, 'asc']],
        columnDefs: [
            { className: 'text-left', targets: [3, 4, 5, 6] },
            { className: 'px-0', targets: [1] },
            { className: 'text-center', targets: ['_all'] },
        ],
        ajax: '{{ route("rdp.master.rumah.indexDT") }}',
        columns: [
            {
                data: null, name: 'created_at', orderable: false, searchable: false,
                render: function (data, type, row, meta) {
                    const usedCount = Number(data.rdp_karyawan_masuks_count || 0) + Number(data.rdp_karyawan_keluars_count || 0);
                    if (usedCount > 0) {
                        return `<input class="data-check" type="checkbox" value="${data.id}" disabled title="Unit rumah sudah dipakai proses RDP">`;
                    }

                    return '<input class="data-check" type="checkbox" value="'+data.id+'">';
                }
            },
            {
                data: null, name: 'created_at', orderable: false, searchable: false,
                render: function(data, type, row) {
                    const label = [data.block, data.tipe, data.nomor].filter(Boolean).join(' ');
                    const usedCount = Number(data.rdp_karyawan_masuks_count || 0) + Number(data.rdp_karyawan_keluars_count || 0);
                    const dtJson = {
                        msg: `Apakah anda yakin menghapus unit rumah ${label}?`,
                        info: usedCount > 0 ? `Unit rumah ini sudah dipakai oleh ${usedCount} proses RDP dan tidak bisa dihapus.` : '',
                        id: data.id
                    };
                    const deleteMenu = usedCount > 0
                        ? `<a class="dropdown-item text-muted" href="javascript:void(0);">
                                <i class="fas fa-ban fa-fw"></i> Tidak Bisa Dihapus
                            </a>`
                        : `<a data-json='${JSON.stringify(dtJson)}' class="dropdown-item text-danger delete"
                                data-toggle="modal" data-target="#modalConfirmDelete"
                                data-dispatch="wireDelete()"
                                href="javascript:void(0);">
                                <i class="fas fa-trash-alt fa-fw"></i> Hapus Data
                                </a>`;

                    return `
                        <div class="btn-group">
                            <a href="javascript:void(0)" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                                <i class="mdi mdi-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu">
                                <a href="{{ url('rdp/master/rumah/detail') }}/${data.id}" class="dropdown-item">
                                    <i class="fas fa-eye fa-fw"></i> Detail Data
                                </a>
                                <a href="{{ url('rdp/master/rumah/edit') }}/${data.id}" class="dropdown-item">
                                    <i class="fas fa-edit fa-fw"></i> Edit Data
                                </a>
                                ${deleteMenu}
                            </div>
                        </div>
                    `;
                }
            },
            {
                data: null, name: 'DT_RowIndex', orderable: false, searchable: false,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'block', name: 'block', orderable: true, searchable:true },
            { data: 'tipe', name: 'tipe', orderable: true, searchable:true },
            { data: 'nomor', name: 'nomor', orderable: true, searchable:true },
            {
                data: null, name: 'rdp_master_clusters.nama_cluster', orderable: true, searchable:true,
                render: function(data, type, row) {
                    return data.rdp_master_clusters?.nama_cluster || '-';
                }
            },
            { data: 'status', name: 'status', orderable: true, searchable:true },
            {
                data: null, name: 'rdp_master_rumah_asets_count', orderable: true, searchable:false,
                render: function(data, type, row) {
                    return data.rdp_master_rumah_asets_count + ' aset';
                }
            },
        ],
        initComplete: function(settings){
            table = settings.oInstance.api();
            initSearchCol(table,'#header-filter','search-col-dt');

            $('#header-filter').on('change', '.search-status-dt', function () {
                const colIndex = $(this).parent().index();
                table.column(colIndex).search(this.value).draw(false);
            });
        }
    });

    $(document).on('change', '.check-data-all', function () {
        let isChecked = $(this).is(':checked');
        table.rows({ page: 'current' }).nodes().each(function (row) {
            $(row).find('.data-check:not(:disabled)').prop('checked', isChecked);
        });
        $('#btnDeleteSelected').prop('disabled', !isChecked);
    });

    $(document).on('change', '.data-check', function () {
        let total = $('.data-check:visible').length;
        let checked = $('.data-check:visible:checked').length;
        $('.check-data-all').prop('checked', total === checked);
        $('#btnDeleteSelected').prop('disabled', checked === 0);
    });

    dtTable.on('draw.dt', function () {
        $('.check-data-all').prop('checked', false);
        $('#btnDeleteSelected').prop('disabled', true);
    });

    $(document).on('click', '#btnDeleteSelected', function () {
        let ids = $('.data-check:checked').map(function () {
            return $(this).val();
        }).get();

        if (ids.length === 0) {
            alert('Pilih minimal 1 data untuk dihapus.');
            return;
        }

        Livewire.dispatch('setDeleteMultipleId', { ids: ids });

        $('#modalConfirmDeleteMultiple')
            .find('#submitModalConfirmDeleteMultiple')
            .attr('wire:click', 'deleteMultiple()')
            .prop('disabled', false)
            .text('Hapus Data');
        $('#modalConfirmDeleteMultiple')
            .find('.msg')
            .text(`Apakah anda yakin menghapus ${ids.length} unit rumah yang dipilih?`);
        $('#modalConfirmDeleteMultiple')
            .find('.delete-info')
            .text('');
        $('#modalConfirmDeleteMultiple').modal('show');

    });
</script>
@endpush
