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
        order: [[1, 'asc']],
        columnDefs: [
            { className: 'text-left', targets: [4,10] },
            { className: 'px-0', targets: [1] },
            { className: 'text-center', targets: ['_all'] },
        ],
        ajax: '{{ route("jadwal-kerja.indexDT") }}',
        columns: [
            {
                data: null, name: 'created_at', orderable: false, searchable: false,
                render: function (data, type, row, meta) {
                    el = '';
                    if (data.data_employees?.length === 0) {
                        el += '<input class="data-check" type="checkbox" value="'+data.id+'">';
                    }
                    return el;
                }
            },
            {
                data: null, name: 'created_at', orderable: false, searchable: false,
                render: function(data, type, row) {
                    let html = `
                        <div class="btn-group">
                            <a href="javascript:void(0)" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                                <i class="mdi mdi-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="edit/`+data.id+`/`+data.type.toLowerCase()+`">
                                    <i class="fas fa-edit fa-fw"></i> Edit Data
                                </a>
                    `;

                    if (data.data_employees?.length === 0) {
                        const dtJson = {
                            msg: `Apakah anda yakin menghapus data ${data.name}?`,
                            id: data.id
                        };

                        html += `
                            <a data-json='${JSON.stringify(dtJson)}' class="dropdown-item text-danger delete"
                            data-toggle="modal" data-target="#modalConfirmDelete"
                            data-dispatch="wireDelete()"
                            href="javascript:void(0);">
                            <i class="fas fa-trash-alt fa-fw"></i> Hapus Data
                            </a>
                        `;
                    }

                    html += `</div></div>`;
                    return html;
                }
            },
            {
                data: null, name: 'DT_RowIndex', orderable: false, searchable: false,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'kode', name: 'kode', orderable: false, searchable:true },
            { data: 'name', name: 'name', orderable: false, searchable:true },
            { data: 'type', name: 'type', orderable: false, searchable:true },
            { data: 'checkin_time', name: 'checkin_time', orderable: false, searchable:false },
            { data: 'work_time', name: 'work_time', orderable: false, searchable:false },
            { data: 'checkin_deadline_time', name: 'checkin_deadline_time', orderable: false, searchable:false },
            { data: 'checkout_time', name: 'checkout_time', orderable: false, searchable:false },
            { data: 'day_work', name: 'day_work', orderable: false, searchable:false,
                render: function (data, type, row, meta) {
                    const rowStyle = 'display:flex; align-items:flex-start; margin-bottom:2px;';
                    const labelStyle = 'width:160px; font-weight:bold;';
                    const valueStyle = 'flex:1;';

                    if (row.type === 'Rotasi') {
                        const startDate = data.start_date ?? '-';
                        const workDay = data.work_day ? `${data.work_day} hari kerja` : '-';
                        const offDay = data.off_day ? `${data.off_day} hari off` : '-';

                        return `
                            <div style="${rowStyle}"><div style="${labelStyle}">Tanggal Mulai Rotasi:</div><div style="${valueStyle}">${startDate}</div></div>
                            <div style="${rowStyle}"><div style="${labelStyle}">Rotasi:</div><div style="${valueStyle}">${workDay}</div></div>
                            <div style="${rowStyle}"><div style="${labelStyle}"></div><div style="${valueStyle}">${offDay}</div></div>
                        `;
                    } else if (row.type === 'Tetap') {
                        const regularDays = data.regular && Object.keys(data.regular).length
                            ? Object.entries(data.regular)
                                .filter(([key, val]) => val === true)
                                .map(([key]) => getHariIndo[parseInt(key)])
                                .join(', ')
                            : '-';

                        const lemburDays = data.lembur && Object.keys(data.lembur).length
                            ? Object.entries(data.lembur)
                                .filter(([key, val]) => val === true)
                                .map(([key]) => getHariIndo[parseInt(key)])
                                .join(', ')
                            : '-';

                        return `
                            <div style="${rowStyle}"><div style="${labelStyle}">Hari Kerja:</div><div style="${valueStyle}">${regularDays}</div></div>
                            <div style="${rowStyle}"><div style="${labelStyle}">Hari Off/Lembur:</div><div style="${valueStyle}">${lemburDays}</div></div>
                        `;
                    }
                }

             },
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
            .attr('wire:click', 'deleteMultiple()');
        $('#modalConfirmDeleteMultiple').modal('show');

    });
</script>
@endpush
