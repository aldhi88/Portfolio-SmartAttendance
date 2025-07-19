@section('style')
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('script')
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/libs/moment/locale/id.js') }}"></script>
@endsection

@push('push-script')
<script>
    var dtTable = $('#myTable').DataTable({
        processing: true,serverSide: true,pageLength: 25,dom: 'lrtip',
        order: [[1, 'asc']],
        columnDefs: [
            { className: 'text-left', targets: [3] },
            { className: 'px-0', targets: [1] },
            { className: 'text-center', targets: ['_all'] },
        ],
        ajax: '{{ route("lembur.indexLemburDT") }}',
        columns: [
            { data: null, name: 'created_at', orderable: false, searchable: false,
                render: function (data, type, row, meta) {
                    el = '<input class="data-check" type="checkbox" value="'+row.id+'">';
                    return el;
                }
            },
            { data: null, name: 'created_at', orderable: false, searchable: false,
                render: function(data, type, row) {
                    let editUrl = "edit/"+row.id;
                    let html = `
                        <div class="btn-group">
                            <a href="javascript:void(0)" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                                <i class="mdi mdi-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu">
                    `;

                    html += `
                                <a class="dropdown-item" href="${editUrl}">
                                    <i class="fas fa-edit fa-fw"></i> Edit Data
                                </a>
                    `;

                    if (data.status === 'Proses' || data.status === 'Ditolak') {
                        const dtJson = {
                            msg: `Anda yakin menyetujui data izin ${data.data_employees.name}?`,
                            id: data.id,
                            proses: 'Setujui'
                        };

                        html += `
                            <a data-json='${JSON.stringify(dtJson)}' class="dropdown-item"
                            data-toggle="modal" data-target="#modalConfirmSetuju"
                            data-dispatch="wireProses('Disetujui')"
                            href="javascript:void(0);">
                            <i class="fas fa-check fa-fw"></i> Setujui Data Izin
                            </a>
                        `;
                    }
                    if (data.status === 'Disetujui') {
                        const dtJson = {
                            msg: `Anda yakin menolak data izin ${data.data_employees.name}?`,
                            id: data.id,
                            proses: 'Ditolak'
                        };

                        html += `
                            <a data-json='${JSON.stringify(dtJson)}' class="dropdown-item text-danger"
                            data-toggle="modal" data-target="#modalConfirmSetuju"
                            data-dispatch="wireProses('Ditolak')"
                            href="javascript:void(0);">
                            <i class="fas fa-times fa-fw"></i> Tolak Data Izin
                            </a>
                        `;
                    }

                    const dtJson = {
                        msg: `Apakah anda yakin menghapus data izin ${row.data_employees.name}?`,
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

                    html += `</div></div>`;
                    return html;
                }
            },
            { data: null, name: 'DT_RowIndex', orderable: false, searchable: false,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'data_employees.name', name: 'data_employees.name', orderable: false, searchable:false },
            { data: 'status', name: 'status', orderable: false, searchable:false,
                render: function (data, type, row, meta) {
                    let color;
                    let status = row.status;
                    switch (status) {
                        case "Proses":
                            color = "secondary";
                            break;
                        case "Disetujui":
                            color = "success";
                            break;
                        default:
                            color = "danger";
                        }
                    return `<span class="badge badge-${color} w-100" style="font-size:13px">${status}</span>`;
                }
            },
            { data: 'data_employees', name: null, orderable: false, searchable:false,
                render: function (data, type, row, meta) {
                    if(row.approved_by != null){
                        return row.data_employee_admins.name;
                    }
                    return '-';
                }
            },
            { data: 'tanggal', name: 'tanggal', orderable: false, searchable:false,
                render: function (data, type, row, meta) {
                    return moment(row.tanggal).locale('id').format('DD MMMM YYYY');
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

    // 3. Reset "select all" saat pindah halaman
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


