@section('style')
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('script')
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/libs/moment/moment.js') }}"></script>
@endsection

@push('push-script')
<script>

    var dtTable = $('#myTable').DataTable({
        processing: true,serverSide: true,pageLength: 25,dom: 'lrtip',
        order: [[1, 'asc']],
        columnDefs: [
            { className: 'text-left', targets: [4,11] },
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
            { data: 'day_work', name: 'day_work', orderable: false, searchable:false, render: function (data, type, row, meta) {
                    if (row.type === 'Tetap') {
                        return data.time.checkin_time;
                    }
                    if (row.type === 'Rotasi') {
                        let shiftList = '-';
                        if (Array.isArray(data.time) && data.time.length > 0) {
                            shiftList = data.time
                                .map((shift, idx) => {
                                    return `
                                            <strong>${shift.name || 'Shift ' + (idx + 1)}</strong><br>
                                            ${shift.checkin_time}
                                    `;
                                })
                                .join('');
                        }
                        return shiftList;
                    }
                }
            },
            { data: 'day_work', name: 'day_work', orderable: false, searchable:false, render: function (data, type, row, meta) {
                    if (row.type === 'Tetap') {
                        return data.time.work_time;
                    }
                    if (row.type === 'Rotasi') {
                        let shiftList = '-';
                        if (Array.isArray(data.time) && data.time.length > 0) {
                            shiftList = data.time
                                .map((shift, idx) => {
                                    return `
                                            <strong>${shift.name || 'Shift ' + (idx + 1)}</strong><br>
                                            ${shift.work_time}
                                    `;
                                })
                                .join('');
                        }
                        return shiftList;
                    }
                }
            },
            { data: 'day_work', name: 'day_work', orderable: false, searchable:false, render: function (data, type, row, meta) {
                    if (row.type === 'Tetap') {
                        return data.time.checkin_deadline_time;
                    }
                    if (row.type === 'Rotasi') {
                        let shiftList = '-';
                        if (Array.isArray(data.time) && data.time.length > 0) {
                            shiftList = data.time
                                .map((shift, idx) => {
                                    return `
                                            <strong>${shift.name || 'Shift ' + (idx + 1)}</strong><br>
                                            ${shift.checkin_deadline_time}
                                    `;
                                })
                                .join('');
                        }
                        return shiftList;
                    }
                }
            },
            { data: 'day_work', name: 'day_work', orderable: false, searchable:false, render: function (data, type, row, meta) {
                    if (row.type === 'Tetap') {
                        return data.time.checkout_time;
                    }
                    if (row.type === 'Rotasi') {
                        let shiftList = '-';
                        if (Array.isArray(data.time) && data.time.length > 0) {
                            shiftList = data.time
                                .map((shift, idx) => {
                                    return `
                                            <strong>${shift.name || 'Shift ' + (idx + 1)}</strong><br>
                                            ${shift.checkout_time}
                                    `;
                                })
                                .join('');
                        }
                        return shiftList;
                    }
                }
            },
            { data: 'day_work', name: 'day_work', orderable: false, searchable:false, render: function (data, type, row, meta) {
                    if (row.type === 'Tetap') {
                        return data.time.checkout_deadline_time;
                    }
                    if (row.type === 'Rotasi') {
                        let shiftList = '-';
                        if (Array.isArray(data.time) && data.time.length > 0) {
                            shiftList = data.time
                                .map((shift, idx) => {
                                    return `
                                            <strong>${shift.name || 'Shift ' + (idx + 1)}</strong><br>
                                            ${shift.checkout_deadline_time}
                                    `;
                                })
                                .join('');
                        }
                        return shiftList;
                    }
                }
            },
            { data: 'day_work', name: 'day_work', orderable: false, searchable:false, render: function (data, type, row, meta) {
                    const rowStyle = 'display:flex; align-items:flex-start; margin-bottom:2px;';
                    const labelStyle = 'width:160px; font-weight:bold;';
                    const valueStyle = 'flex:1;';

                    if (row.type === 'Rotasi') {
                        const startDate = data.start_date
                            ? moment(data.start_date).format('DD-MM-YYYY')
                            : '-';
                        const workDay = data.work_day ? `${data.work_day} hari kerja` : '-';
                        const offDay = data.off_day ? `${data.off_day} hari off` : '-';

                        let shiftList = '-';
                        if (Array.isArray(data.time) && data.time.length > 0) {
                            shiftList = data.time
                                .map((shift, idx) => {
                                    return `
                                        <div>
                                            <strong>${shift.name || 'Shift ' + (idx + 1)}</strong><br>
                                            Masuk: ${shift.checkin_time} s/d ${shift.checkin_deadline_time}<br>
                                            Kerja: ${shift.work_time}<br>
                                            Pulang: ${shift.checkout_time} s/d ${shift.checkout_deadline_time}
                                        </div>
                                    `;
                                })
                                .join('<hr style="margin: 4px 0;">');
                        }

                        return `
                            <div style="${rowStyle}"><div style="${labelStyle}">Tanggal Mulai Rotasi:</div><div style="${valueStyle}">${startDate}</div></div>
                            <div style="${rowStyle}"><div style="${labelStyle}">Siklus Rotasi:</div><div style="${valueStyle}">${workDay}</div></div>
                            <div style="${rowStyle}"><div style="${labelStyle}"></div><div style="${valueStyle}">${offDay}</div></div>
                        `;
                    } else if (row.type === 'Tetap') {
                        const semuaHari = [0, 1, 2, 3, 4, 5, 6];

                        const regularDays = Array.isArray(data.day) && data.day.length > 0
                            ? data.day
                                .map((key) => getHariIndo[parseInt(key)])
                                .join(', ')
                            : '-';

                        const lemburDays = Array.isArray(data.day) && data.day.length > 0
                            ? semuaHari
                                .filter((key) => !data.day.includes(key))
                                .map((key) => getHariIndo[key])
                                .join(', ')
                            : '-';

                        return `
                            <div style="${rowStyle}">
                                <div style="${labelStyle}">Hari Kerja:</div>
                                <div style="${valueStyle}">${regularDays}</div>
                            </div>
                            <div style="${rowStyle}">
                                <div style="${labelStyle}">Hari Off/Lembur:</div>
                                <div style="${valueStyle}">${lemburDays}</div>
                            </div>
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
