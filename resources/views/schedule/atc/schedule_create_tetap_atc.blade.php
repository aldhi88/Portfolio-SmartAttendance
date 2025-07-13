@section('script')
    <script src="{{ asset('assets/libs/inputmask/jquery.inputmask.min.js') }}"></script>
@endsection

@push('push-script')
    <script>
        $(document).ready(function() {
            $('.input-mask-time').inputmask();
            $('.input-mask-time').on('input', function () {
                const val = $(this).val();
                @this.set('dtTetap.day_work.time.'+$(this).attr('id'), val);
            });

            // $('.regular').on('change', function() {
            //     let val = $(this).val();
            //     $('.lembur[value="' + val + '"]').prop('disabled', $(this).is(':checked'));
            // });

            // $('.lembur').on('change', function() {
            //     let val = $(this).val();
            //     $('.regular[value="' + val + '"]').prop('disabled', $(this).is(':checked'));
            // });
        });
    </script>
@endpush
