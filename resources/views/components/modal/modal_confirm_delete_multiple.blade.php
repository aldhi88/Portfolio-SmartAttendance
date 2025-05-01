<div>
    <div wire:ignore class="modal fade" id="modalConfirmDeleteMultiple" tabindex="-1">
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
                                <i class="ri-alert-line rounded-circle p-3 badge-soft-danger"></i>
                            </h1>
                            <h4>Konfirmasi Hapus Data Banyak</h4>
                            <h6 class="msg">Apakah anda yakin menghapus semua data yang dipilih?</h6>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-light waves-effect px-5" data-dismiss="modal">Batal</button>
                        <button type="button" id="submitModalConfirmDeleteMultiple" class="btn btn-danger waves-effect waves-light px-5" wire:click="">Hapus Data</button>
                    </div>
                </div><!-- /.modal-content -->
            </div>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
