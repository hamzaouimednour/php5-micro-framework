$(function () {

    var table = 0 !== $("#data-table-orders").length && $("#data-table-orders").DataTable({
        responsive: !0,
        "order": [[ 2, "desc" ]],
        "lengthMenu": [10, 15, 25, 50, 100],
        "pageLength": 15
    })

    $('#modal-dialog').on('shown.bs.modal', function (e) {
        $('[data-toggle="popover"]').popover({container:'body', trigger: 'hover click', placement:"left"});
        $('.modal').on('click touchstart', '.comment-popover', function (e) {
            e.preventDefault();
            $(this).popover('show');
        });

        $('body').on('click', function (e) {
            $('[data-toggle="popover"]').each(function () {
                if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                    $(this).popover('hide');
                }
            });
        });
    });
    
    $('#data-table-orders').on('click', '.btn-view', function (event) {

        event.preventDefault();
        var orderRef = $(this).attr('data-id');
        var a = $(this).closest(".panel");
        if (!$(a).hasClass("panel-loading")) {
          var t = $(a).find(".panel-body");
          $(a).addClass("panel-loading");
          $(t).prepend('<div class="panel-loader"><span class="spinner-small"></span></div>');
        }
        setTimeout(function () {
            $('#modal-title-order').text(orderRef);
            $(a).removeClass("panel-loading").find(".panel-loader").remove();
            var postURL = base_url.replace(new RegExp("(.*/)[^/]+$"),"$1");
            $('#modal-dialog').modal('show').find('#modal-form').load(postURL + 'Invoice/' + orderRef + "/webmaster");
          }, 500);
    });
})