@section('style')
@endsection
@section('script')
@endsection

@push('push-script')
<script>
    let modalId = null;
    let scheduleId = null;
    window.addEventListener('openModal', event => {
        modalId = event.detail.id;
        scheduleId = event.detail.scheduleId;
        $('#' + modalId).modal('show');
    });
</script>
@endpush
