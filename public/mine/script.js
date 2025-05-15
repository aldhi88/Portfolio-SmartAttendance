$(document).ready(function () {
    $('.loading').fadeOut(500);

    const path = window.location.pathname;        // ex: "/jadwal-kerja/create/tetap"
    const firstSegment = path.split('/')[1];      // ambil segmen pertama setelah slash

    // Cari menu parent dan child berdasarkan segmen
    const parentSelector = `.parent.${firstSegment}`;
    const childSelector = `.child.${firstSegment}`;

    // Aktifkan parent menu
    $(parentSelector).addClass('mm-active');
    $(parentSelector + ' > a').addClass('mm-active');
    $(parentSelector + ' > ul.sub-menu').addClass('mm-collapse mm-show');

    // Aktifkan child menu (kalau ada)
    $(childSelector).addClass('mm-active');
    $(childSelector + ' > a').addClass('active');
});

function initSearchCol(table, headerId, inputClass) {
    $(headerId).on('keyup', '.' + inputClass, function () {
        table.column($(this).parent().index()).search(this.value).draw(false);
    });

    $(headerId).on('change', '.' + inputClass, function () {
        table.column($(this).parent().index()).search(this.value).draw();
    });
}

function clearValidation(id) {
    document.getElementById(id).classList.remove("is-invalid");
}

window.addEventListener('alert', event => {
    toastr[event.detail.data.type](event.detail.data.message, event.detail.data.title ?? '', {
        closeButton: true,
        debug: false,
        newestOnTop: false,
        progressBar: false,
        positionClass: "toast-top-center",
        preventDuplicates: false,
        onclick: null,
        showDuration: "1000",
        hideDuration: "1000",
        timeOut: "3000",
        extendedTimeOut: "1000",
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "fadeIn",
        hideMethod: "fadeOut",
    })
});



$(".modal").on("shown.bs.modal", function (e) {
    const modal = $('#' + e.target.id);
    modal.find('input.modalOnFocus').focus();
});
$(".modal").on("show.bs.modal", function (e) {
    const modal = $('#' + e.target.id);
    modal.find('form.restart').each(function () {
        this.reset();
    });
});

window.addEventListener('closeModal', param => {
    $('#' + param.detail.id).modal('hide');
});

window.addEventListener('showModal', param => {
    $('#' + param.detail.id).modal('show');
});

window.addEventListener('reloadDT', param => {
    window[param.detail.data].ajax.reload();
});

const getHariIndo = {
    1: 'Senin',
    2: 'Selasa',
    3: 'Rabu',
    4: 'Kamis',
    5: 'Jumat',
    6: 'Sabtu',
    7: 'Minggu'
};


function getAbsenWaktu(row, tanggalLabel, type = 'in') {
    const tanggalYMD = moment(tanggalLabel, 'DD-MMM-YYYY').format('YYYY-MM-DD');
    const hariKe = moment(tanggalYMD).isoWeekday();

    const semuaJadwal = (row.master_schedules || []).filter(sch => {
        const eff = moment(sch.pivot.effective_at).format('YYYY-MM-DD');
        const exp = sch.pivot.expired_at ? moment(sch.pivot.expired_at).format('YYYY-MM-DD') : null;
        return tanggalYMD >= eff && (!exp || tanggalYMD <= exp);
    });

    if (!semuaJadwal.length) return '-';

    const jadwal = semuaJadwal[0];

    let isHariKerja = false;
    let isHariKerjaRegular = false;
    for (const sch of semuaJadwal) {
        if (sch.type === 'Tetap') {
            if (sch.day_work.regular[hariKe]) isHariKerjaRegular = true;
            const aktif = sch.day_work.regular[hariKe] || sch.day_work.lembur[hariKe];
            if (aktif) isHariKerja = true;
        }
        if (sch.type === 'Rotasi') {
            const startDate = moment(sch.day_work.start_date, 'YYYY-MM-DD');
            const selisihHari = moment(tanggalYMD).diff(startDate, 'days');
            const totalSiklus = parseInt(sch.day_work.work_day) + parseInt(sch.day_work.off_day);
            const posisi = selisihHari % totalSiklus;
            const aktif = posisi < parseInt(sch.day_work.work_day);
            if (aktif) isHariKerja = true;
        }
    }

    if (!isHariKerja) {
        const isRotasi = semuaJadwal.some(j => j.type === 'Rotasi');
        if (isRotasi) {
            return `
                <span style="display:flex; flex-direction:column; line-height:1">
                    -
                    <small class="text-muted">Off</small>
                </span>
            `;
        }
        return '-';
    }

    const checkinStart = moment(`${tanggalYMD} ${jadwal.checkin_time}`);
    let workTime = moment(`${tanggalYMD} ${jadwal.work_time}`);
    if (jadwal.work_time < jadwal.checkin_time) workTime.add(1, 'day');

    let checkinEnd = moment(`${tanggalYMD} ${jadwal.checkin_deadline_time}`);
    if (jadwal.checkin_deadline_time < jadwal.checkin_time) checkinEnd.add(1, 'day');

    let checkoutTime = moment(`${tanggalYMD} ${jadwal.checkout_time}`);
    if (jadwal.checkout_time < jadwal.checkin_time) checkoutTime.add(1, 'day');

    const logs = (row.log_attendances || [])
        .map(log => moment(log.time))
        .filter(time => {
            const logDate = time.format('YYYY-MM-DD');
            return logDate === tanggalYMD || logDate === moment(tanggalYMD).add(1, 'day').format('YYYY-MM-DD');
        });

    const logMasuk = logs
        .filter(time => time.isBetween(checkinStart, checkinEnd, null, '[]'))
        .sort((a, b) => a - b)[0];

    const logKeluar = logs
        .filter(time => time.isAfter(checkinEnd))
        .sort((a, b) => b - a)[0];

    const tidakAdaLog = !logMasuk && !logKeluar;

    if (tidakAdaLog && isHariKerjaRegular) {
        return `
            <span style="display:flex; flex-direction:column; line-height:1">
                -
                <small class="text-danger">Alpha</small>
            </span>
        `;
    }

    if (type === 'in') {
        if (!logMasuk) return '-';

        const jam = logMasuk.format('HH:mm');
        let warna = 'text-success';
        let label = 'on time';

        if (logMasuk.isAfter(workTime) && logMasuk.isSameOrBefore(checkinEnd)) {
            warna = 'text-danger';
            label = 'terlambat';
        }

        return `
            <span style="display:flex; flex-direction:column; line-height:1">
                ${jam}
                <small class="${warna}">(${label})</small>
            </span>
        `;
    }

    if (type === 'out') {
        if (!logKeluar) return '-';

        const jam = logKeluar.format('HH:mm');
        let warna = 'text-danger';
        let label = 'Plg Cepat';

        if (logKeluar.isSameOrAfter(checkoutTime)) {
            warna = 'text-success';
            label = 'on time';
        }

        return `
            <span style="display:flex; flex-direction:column; line-height:1">
                ${jam}
                <small class="${warna}">(${label})</small>
            </span>
        `;
    }

    return '-';
}
