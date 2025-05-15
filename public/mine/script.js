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
    const hariKe = moment(tanggalYMD).isoWeekday(); // 1 = Senin, ..., 7 = Minggu

    // Cari jadwal yang aktif pada tanggal ini
    const jadwal = (row.master_schedules || []).find(sch => {
        const eff = moment(sch.pivot.effective_at).format('YYYY-MM-DD');
        const exp = sch.pivot.expired_at ? moment(sch.pivot.expired_at).format('YYYY-MM-DD') : null;
        return tanggalYMD >= eff && (!exp || tanggalYMD <= exp);
    });

    if (!jadwal) return '-';

    // Tentukan apakah ini hari kerja tergantung jenis jadwal
    let isHariKerja = false;

    if (jadwal.type === 'Tetap') {
        isHariKerja = jadwal.day_work.regular[hariKe] || jadwal.day_work.lembur[hariKe];
    }

    if (jadwal.type === 'Rotasi') {
        const startDate = moment(jadwal.day_work.start_date, 'YYYY-MM-DD');
        const selisihHari = moment(tanggalYMD).diff(startDate, 'days');
        const totalSiklus = parseInt(jadwal.day_work.work_day) + parseInt(jadwal.day_work.off_day);
        const posisi = selisihHari % totalSiklus;
        isHariKerja = posisi < parseInt(jadwal.day_work.work_day);
    }

    if (!isHariKerja) return '-';

    // Batas waktu dari jadwal
    const checkinStart = moment(`${tanggalYMD} ${jadwal.checkin_time}`);
    const checkinEnd = moment(`${tanggalYMD} ${jadwal.checkin_deadline_time}`);
    const checkoutTime = moment(`${tanggalYMD} ${jadwal.checkout_time}`);

    // Ambil log hari ini
    const logs = (row.log_attendances || [])
        .filter(log => log.time.startsWith(tanggalYMD))
        .map(log => moment(log.time));

    if (type === 'in') {
        const logMasuk = logs
            .filter(time => time.isBetween(checkinStart, checkinEnd, null, '[]'))
            .sort((a, b) => a - b)[0];

        if (!logMasuk) return '-';

        const jam = logMasuk.format('HH:mm');
        const warna = logMasuk.isSameOrBefore(checkinEnd) ? 'text-success' : 'text-danger';
        const label = logMasuk.isSameOrBefore(checkinEnd) ? 'on time' : 'terlambat';

        return `
            <span style="display:flex; flex-direction:column; line-height:1">
                ${jam}
                <small class="${warna}">(${label})</small>
            </span>
        `;
    }

    if (type === 'out') {
        const logKeluar = logs
            .filter(time => time.isAfter(checkinEnd))
            .sort((a, b) => b - a)[0];

        if (!logKeluar) return '-';

        const jam = logKeluar.format('HH:mm');
        const warna = logKeluar.isSameOrAfter(checkoutTime) ? 'text-success' : 'text-danger';
        const label = logKeluar.isSameOrAfter(checkoutTime) ? 'on time' : 'Plg Cepat';

        return `
            <span style="display:flex; flex-direction:column; line-height:1">
                ${jam}
                <small class="${warna}">(${label})</small>
            </span>
        `;
    }

    return '-';
}
