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
        url: '{{ route("dashboard.getSummary") }}',
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
            console.log(data);

            setJuara(data);
            setSummaryAttd(param)
            launchConfetti();
        },
        error: function(xhr) {
            console.error('Gagal:', xhr.responseText);
        }
    });

    function setSummaryAttd(data){
        moment.locale('id');
        const todayFormatted = moment().format('D MMMM YYYY');
        $('#today').html(todayFormatted);
        $('#jlh_karyawan').html(data.jlh_karyawan);
    }

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

</script>


@endpush



