<div>
    <div wire:ignore.self class="modal fade" id="modal-delete" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Konfirmasi Hapus</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <h6 class="msg"></h6>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger waves-effect waves-light delete">Hapus Data</button>
                    </div>
                </div><!-- /.modal-content -->
            </div>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    @push('push-script')
        <script>
            $("#modal-delete").on("show.bs.modal", function(e) {
                var data = $(e.relatedTarget).data('json');
                $(this).find('.msg').text(data.msg);
                $(this).find('.delete').attr('wire:click', 'delete(' + data.id + ')');
            });
        </script>
    @endpush
</div>
