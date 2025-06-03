@section('style')
    <link rel="stylesheet" href="{{ asset('assets/libs/flatpicker/flatpickr.min.css') }}">
@endsection
@section('script')
    <script src="{{ asset('assets/libs/flatpicker/flatpickr.js') }}"></script>
@endsection

@push('push-script')

<script>
    flatpickr(".datetime", {
        enableTime: true,
        noCalendar: false,
        dateFormat: "Y-m-d H:i",
        time_24hr: true,
    });
</script>


@endpush
