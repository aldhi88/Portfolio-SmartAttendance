<div class="modal fade" id="myModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <form wire:submit.prevent="wireSubmitScheduleBebas">
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
                        <div class="col">
                            <h3 class="text-center">Set Waktu Jadwal Bebas</h3>
                            <div class="table-responsive">
                                <table class="table table-sm m-0 table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Jadwal</th>
                                            <th>Checkin Start</th>
                                            <th>Checkin Ontime</th>
                                            <th>Checkin End</th>
                                            <th>Checkout Start</th>
                                            <th>Checkout End</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($modalListDates as $i => $item)
                                            <tr>
                                                <th>{{ Carbon\Carbon::parse($item['tanggal'])->format('d/m/Y') }}</th>
                                                <td>
                                                    <select class="form-control form-control-sm jadwal-select"
                                                        data-index="{{ $i }} "
                                                        wire:model="selectedJadwal.{{ $i }}">
                                                        <option value="-">-- pilih --</option>
                                                        @foreach ($modalTimeTemplate as $time)
                                                            <option value='@json($time)'
                                                                data-json='@json($time)'>
                                                                {{ $time['name'] }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="checkin_time"></td>
                                                <td class="work_time">-</td>
                                                <td class="checkin_deadline_time">-</td>
                                                <td class="checkout_time">-</td>
                                                <td class="checkout_deadline_time">-</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                    <div class="text-right mt-4">
                        <button type="button" class="btn btn-light waves-effect px-5" data-dismiss="modal">Batal</button>
                        <button type="submit" id=""
                            class="btn btn-danger waves-effect waves-light px-5">Simpan
                            Data</button>
                    </div>
                </div><!-- /.modal-content -->
            </div>
        </form>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@push('push-script')
    <script>
        $(document).on('change', '.jadwal-select', function() {
            let row = $(this).closest('tr');
            let selected = $(this).find(':selected');
            let index = $(this).data('index');

            if (selected) {
                let data = JSON.parse(selected.attr('data-json'));
                console.log(data);

                row.find('.checkin_time').text(data.checkin_time || '-');
                row.find('.work_time').text(data.work_time || '-');
                row.find('.checkin_deadline_time').text(data.checkin_deadline_time || '-');
                row.find('.checkout_time').text(data.checkout_time || '-');
                row.find('.checkout_deadline_time').text(data.checkout_deadline_time || '-');

                // Livewire.dispatch('setScheduleTime', {
                //     index: index,
                //     scheduleId: scheduleId,
                //     jadwal: data.name,
                //     times: {
                //         checkin_time: data.checkin_time,
                //         work_time: data.work_time,
                //         checkin_deadline_time: data.checkin_deadline_time,
                //         checkout_time: data.checkout_time,
                //         checkout_deadline_time: data.checkout_deadline_time
                //     }
                // });

                // @this.set('dtScheduleBebas.' + index + '.jadwal', data.name);
            } else {
                row.find('.checkin_time').text('-');
                row.find('.work_time').text('-');
                row.find('.checkin_deadline_time').text('-');
                row.find('.checkout_time').text('-');
                row.find('.checkout_deadline_time').text('-');
            }
        });
    </script>
@endpush
