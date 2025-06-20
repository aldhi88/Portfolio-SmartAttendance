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
        showDuration: "500",
        hideDuration: "500",
        timeOut: "1000",
        extendedTimeOut: "500",
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

function launchConfetti() {
    const canvas = document.getElementById('confetti-canvas');
    const ctx = canvas.getContext('2d');
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;

    const confettiCount = 150;
    const confetti = [];

    for (let i = 0; i < confettiCount; i++) {
        confetti.push({
            x: canvas.width - Math.random() * 1000, // sudut kanan atas
            y: -Math.random() * canvas.height,
            r: Math.random() * 6 + 4,
            d: Math.random() * confettiCount,
            color: `hsl(${Math.floor(Math.random() * 360)}, 70%, 60%)`,
            tilt: Math.floor(Math.random() * 10) - 10,
            tiltAngleIncremental: (Math.random() * 0.07) + 0.05,
            tiltAngle: 0
        });
    }

    let animationId;
    const start = performance.now();
    const duration = 2000; // ms

    function draw(progress) {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.globalAlpha = 1 - progress; // efek fade out

        confetti.forEach((confetto, i) => {
            ctx.beginPath();
            ctx.lineWidth = confetto.r;
            ctx.strokeStyle = confetto.color;
            ctx.moveTo(confetto.x + confetto.tilt + (confetto.r / 2), confetto.y);
            ctx.lineTo(confetto.x + confetto.tilt, confetto.y + confetto.tilt + (confetto.r / 2));
            ctx.stroke();
        });

        ctx.globalAlpha = 1; // reset untuk keamanan render selanjutnya
    }

    function update() {
        confetti.forEach((confetto, i) => {
            confetto.tiltAngle += confetto.tiltAngleIncremental;
            confetto.y += (Math.cos(confetto.d) + 3 + confetto.r / 2) / 2;
            confetto.tilt = Math.sin(confetto.tiltAngle - (i / 3)) * 15;
        });
    }

    function animate(now) {
        const elapsed = now - start;
        const progress = Math.min(elapsed / duration, 1);

        update();
        draw(progress);

        if (progress < 1) {
            animationId = requestAnimationFrame(animate);
        } else {
            cancelAnimationFrame(animationId);
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }
    }

    animationId = requestAnimationFrame(animate);
}
