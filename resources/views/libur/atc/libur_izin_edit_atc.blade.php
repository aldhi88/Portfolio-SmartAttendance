@section('style')
    <link rel="stylesheet" href="{{ asset('assets/libs/flatpicker/flatpickr.min.css') }}">
@endsection
@section('script')
    <script src="{{ asset('assets/libs/flatpicker/flatpickr.js') }}"></script>
@endsection

@push('push-script')

<script>
    flatpickr("input[name=start]", {
        enableTime: true,
        noCalendar: false,
        dateFormat: "Y-m-d H:i",
        time_24hr: true,
        defaultHour: 0,
        defaultMinute: 0,
        defaultDate: "{{ $form['from'] ?? '' }}",
    });

    flatpickr("input[name=end]", {
        enableTime: true,
        noCalendar: false,
        dateFormat: "Y-m-d H:i",
        time_24hr: true,
        defaultHour: 23,
        defaultMinute: 59,
        defaultDate: "{{ $form['to'] ?? '' }}",
    });
</script>


@endpush
