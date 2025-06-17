<div>
    <div class="loading-50" wire:loading>
        <div class="loader"></div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title')
                {{-- <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    <a href="{{ route('karyawan.index') }}" class="btn btn-secondary">
                        Kembali <i class="fas fa-angle-right ml-1"></i>
                    </a>
                </div> --}}
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-2 pt-2">
            <h5>Pilih Tahun:</h5>
        </div>
        <div class="col-md-2">
            <select class="form-control" wire:model.live="year" wire:change="genCalendar">
                @for ($i = date('Y'); $i >= 2024; $i--)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        @foreach ($calendars as $indexCalendar => $calendar)
                            <div class="col-md-4 mb-4" wire:key="{{ $calendar['label'] }}-{{ $year }}">
                                <h5 class="text-center">{{ $calendar['label'] }} {{ $year }}</h5>
                                <table class="table table-sm table-bordered text-center">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-danger">Min</th>
                                            <th>Sen</th>
                                            <th>Sel</th>
                                            <th>Rab</th>
                                            <th>Kam</th>
                                            <th>Jum</th>
                                            <th>Sab</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $counter = 0;
                                        @endphp
                                        <tr>
                                            @for ($i = 0; $i < $calendar['start_dow']; $i++)
                                                <td></td>
                                                @php $counter++; @endphp
                                            @endfor

                                            @foreach ($calendar['dates'] as $d)
                                                @php
                                                    $isSunday = $d['dow'] === 0;
                                                    $isHoliday = in_array($d['date'], $hariLibur);
                                                @endphp
                                                <td
                                                    style="cursor: pointer; transition: background-color 0.2s;"
                                                    onmouseover="this.style.backgroundColor='#f0f0f0';"
                                                    onmouseout="this.style.backgroundColor='';"
                                                    wire:click="{{ $isHoliday ? "delLibur('{$d['date']}')" : "addLibur('{$d['date']}')" }}"
                                                    class="
                                                        {{ $isHoliday ? 'bg-danger text-white font-weight-bold' : '' }}
                                                        {{ !$isHoliday && $isSunday ? 'text-danger font-weight-bold' : '' }}
                                                    "
                                                >
                                                    {{ $d['day'] }}
                                                </td>

                                                @php $counter++; @endphp

                                                @if ($counter % 7 == 0)
                                                    </tr><tr>
                                                @endif
                                            @endforeach

                                            @while ($counter % 7 != 0)
                                                <td></td>
                                                @php $counter++; @endphp
                                            @endwhile
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>








</div>
