@section('script')
    <script src="{{ asset('assets/libs/inputmask/jquery.inputmask.min.js') }}"></script>
@endsection

@push('push-script')
    <script>
        $(document).ready(function() {
            $('.input-mask-time').inputmask();
            $('.input-mask-time').on('input', function () {
                const val = $(this).val();
                @this.set('dtRotasi.day_work.time.'+$(this).attr('id'), val);
            });
        });
    </script>
@endpush
