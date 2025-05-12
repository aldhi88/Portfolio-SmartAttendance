@push('push-script')
    <script>
        $(document).ready(function() {
            $('.regular').on('change', function() {
                let val = $(this).val();
                $('.lembur[value="' + val + '"]').prop('disabled', $(this).is(':checked'));
            });

            $('.lembur').on('change', function() {
                let val = $(this).val();
                $('.regular[value="' + val + '"]').prop('disabled', $(this).is(':checked'));
            });
        });
    </script>
@endpush
