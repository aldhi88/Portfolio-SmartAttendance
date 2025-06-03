<div>
    <div wire:ignore class="modal fade" id="modalConfirm" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col text-center">
                            <h1 class="mb-3">
                                <i class="ri-question-line rounded-circle p-3 badge-soft-info"></i>
                            </h1>
                            <h4>Konfirmasi Proses</h4>
                            <h6 class="msg">{{ isset($data['msg'])?$data['msg']:null }}</h6>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-light waves-effect px-5" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-info waves-effect waves-light px-5" id="submitModalConfirm" wire:click="">Ya, Proses</button>
                    </div>
                </div><!-- /.modal-content -->
            </div>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    @push('push-script')
        <script>
            $('#modalConfirm').on('show.bs.modal', function(e) {
                const data = $(e.relatedTarget).data('json');
                $(this).find('.msg').text(data.msg);
                Livewire.dispatch('setProsesId', {id: data.id});
                const dispatchMethod = $(e.relatedTarget).data('dispatch') || 'wireProses';
                $(this).find('#submitModalConfirm').attr('wire:click', `${dispatchMethod}`);
            });
        </script>
    @endpush
</div>
