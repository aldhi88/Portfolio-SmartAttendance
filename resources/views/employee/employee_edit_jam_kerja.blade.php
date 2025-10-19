@foreach ($dtEdit['schedule'] as $item)
    @if ($item['type']=="Bebas")

    <div class="modal fade" id="modalJamKerja{{$item['id']}}" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-xl">
            {{-- <form wire:submit.prevent="wireSubmitScheduleBebas"> --}}
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                        <div class="text-right mt-4">
                            <button type="button" class="btn btn-light waves-effect px-5" data-dismiss="modal">Batal</button>
                            <button type="submit" id=""
                                class="btn btn-danger waves-effect waves-light px-5" data-dismiss="modal">Simpan Data</button>
                        </div>
                        <div class="row">
                            <div class="col">
                                <h3 class="text-center">Set Waktu Jadwal Bebas ({{ $item['name'] }})</h3>
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
                                            @isset($dtScheduleBebas[$item['id']])
                                                @foreach ($dtScheduleBebas[$item['id']] as $i => $val)
                                                    <tr>
                                                        <th>{{ Carbon\Carbon::parse($val['tanggal'])->format('d/m/Y') }}</th>
                                                        <td>
                                                            <select
                                                                class="form-control form-control-sm jadwal-select"
                                                                wire:change="applyTemplate({{ $item['id'] }}, {{ $i }}, $event.target.value)"
                                                            >
                                                                <option value="">-- pilih --</option>
                                                                @foreach ($item['day_work']['time'] as $time)
                                                                    <option value='@json($time)'>{{ $time['name'] }}</option>
                                                                @endforeach
                                                            </select>

                                                        </td>
                                                        <td class="checkin_time">
                                                            <input type="time" readonly class="form-control form-control-sm"
                                                                wire:model="dtScheduleBebas.{{ $item['id'] }}.{{ $i }}.day_work.checkin_time">
                                                        </td>
                                                        <td class="work_time">
                                                            <input type="time" readonly class="form-control form-control-sm"
                                                                wire:model="dtScheduleBebas.{{ $item['id'] }}.{{ $i }}.day_work.work_time">
                                                        </td>
                                                        <td class="checkin_deadline_time">
                                                            <input type="time" readonly class="form-control form-control-sm"
                                                                wire:model="dtScheduleBebas.{{ $item['id'] }}.{{ $i }}.day_work.checkin_deadline_time">
                                                        </td>
                                                        <td class="checkout_time">
                                                            <input type="time" readonly class="form-control form-control-sm"
                                                                wire:model="dtScheduleBebas.{{ $item['id'] }}.{{ $i }}.day_work.checkout_time">
                                                        </td>
                                                        <td class="checkout_deadline_time">
                                                            <input type="time" readonly class="form-control form-control-sm"
                                                                wire:model="dtScheduleBebas.{{ $item['id'] }}.{{ $i }}.day_work.checkout_deadline_time">
                                                        </td>

                                                    </tr>
                                                @endforeach
                                            @endisset

                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                        <div class="text-right mt-4">
                            <button type="button" class="btn btn-light waves-effect px-5" data-dismiss="modal">Batal</button>
                            <button type="submit" id=""
                                class="btn btn-danger waves-effect waves-light px-5" data-dismiss="modal">Simpan Data</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div>
            {{-- </form> --}}
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    @endif
@endforeach
