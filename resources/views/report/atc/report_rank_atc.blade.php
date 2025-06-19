@section('style')
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.dataTables.min.css" />
    <style>
        .text-rapat {
            line-height: 16px;
        }
    </style>
@endsection

@section('script')
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/libs/moment/moment.js') }}"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>
@endsection

@push('push-script')
<script>

    let pushCols = [
        { data: null, name: 'id', orderable: false, searchable: false,
            render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
        { data: null, name: 'name', orderable: false, searchable:true,
            render: function(data, type, row, meta){
                let html = `<h6 class="">${row.name}</h6>`;
                html += `<div class="text-muted text-rapat">`;
                html += `${row.master_organizations.name}</div>`;
                html += `<div class="text-muted text-rapat">${row.master_positions.name}</div>`;

                return html;
            }
        },
        { name: 'id', orderable: false, searchable:false,
            render: function(data, type, row, meta){
                return row.akumulasi.hari_kerja+' hari';
            }
        },
        { name: 'id', orderable: false, searchable:false,
            render: function(data, type, row, meta){
                return row.akumulasi.tdk_absen+' kali';
            }
        },
        { name: 'id', orderable: false, searchable:false,
            render: function(data, type, row, meta){
                return row.akumulasi.loyal_time_read+' jam';
            }
        },
        { name: 'id', orderable: false, searchable:false,
            render: function(data, type, row, meta){
                return row.akumulasi.hadir+' hari';
            }
        },
        { name: 'id', orderable: false, searchable:false,
            render: function(data, type, row, meta){
                return row.akumulasi.izin+' hari';
            }
        },
        { name: 'id', orderable: false, searchable:false,
            render: function(data, type, row, meta){
                return row.akumulasi.alpa+' hari';
            }
        },
        { name: 'id', orderable: true, searchable:false,
            render: function(data, type, row, meta){
                return row.akumulasi.total_poin;
            }
        },
    ];

    var dtTable = $('#myTable').DataTable({
        processing: true,serverSide: true,pageLength: -1,dom: 'rtip',
        order: [[8, 'desc']],
        columnDefs: [
            { className: 'text-left text-nowrap', targets: [1] },
            { className: 'text-center text-nowrap', targets: ['_all'] },
        ],
        ajax: {
            url: '{{ route("report.rankDT") }}',
            type: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: function (d) {
                d.filter_year = "{{ $filter['thisYear'] }}";
                d.filter_month = "{{ $filter['thisMonth'] }}";
                d.filter_master_organization_id = "{{ $filter['master_organization_id'] }}";
                d.filter_master_position_id = "{{ $filter['master_position_id'] }}";
                d.filter_name = "{{ $filter['name'] }}";
            },
            dataSrc: function(json) {
                json.data.sort((a, b) => {
                    // Urutkan berdasarkan total_poin (desc)
                    if (b.akumulasi.total_poin !== a.akumulasi.total_poin) {
                        return b.akumulasi.total_poin - a.akumulasi.total_poin;
                    }

                    // // Jika total_poin sama, urutkan berdasarkan tdk_absen (asc)
                    // if (a.akumulasi.tdk_absen !== b.akumulasi.tdk_absen) {
                    //     return a.akumulasi.tdk_absen - b.akumulasi.tdk_absen;
                    // }

                    // Jika masih sama, urutkan berdasarkan loyal_time (desc)
                    return b.akumulasi.loyal_time - a.akumulasi.loyal_time;
                });

                const rank1 = json.data[0];
                console.log(rank1);
                $('#rank1-name').html(rank1.name);
                $('#rank1-point').text(rank1.akumulasi.total_poin);
                $('#rank1-org').html(rank1.master_organizations.name);
                $('#rank1-as').text(rank1.master_positions.name);
                $('#rank1-day-work').text(rank1.akumulasi.hari_kerja);
                $('#rank1-hadir').text(rank1.akumulasi.hadir);
                $('#rank1-noabsen').text(rank1.akumulasi.tdk_absen);
                $('#rank1-loyal').text(rank1.akumulasi.loyal_time_read);

                launchConfetti();
                return json.data;
            }
        },

        columns: pushCols,
        initComplete: function(settings){
            table = settings.oInstance.api();
            initSearchCol(table,'#header-filter','search-col-dt');
        }
    });

    function launchConfetti() {
        const canvas = document.getElementById('confetti-canvas');
        const ctx = canvas.getContext('2d');
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        const confettiCount = 150;
        const confetti = [];

        for (let i = 0; i < confettiCount; i++) {
            confetti.push({
                x: canvas.width - Math.random() * 1000, // sudut kanan atas
                y: -Math.random() * canvas.height,
                r: Math.random() * 6 + 4,
                d: Math.random() * confettiCount,
                color: `hsl(${Math.floor(Math.random() * 360)}, 70%, 60%)`,
                tilt: Math.floor(Math.random() * 10) - 10,
                tiltAngleIncremental: (Math.random() * 0.07) + 0.05,
                tiltAngle: 0
            });
        }

        let animationId;
        const start = performance.now();
        const duration = 5000; // ms

        function draw(progress) {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.globalAlpha = 1 - progress; // efek fade out

            confetti.forEach((confetto, i) => {
                ctx.beginPath();
                ctx.lineWidth = confetto.r;
                ctx.strokeStyle = confetto.color;
                ctx.moveTo(confetto.x + confetto.tilt + (confetto.r / 2), confetto.y);
                ctx.lineTo(confetto.x + confetto.tilt, confetto.y + confetto.tilt + (confetto.r / 2));
                ctx.stroke();
            });

            ctx.globalAlpha = 1; // reset untuk keamanan render selanjutnya
        }

        function update() {
            confetti.forEach((confetto, i) => {
                confetto.tiltAngle += confetto.tiltAngleIncremental;
                confetto.y += (Math.cos(confetto.d) + 3 + confetto.r / 2) / 2;
                confetto.tilt = Math.sin(confetto.tiltAngle - (i / 3)) * 15;
            });
        }

        function animate(now) {
            const elapsed = now - start;
            const progress = Math.min(elapsed / duration, 1);

            update();
            draw(progress);

            if (progress < 1) {
                animationId = requestAnimationFrame(animate);
            } else {
                cancelAnimationFrame(animationId);
                ctx.clearRect(0, 0, canvas.width, canvas.height);
            }
        }

        animationId = requestAnimationFrame(animate);
    }


</script>
@endpush
