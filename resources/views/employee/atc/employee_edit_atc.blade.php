@section('style')
@endsection
@section('script')
<script>
    Livewire.on('reloadPage', () => {
        window.location.reload();
    });
</script>
@endsection


