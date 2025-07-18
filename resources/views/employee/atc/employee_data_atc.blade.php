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
            { className: 'text-left text-nowrap', targets: [6,7] },
            { className: 'px-0 text-nowrap', targets: [1] },
            { className: 'text-center text-nowrap', targets: ['_all'] },
        ],
        ajax: '{{ route("karyawan.indexDT") }}',
        columns: [
            { data: null, name: 'check', orderable: false, searchable: false,
                render: function (data, type, row, meta) {
                    el = '<input class="data-check" type="checkbox" value="'+data.id+'">';
                    return el;
                }
            },
            { data: null, name: 'id', orderable: true, searchable: false,
                render: function(data, type, row) {
                    let html = `
                        <div class="btn-group">
                            <a href="javascript:void(0)" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                                <i class="mdi mdi-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu">
                    `;

                    if(!row.user_login_id){
                        html += `
                            <a class="dropdown-item" href="edit/`+data.id+`">
                                <i class="fas fa-edit fa-fw"></i> Aktivasi
                            </a>
                        `;

                    }else{
                        html += `
                            <a class="dropdown-item" href="edit/`+data.id+`">
                                <i class="fas fa-edit fa-fw"></i> Edit Data
                            </a>
                        `;
                    }

                    html += `</div></div>`;
                    return html;
                }
            },
            { data: null, name: 'DT_RowIndex', orderable: false, searchable: false,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'status', name: 'status', orderable: false, searchable: true,
                render: function (data, type, row, meta) {
                    css = 'badge-primary';
                    if(row.status=='Belum Aktif'){
                        css = 'badge-soft-primary';
                    }
                    if(row.status=='Tidak Aktif'){
                        css = 'badge-soft-dark';
                    }

                    el = `<span class="badge `+css+` w-100" style="font-size:13px">`+row.status+`</span>`;

                    return el;
                }
            },
            { data: 'role_name', name: 'user_logins.user_role_id', orderable: false, searchable:true},
            { data: 'name', name: 'name', orderable: false, searchable:true },
            { data: null, name: 'master_schedule_id',orderable: false, searchable: false,
                render: function (data, type, row, meta) {
                    const schedules = row.master_schedules || [];
                    if (schedules.length === 0) {
                        return '';
                    }
                    if (schedules.length === 1) {
                        return schedules[0].kode || '-';
                    }
                    const aktif = schedules.find(s => s.expired_at === null);
                    return aktif ? aktif.kode : '-';
                }
            },
            { data: 'master_organizations.name', name: 'master_organization_id', orderable: false, searchable:true,
                render: function (data, type, row, meta) {
                    return row.master_organizations ? row.master_organizations.name : '-';
                }
            },
            { data: 'master_positions.name', name: 'master_position_id', orderable: false, searchable:true,
                render: function (data, type, row, meta) {
                    return row.master_positions ? row.master_positions.name : '-';
                }
             },
            { data: 'master_locations.name', name: 'master_location_id', orderable: false, searchable:true,
                render: function (data, type, row, meta) {
                    return row.master_locations ? row.master_locations.name : '-';
                }
             },
            { data: 'master_functions.name', name: 'master_function_id', orderable: false, searchable:true,
                render: function (data, type, row, meta) {
                    return row.master_functions ? row.master_functions.name : '-';
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

    $(document).on('click', '.btnMultipleProcess', function () {
        let process = $(this).data('process');
        let msg = $(this).data('msg');

        let ids = $('.data-check:checked').map(function () {
            return $(this).val();
        }).get();

        if (ids.length === 0) {
            alert('Pilih minimal 1 data untuk dihapus.');
            return;
        }

        Livewire.dispatch('setProcessMultipleId', { ids: ids, process: process });

        $('#modalConfirm')
            .find('#proses-label')
            .text(msg);
        $('#modalConfirm').modal('show');

    });
</script>
@endpush
