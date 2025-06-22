@section('style')
{{-- <link href="{{ asset('assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" /> --}}
<style>
    .text-rapat {
        line-height: 16px;
        font-size: 14px !important;
    }
</style>
@endsection

@section('script')
{{-- <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
<script src="{{ asset('assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-us-merc-en.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/dashboard.init.js') }}"></script> --}}
<script src="{{ asset('assets/libs/moment/moment.js') }}"></script>
<script src="{{ asset('assets/libs/moment/locale/id.js') }}"></script>
@endsection

@push('push-script')

<script>
    $.ajax({
        url: '{{ route("dashboard.getSummaryRank") }}',
        method: 'POST',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        data: JSON.stringify({
            // _token: '{{ csrf_token() }}'
        }),
        success: function(response) {
            const data = response.data;
            const param = response.param

            setJuara(data);
            launchConfetti();
            setTop(data);
        },
        error: function(xhr) {
            console.error('Gagal:', xhr.responseText);
        }
    });

    function setJuara(data){
        data.sort((a, b) => {
            if (b.akumulasi.rank.total_poin !== a.akumulasi.rank.total_poin) {
                return b.akumulasi.rank.total_poin - a.akumulasi.rank.total_poin;
            }
            return b.akumulasi.time_detail.loyal_time - a.akumulasi.time_detail.loyal_time;
        });

        const rank1 = data[0];

        // Tampilkan ke elemen HTML
        $('#rank1-name').html(rank1.name);
        $('#rank1-point').html(rank1.akumulasi.rank.total_poin);
        $('#rank1-org').html(rank1.master_organizations.name);
        $('#rank1-as').html(rank1.master_positions.name);
        $('#rank1-day-work').html(rank1.akumulasi.hari_kerja);
        $('#rank1-hadir').html(rank1.akumulasi.hadir);
        $('#rank1-noabsen').html(rank1.akumulasi.tdk_absen);
        if(rank1.akumulasi.time_detail.loyal_time_read >= 0){
            $('#rank1-loyal').html('+'+formatAngka(rank1.akumulasi.time_detail.loyal_time_read));
        }else{
            $('#rank1-loyal').html(formatAngka(rank1.akumulasi.time_detail.loyal_time_read));
        }

    }

    function parsingRowTop(data, tableId, attr){
        var html = '';
        data.forEach((item, index) => {
            html += `
                <tr>
                    <td>${index + 1}</td>
                    <td><h5 class="font-size-14 mb-0">${item.name}</h5></td>
                    <td><p class="text-muted mb-0">${formatAngka(item.akumulasi.time_detail[attr])} menit</p></td>
                </tr>
            `;
        });
        document.querySelector(`#${tableId} tbody`).innerHTML = html;
    }


    function setTop(data){
        let order1 = data.slice();
        let order2 = data.slice();
        let order3 = data.slice();
        let order4 = data.slice();

        order1.sort((a, b) => b.akumulasi.time_detail.total_dtg_cpt - a.akumulasi.time_detail.total_dtg_cpt);
        order2.sort((a, b) => b.akumulasi.time_detail.total_dtg_lama - a.akumulasi.time_detail.total_dtg_lama);
        order3.sort((a, b) => b.akumulasi.time_detail.total_plg_cpt - a.akumulasi.time_detail.total_plg_cpt);
        order4.sort((a, b) => b.akumulasi.time_detail.total_plg_lama - a.akumulasi.time_detail.total_plg_lama);

        parsingRowTop(order1.slice(0, 5), 'order1', 'total_dtg_cpt_read');
        parsingRowTop(order2.slice(0, 5), 'order2', 'total_dtg_lama_read');
        parsingRowTop(order3.slice(0, 5), 'order3', 'total_plg_cpt_read');
        parsingRowTop(order4.slice(0, 5), 'order4', 'total_plg_lama_read');
    }

    $.ajax({
        url: '{{ route("dashboard.getSummaryAttd") }}',
        method: 'POST',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        data: JSON.stringify({
            // _token: '{{ csrf_token() }}'
        }),
        success: function(response) {
            const data = response.data;
            const param = response.param

            setSummaryAttd(param.jlh_karyawan, data)
            launchConfetti();
        },
        error: function(xhr) {
            console.error('Gagal:', xhr.responseText);
        }
    });

    function setSummaryAttd(totalEmploye, data){
        moment.locale('id');
        const todayFormatted = moment().format('D MMMM YYYY');
        $('#today').html(todayFormatted);
        $('#jlh_karyawan').html(totalEmploye);

        // console.log(data);
        const totalDtgOntime = data.reduce((sum, item) => sum + (item.akumulasi?.dtg_ontime || 0), 0);
        $('#dtg-ontime').html(totalDtgOntime);
        const totalDtgTerlambat = data.reduce((sum, item) => sum + (item.akumulasi?.terlambat || 0), 0);
        $('#dtg-terlambat').html(totalDtgTerlambat);
        const totalDtgNoabsen = data.reduce((sum, item) => sum + (item.akumulasi?.tdk_absen_dtg || 0), 0);
        $('#dtg-noabsen').html(totalDtgNoabsen);
        const totalPlgOntime = data.reduce((sum, item) => sum + (item.akumulasi?.plg_ontime || 0), 0);
        $('#plg-ontime').html(totalPlgOntime);
        const totalPlgCepat = data.reduce((sum, item) => sum + (item.akumulasi?.plg_cepat || 0), 0);
        $('#plg-cepat').html(totalPlgCepat);
        const totalPlgNoabsen = data.reduce((sum, item) => sum + (item.akumulasi?.tdk_absen_plg || 0), 0);
        $('#plg-noabsen').html(totalPlgNoabsen);

    }


</script>


@endpush



