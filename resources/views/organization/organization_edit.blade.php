<div>
    <div wire:ignore.self class="modal fade" id="modal-edit" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Form Edit Data Perusahaan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="submit">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Nama Perusahaan</label>
                                    <input autofocus type="text" wire:model="dt.name"
                                        class="form-control @error('dt.name') is-invalid @enderror">
                                    @error('dt.name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer px-0">
                            <button type="button" class="btn btn-light waves-effect"
                                data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan Perubahan</button>
                        </div>
                    </div><!-- /.modal-content -->
                </form>
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        @push('push-script')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    $("#modal-edit").on("show.bs.modal", function(e) {
                        const id = $(e.relatedTarget).data('id');
                        Livewire.dispatch('organizationEdit-editPrepare', {id: id});
                    });
                });
            </script>
        @endpush
    </div>
