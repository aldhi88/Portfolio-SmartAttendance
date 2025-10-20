@section('style')
@endsection
@section('script')
@endsection

@push('push-script')
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('reloadPage', () => {
                setTimeout(() => {
                    window.location.reload();
                }, 1000); // 1.5 detik = waktu alert tampil
            });
        });
    </script>
@endpush
