@section('script')
    <script src="{{ asset('assets/libs/inputmask/jquery.inputmask.min.js') }}"></script>
@endsection

@push('push-script')
    <script>
        $(document).ready(function() {
            $('.input-mask-time').inputmask();
            $('.input-mask-time-sore').inputmask();
            $('.input-mask-time-malam').inputmask();

            $('.input-mask-time').on('change', function () {
                const val = $(this).val();
                @this.set('dtRotasi.'+$(this).attr('id'), val);
            });

            $('.input-mask-time-sore').on('change', function () {
                const val = $(this).val();
                @this.set('dtRotasi.day_work.rotasi.sore.'+$(this).attr('id'), val);
            });

            $('.input-mask-time-malam').on('change', function () {
                const val = $(this).val();
                @this.set('dtRotasi.day_work.rotasi.malam.'+$(this).attr('id'), val);
            });
        });
    </script>
@endpush
