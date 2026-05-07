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
        order: [[3, 'asc']],
        columnDefs: [
            { className: 'text-left', targets: [3] },
            { className: 'px-0', targets: [1] },
            { className: 'text-center', targets: ['_all'] },
        ],
        ajax: '{{ route("rdp.master.aset.indexDT") }}',
        columns: [
            {
                data: null, name: 'created_at', orderable: false, searchable: false,
                render: function (data, type, row, meta) {
                    return `
                        <input
                            class="data-check"
                            type="checkbox"
                            value="${data.id}"
                            data-used="${data.rdp_master_cluster_master_asets_count}"
                            data-rumah-used="${data.rdp_master_rumah_asets_count}"
                        >
                    `;
                }
            },
            {
                data: null, name: 'created_at', orderable: false, searchable: false,
                render: function(data, type, row) {
                    const usedCount = data.rdp_master_cluster_master_asets_count;
                    const rumahCount = data.rdp_master_rumah_asets_count;
                    const dtJson = {
                        msg: `Apakah anda yakin menghapus data ${data.perlengkapan}?`,
                        info: rumahCount > 0
                            ? `Data ini sedang dipakai oleh ${rumahCount} aset rumah dan tidak bisa dihapus.`
                            : usedCount > 0
                            ? `Data ini sedang terpakai di ${usedCount} detail cluster. Jika dihapus, data aset ini juga akan terhapus dari detail cluster tersebut.`
                            : '',
                        blockDelete: rumahCount > 0,
                        id: data.id
                    };

                    return `
                        <div class="btn-group">
                            <a href="javascript:void(0)" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                                <i class="mdi mdi-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu">
                                <a data-id="${data.id}" data-toggle="modal" data-target="#modalEdit" class="dropdown-item" href="javascript:void(0);">
                                    <i class="fas fa-edit fa-fw"></i> Edit Data
                                </a>
                                <a data-json='${JSON.stringify(dtJson)}' class="dropdown-item text-danger delete"
                                data-toggle="modal" data-target="#modalConfirmDelete"
                                data-dispatch="wireDelete()"
                                href="javascript:void(0);">
                                <i class="fas fa-trash-alt fa-fw"></i> Hapus Data
                                </a>
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
            { data: 'perlengkapan', name: 'perlengkapan', orderable: true, searchable:true },
        ],
        initComplete: function(settings){
            table = settings.oInstance.api();
            initSearchCol(table,'#header-filter','search-col-dt');
        }
    });

    $(document).on('change', '.check-data-all', function () {
        let isChecked = $(this).is(':checked');
        table.rows({ page: 'current' }).nodes().each(function (row) {
            $(row).find('.data-check').prop('checked', isChecked);
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

        let rumahItems = $('.data-check:checked').map(function () {
            return Number($(this).data('rumah-used')) || 0;
        }).get().filter(function (item) {
            return item > 0;
        });

        let totalRumahUsed = rumahItems.reduce(function (total, item) {
            return total + item;
        }, 0);

        let usedItems = $('.data-check:checked').map(function () {
            return Number($(this).data('used')) || 0;
        }).get().filter(function (item) {
            return item > 0;
        });

        let totalUsed = usedItems.reduce(function (total, item) {
            return total + item;
        }, 0);

        let info = '';
        if (rumahItems.length > 0) {
            info = `${rumahItems.length} data aset yang dipilih sedang dipakai oleh total ${totalRumahUsed} aset rumah dan tidak bisa dihapus.`;
        } else if (usedItems.length > 0) {
            info = `${usedItems.length} data aset yang dipilih sedang terpakai di ${totalUsed} detail cluster. Jika dihapus, data aset tersebut juga akan terhapus dari detail cluster terkait.`;
        }

        $('#modalConfirmDeleteMultiple')
            .find('#submitModalConfirmDeleteMultiple')
            .attr('wire:click', 'deleteMultiple()')
            .prop('disabled', rumahItems.length > 0)
            .text(rumahItems.length > 0 ? 'Tidak Bisa Dihapus' : 'Hapus Data');
        $('#modalConfirmDeleteMultiple')
            .find('.msg')
            .text(`Apakah anda yakin menghapus ${ids.length} data aset yang dipilih?`);
        $('#modalConfirmDeleteMultiple')
            .find('.delete-info')
            .text(info);
        $('#modalConfirmDeleteMultiple').modal('show');

    });
</script>
@endpush
