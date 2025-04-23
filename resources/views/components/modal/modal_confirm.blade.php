<div>
    <div wire:ignore.self class="modal fade" id="modalConfirm" tabindex="-1">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-info-circle"> </i> Konfirmasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                <form method="POST" wire:submit.prevent="modalConfirmProcess">

                    <div class="modal-body">

                        {{ isset($data['msg_confirm'])?$data['msg_confirm']:null }}?

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Batalkan</button>
                        <button type="submit" class="btn btn-danger">Yakin & Lanjutkan</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

</div>
