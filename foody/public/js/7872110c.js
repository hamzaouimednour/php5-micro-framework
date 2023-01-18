$(function () {
    $('#view-order-modal').on('shown.bs.modal', function (e) {
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
    })

    $('#orders-table').on('click', '.btn-view-order', function (event) {
        event.preventDefault();
        var orderRef = $(this).attr('data-id');
        var btn_icon = $(this).find('i');
        $(this).find('i').removeClass().addClass('fas fa-circle-notch fa-spin');
        setTimeout(function () {
            $('#modal-title').text(orderRef);
            btn_icon.removeClass().addClass('mdi mdi-eye');
            $('#view-order-modal').modal('show').find('#modal-form').load(root_url + 'user/order/' + orderRef );

        }, 500);
    })

    $('.modal').on('click', '.btn-cancel-order', function(event){
        event.preventDefault();
        swal({
            title: "Êtes-vous sûr?",
            text: "êtes-vous sûr de vouloir annuler votre commande ?",
            icon: "info",
            buttons: {
              cancel: {
                text: "Retour",
                value: null,
                visible: !0,
                className: "btn btn-default",
                closeModal: !0
              },
              confirm: {
                text: "OUI, CONFIRMER L'ANULATION",
                value: !0,
                visible: !0,
                className: "btn btn-secondary",
                closeModal: 0
              }
            }
          })
            .then((willAccept) => {
              if (willAccept) {
                $.post(root_url + 'user/orders/CancelOrder', { order_id: $(this).attr('data-order-id') })
                  .done(function (data) {
                    try {
                      var obj = jQuery.parseJSON(data);
                      if (obj.status === 'success') {
                        swal("Succès!", obj.info, {
                            icon: "success",
                            closeOnClickOutside: false,
                            closeOnEsc: false,
                          }).then((willAccept) => {
                              if (willAccept) {
                                  location.reload();
                              }
                        })
                        $('.modal-footer').html('<button type="button" class="btn btn-default"> Retour</button>')
                      } else {
                        swal({
                          title: "Échoué!",
                          text: obj.info,
                          icon: "error",
                          closeOnEsc: false,
                          closeOnClickOutside: false
                        })
                      }
                    } catch (err) {
                      swal({
                        title: "Échoué!",
                        text: "Operation echoué, réessayer une autre fois!",
                        icon: "error",
                        closeOnEsc: false,
                        closeOnClickOutside: false
                      })
                    }
      
                  })
                  .fail(function () {
                    swal("Erreur!", "Operation echoué, réessayer une autre fois!", {
                      icon: "error",
                    });
                  });
              }
            });
    })
    $('#orders-table').on('click', '.btn-cancel-order', function(event){
        event.preventDefault();
        swal({
            title: "Êtes-vous sûr?",
            text: "êtes-vous sûr de vouloir annuler votre commande ?",
            icon: "info",
            buttons: {
              cancel: {
                text: "Retour",
                value: null,
                visible: !0,
                className: "btn btn-default",
                closeModal: !0
              },
              confirm: {
                text: "OUI, CONFIRMER L'ANULATION",
                value: !0,
                visible: !0,
                className: "btn btn-secondary",
                closeModal: 0
              }
            }
          })
            .then((willAccept) => {
              if (willAccept) {
                $.post(root_url + 'user/orders/CancelOrder', { order_id: $(this).attr('data-order-id') })
                  .done(function (data) {
                    //alert(data)
                    try {
                      var obj = jQuery.parseJSON(data);
                      if (obj.status === 'success') {
                        swal("Succès!", obj.info, {
                          icon: "success",
                          closeOnClickOutside: false,
                          closeOnEsc: false,
                        }).then((willAccept) => {
                            if (willAccept) {
                                location.reload();
                            }
                        })
                        $('.modal-footer').html('<button type="button" class="btn btn-default"> Retour</button>')
                      } else {
                        swal({
                          title: "Échoué!",
                          text: obj.info,
                          icon: "error",
                          closeOnEsc: false,
                          closeOnClickOutside: false
                        })
                      }
                    } catch (err) {
                      swal({
                        title: "Échoué!",
                        text: "Operation echoué, réessayer une autre fois!",
                        icon: "error",
                        closeOnEsc: false,
                        closeOnClickOutside: false
                      })
                    }
      
                  })
                  .fail(function () {
                    swal("Erreur!", "Operation echoué, réessayer une autre fois!", {
                      icon: "error",
                    });
                  });
                  
              }
            });
    })
})