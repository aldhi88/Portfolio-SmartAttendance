@section('style')
    <link rel="stylesheet" href="{{ asset('assets/libs/flatpicker/flatpickr.min.css') }}">
@endsection
@section('script')
    <script src="{{ asset('assets/libs/flatpicker/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/libs/inputmask/jquery.inputmask.min.js') }}"></script>
@endsection

@push('push-script')
    <script>
        $('.input-mask-time').inputmask({
            mask: '99:99',
            placeholder: 'HH:mm',
            hourFormat: '24'
        });

        $('.input-mask-time').on('input', function() {
            const val = $(this).val();
            const field = $(this).data('field');

            if (field) {
                @this.set(field, val+':00');
            }
        });
        // flatpickr("input[name=start]", {
        //     enableTime: true,
        //     noCalendar: false,
        //     dateFormat: "Y-m-d H:i",
        //     time_24hr: true,
        //     defaultHour: 0,
        //     defaultMinute: 0,
        // });

        // flatpickr("input[name=end]", {
        //     enableTime: true,
        //     noCalendar: false,
        //     dateFormat: "Y-m-d H:i",
        //     time_24hr: true,
        //     defaultHour: 23,
        //     defaultMinute: 59,
        // });
    </script>
@endpush
