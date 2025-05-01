$(document).ready(function () {
    $('.loading').fadeOut(500);

    const path = window.location.pathname;

    // Cek apakah path mengandung '/perusahaan'
    if (path.includes('/perusahaan')) {
        // Tambahkan class ke parent dan child menu
        $('.parent.perusahaan').addClass('mm-active');
        $('.parent.perusahaan > a').addClass('mm-active');
        $('.parent.perusahaan > ul.sub-menu').addClass('mm-collapse mm-show');

        // Cek apakah sedang di halaman create
        if (path.includes('/perusahaan/create')) {
            $('.parent.perusahaan .child.create').addClass('mm-active');
            $('.parent.perusahaan .child.create a').addClass('active');
        } else {
            // Asumsikan ini halaman index
            $('.parent.perusahaan .child.create').addClass('mm-active');
            $('.parent.perusahaan .child.create a').addClass('active');
        }
    }
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

