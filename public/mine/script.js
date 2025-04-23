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
        positionClass: "toast-top-right",
        preventDuplicates: false,
        onclick: null,
        showDuration: "1000",
        hideDuration: "1000",
        timeOut: "5000",
        extendedTimeOut: "1000",
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "fadeIn",
        hideMethod: "fadeOut"
    })
});



$(".modal").on("shown.bs.modal", function (e) {
    $('#' + e.target.id + ' input.modalOnFocus').focus();
});

// $(".modal").on("show.bs.modal", function (e) {
//     var emit = $(e.relatedTarget).data('emit');
//     if (emit !== undefined) {
//         var json = $(e.relatedTarget).data('json');
//         Livewire.dispatch(emit, { data: json });
//     }
// });

window.addEventListener('closeModal', param => {
    $('#' + param.detail.id).modal('hide');
});

window.addEventListener('showModal', param => {
    $('#' + param.detail.id).modal('show');
});

window.addEventListener('reloadDT', param => {
    // eval(param.detail.data).ajax.reload();
    window[param.detail.data].ajax.reload();
});
// window.addEventListener('show-modal', () => {
//     const modal = new bootstrap.Modal(document.getElementById('the-modal'));
//     modal.show();
// });

// window.addEventListener('hide-modal', () => {
//     const modalEl = document.getElementById('the-modal');
//     const modal = bootstrap.Modal.getInstance(modalEl);
//     modal.hide();
// });


