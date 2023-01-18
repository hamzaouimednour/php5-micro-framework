$(function () {

    $('#data-table-default').on('click', '.btn-view', function(event){
        event.preventDefault();
        var icon = $(this).find('i');
        icon.removeClass().addClass('fas fa-circle-notch fa-spin');
        $.post(base_url + '/Fetch', { req_id: $(this).attr('data-id') })
          .done(function (data) {
            var obj = jQuery.parseJSON(data);
            if (obj.status === 'success') {
                $('#user-name').val(obj.data.name);
                $('#user-auth').val(obj.data.auth);
                $('#user-request').val(obj.data.suj);
                $('#user-desc').val(obj.data.desc);
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