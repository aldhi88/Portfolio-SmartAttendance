$(document).ready(function () {
    $('.loading').fadeOut(500);
    initLivewireFileUploadProgress();

    const path = window.location.pathname;        // ex: "/jadwal-kerja/create/tetap"
    const segments = path.split('/').filter(Boolean);
    const firstSegment = segments[0];      // ambil segmen pertama setelah slash
    const candidates = [];

    for (let i = segments.length; i > 0; i--) {
        candidates.push(segments.slice(0, i).join('-'));
    }
    candidates.push(...segments);

    const activeSegment = candidates.find((item) => $(`.child.${item}`).length || $(`.parent.${item}`).length) || firstSegment;

    // Cari menu parent dan child berdasarkan segmen
    const parentSelector = `.parent.${activeSegment}`;
    const childSelector = `.child.${activeSegment}`;

    // Aktifkan parent menu
    $(parentSelector).addClass('mm-active');
    $(parentSelector + ' > a').addClass('mm-active');
    $(parentSelector + ' > ul.sub-menu').addClass('mm-collapse mm-show');

    // Aktifkan child menu (kalau ada)
    $(childSelector).addClass('mm-active');
    $(childSelector + ' > a').addClass('active');
    $(childSelector).parents('li.parent').each(function () {
        $(this).addClass('mm-active');
        $(this).children('a').addClass('mm-active');
        $(this).children('ul.sub-menu').addClass('mm-collapse mm-show');
    });
});

function initLivewireFileUploadProgress() {
    const isRdpPage = window.location.pathname.split('/').filter(Boolean)[0] === 'rdp';

    function hasLivewireModel(input) {
        return Array.from(input.attributes).some((attr) => attr.name.indexOf('wire:model') === 0);
    }

    function shouldBind(input) {
        return input
            && input.type === 'file'
            && hasLivewireModel(input)
            && (isRdpPage || input.closest('#modalEditSignature'));
    }

    function getStatusElement(input) {
        if (input.nextElementSibling && input.nextElementSibling.classList.contains('livewire-file-upload-progress')) {
            return input.nextElementSibling;
        }

        const status = document.createElement('div');
        status.className = 'livewire-file-upload-progress d-none';
        status.innerHTML = `
            <div class="d-flex justify-content-between align-items-center small mb-1">
                <span class="upload-status-text">Menunggu file dipilih.</span>
                <span class="upload-status-percent">0%</span>
            </div>
            <div class="progress">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"></div>
            </div>
        `;
        input.insertAdjacentElement('afterend', status);

        return status;
    }

    function setStatus(input, text, progress, state) {
        const status = getStatusElement(input);
        const percent = Math.max(0, Math.min(100, parseInt(progress || 0, 10)));
        const bar = status.querySelector('.progress-bar');

        status.classList.remove('d-none');
        status.querySelector('.upload-status-text').textContent = text;
        status.querySelector('.upload-status-percent').textContent = `${percent}%`;

        bar.style.width = `${percent}%`;
        bar.setAttribute('aria-valuenow', percent);
        bar.classList.remove('bg-success', 'bg-danger', 'bg-warning', 'progress-bar-animated');

        if (state === 'success') {
            bar.classList.add('bg-success');
        } else if (state === 'error') {
            bar.classList.add('bg-danger');
        } else if (state === 'warning') {
            bar.classList.add('bg-warning');
        } else {
            bar.classList.add('progress-bar-animated');
        }
    }

    function bindInput(input) {
        if (!shouldBind(input) || input.dataset.uploadProgressBound === '1') {
            return;
        }

        input.dataset.uploadProgressBound = '1';

        input.addEventListener('change', () => {
            if (input.files && input.files.length > 0) {
                setStatus(input, 'Menyiapkan upload file. Mohon tunggu sebelum submit.', 0, 'loading');
            }
        });

        input.addEventListener('livewire-upload-start', () => {
            setStatus(input, 'Mengunggah file. Mohon tunggu sampai selesai sebelum submit.', 0, 'loading');
        });

        input.addEventListener('livewire-upload-progress', (event) => {
            setStatus(input, 'Mengunggah file. Mohon tunggu sampai selesai sebelum submit.', event.detail.progress, 'loading');
        });

        input.addEventListener('livewire-upload-finish', () => {
            setStatus(input, 'Upload selesai. File siap dikirim.', 100, 'success');
        });

        input.addEventListener('livewire-upload-error', () => {
            setStatus(input, 'Upload gagal. Periksa koneksi/ukuran file lalu pilih ulang.', 100, 'error');
        });

        input.addEventListener('livewire-upload-cancel', () => {
            setStatus(input, 'Upload dibatalkan. Pilih file ulang bila diperlukan.', 0, 'warning');
        });
    }

    function bindAll() {
        document.querySelectorAll('input[type="file"]').forEach(bindInput);
    }

    bindAll();

    const observer = new MutationObserver(() => bindAll());
    observer.observe(document.body, { childList: true, subtree: true });
}

function initSearchCol(table, headerId, inputClass) {
    $(headerId).on('input change', '.' + inputClass, function () {
        const colIndex = $(this).parent().index();
        const searchVal = this.value;

        // Jalankan search hanya kalau value-nya berubah
        if (table.column(colIndex).search() !== searchVal) {
            table.column(colIndex).search(searchVal).draw(false);
        }
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
    0: 'Senin',
    1: 'Selasa',
    2: 'Rabu',
    3: 'Kamis',
    4: 'Jumat',
    5: 'Sabtu',
    6: 'Minggu'
};

function launchConfetti() {

    const canvas = document.getElementById('confetti-canvas');
    if (!canvas) {
        console.warn('Canvas confetti tidak ditemukan.');
        return;
    }
    const ctx = canvas.getContext('2d');
    if (!ctx) {
        console.warn('Canvas context tidak tersedia.');
        return;
    }

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

function formatAngka(angka) {
    return Number(angka) % 1 === 0 ? angka : Number(angka).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}
