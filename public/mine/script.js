$( document ).ready(function() {
    $('.loading').fadeOut(500);
});

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



$(".modal").on("shown.bs.modal", function(e) {
    $('#'+e.target.id+' input.modalOnFocus').focus();
});

$(".modal").on("show.bs.modal", function(e) {
    var emit = $(e.relatedTarget).data('emit');
    if( emit !== undefined){
        var json = $(e.relatedTarget).data('json');
        Livewire.dispatch(emit, {data:json});
    }
});

window.addEventListener('closeModal', param => {
    $('#'+param.detail.id).modal('hide');
});

window.addEventListener('showModal', param => {
    $('#'+param.detail.id).modal('show');
});

window.addEventListener('reloadDT', param => {
    eval(param.detail.data).ajax.reload();
});


