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
                                <i class="ri-question-line rounded-circle p-3 badge-soft-primary"></i>
                            </h1>
                            <h4>Konfirmasi Poses</h4>
                            <h6 class="msg">Apakah anda yakin <span id="proses-label"></span> semua data yang dipilih?</h6>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-light waves-effect px-5" data-dismiss="modal">Batal</button>
                        <button type="button" id="submitModalConfirm" class="btn btn-primary waves-effect waves-light px-5" wire:click="">Ya, yakin</button>
                    </div>
                </div><!-- /.modal-content -->
            </div>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
