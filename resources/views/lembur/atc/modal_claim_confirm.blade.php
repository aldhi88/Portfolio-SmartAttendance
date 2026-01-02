<div>
    <div wire:ignore.self class="modal fade" id="modalConfirmClaim" tabindex="-1">
        <div class="modal-dialog modal-lg">
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
                                <i class="ri-error-warning-line rounded-circle p-3 badge-soft-danger"></i>
                            </h1>
                            <h4>Konfirmasi Klaim Presensi Lembur Manual {{$prosesId}}</h4>
                        </div>
                    </div>
                    <hr>

                    @error('error')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <span wire:ignore>
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label>Waktu Masuk Lembur: </label>
                                    <input class="form-control datetime" name="start" />
                                </div>
                                <div class="form-group">
                                    <label>Waktu Pulang Lembur: </label>
                                    <input class="form-control datetime" name="end"/>
                                </div>
                            </div>
                            <div class="col text-md-right">
                                <div class="dropdown mt-4 mt-sm-0">
                                    <a href="#" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Ditemukan <span id="log-gps-count"></span> Log GPS <i class="mdi mdi-chevron-down"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" id="logList"></div>
                                </div>
                            </div>

                        </div>

                        <hr>
                        <div class="text-center mt-4">
                            <button type="button" class="btn btn-light waves-effect px-5" data-dismiss="modal">Batal</button>
                            <button id="submitModalConfirm" disabled type="button" class="btn btn-info waves-effect waves-light px-5">Ya, Proses</button>
                        </div>
                    </span>
                </div><!-- /.modal-content -->
            </div>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    @push('push-script')
    <script>
        $('#modalConfirmClaim').on('shown.bs.modal', function(e) {
            const modal   = this;
            const startEl = modal.querySelector("input[name=start]");
            const endEl   = modal.querySelector("input[name=end]");
            const btn     = modal.querySelector("#submitModalConfirm");

            const updateSubmitState = () => {
                const ok = !!(startEl?.value?.trim() && endEl?.value?.trim());
                if (btn) btn.disabled = !ok;
            };

            // anti double-init saat modal dibuka berkali-kali
            if (startEl && startEl._flatpickr) startEl._flatpickr.destroy();
            if (endEl && endEl._flatpickr) endEl._flatpickr.destroy();

            // ===== RESET nilai lama setiap modal dibuka =====
            if (startEl) startEl.value = '';
            if (endEl) endEl.value = '';
            @this.set('lemburIn', null, false);
            @this.set('lemburOut', null, false);

            // awalnya tombol disabled
            if (btn) btn.disabled = true;

            flatpickr(startEl, {
                enableTime: true,
                dateFormat: "Y-m-d H:i:ss",
                time_24hr: true,
                defaultHour: 0,
                defaultMinute: 0,
                appendTo: modal,
                onChange: function(selectedDates, dateStr) {
                    @this.set('lemburIn', dateStr, false);

                    if (selectedDates?.[0] && endEl?._flatpickr) {
                        endEl._flatpickr.set('minDate', selectedDates[0]);
                    }
                    updateSubmitState();
                }
            });

            flatpickr(endEl, {
                enableTime: true,
                dateFormat: "Y-m-d H:i:ss",
                time_24hr: true,
                defaultHour: 23,
                defaultMinute: 59,
                appendTo: modal,
                onChange: function(selectedDates, dateStr) {
                    @this.set('lemburOut', dateStr, false);
                    updateSubmitState();
                }
            });

            // kode kamu yang lain tetap...
            const data = $(e.relatedTarget).data('json');
            let logs = data.log_gps;
            $(modal).find('#log-gps-count').text(logs.length);
            logs.sort((a, b) => moment(a.created_at) - moment(b.created_at));
            $('#logList').empty();
            $.each(logs, function (i, item) {
                const formatted = moment(item.created_at).locale('id').format('DD/MM/YYYY HH:mm:ss');
                $('#logList').append(`
                    <a class="dropdown-item" href="javascript:void(0)">
                        <i class="fas fa-map-marker-alt fa-fw"></i> ${formatted}
                    </a>
                `);
            });

            const dispatchMethod = $(e.relatedTarget).data('dispatch') || 'wireSubmitClaim';
            $(modal).find('#submitModalConfirm').attr('wire:click', `${dispatchMethod}`);
        });
    </script>
    @endpush



</div>
