$(function () {

    $('#data-table-default').on('click', '.btn-view', function(event){
        event.preventDefault();
        var icon = $(this).find('i');
        icon.removeClass().addClass('fas fa-circle-notch fa-spin');
        $.post(base_url + '/Fetch', { rate_id: $(this).attr('data-id') })
          .done(function (data) {
            var obj = jQuery.parseJSON(data);
            if (obj.status === 'success') {
                $('#customer-name').val(obj.data.customer);
                $('#customer-note').html(obj.data.note);
                $('#customer-comment').val(obj.data.comment);
                setTimeout(() => {
                    icon.removeClass().addClass('fa fa-eye');
                    $('#modal-dialog').modal('show');
                }, 500);
            }else{
                swal('Echoué!', 'opération a échoué, veuillez réessayer', 'error');
            }
        })
    })
})