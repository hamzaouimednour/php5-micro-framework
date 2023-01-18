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
    
    $('#data-table-orders').on('click', '.btn-comment', function (event) {
      event.preventDefault();
      $('#order-ref').val($(this).attr('data-id'));
      $.post(base_url + '/OrderComment', {order_id: $(this).attr('data-id') })
        .done(function (data) {
          var obj = jQuery.parseJSON(data);
          if (obj.status === 'success') {
            $('#restaurant-comment').text(obj.info);
          }
          $('#modal-comment').modal('show');
        })
    })

    $('#modal-form-comment').on('submit', function(event){
      event.preventDefault();
      if ($('#modal-form-comment')[0].checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
      } else {
          $('#comment-save').find('i').removeClass().addClass('fas fa-circle-notch fa-spin');
          $.post(base_url + '/OrderCommentSubmit', $(this).serialize()) // Serialization Data

            .done(function (data) {
              try {
                var result = jQuery.parseJSON(data);
                if (result.status !== undefined) {
                  $("#comment-info-section").html(result.info);
                  $('#comment-save').find('i').removeClass().addClass('fas fa-check-circle');
                  setTimeout(() => {
                    $('#comment-save').find('i').removeClass().addClass('fas fa-save');
                  }, 3000);
                  $("#modal-form-comment").removeClass('was-validated');
                  removeAlert("#auto-dismiss", 5000);
                }
              } catch (error) {
                swal("Erreur!", "Opération échouée, Réssayer une autre fois!", "error");
              }
            });
      }
      $("#modal-form-comment").addClass('was-validated');
    })

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
            $('#modal-dialog').modal('show').find('#modal-form').load(postURL + 'Invoice/' + orderRef );
          }, 500);
    });

    $('.modal').on('click', '#reject-order', function(event){
        event.preventDefault();
        swal({
          title: "Êtes-vous sûr?",
          text: "Tu veux vraiment rejeter la commande ?\n\nSi oui, donnez Le(s) raison(s).",
          content: {
            element: "textarea",
            attributes: {
              placeholder: "Ecrire les raisons ici ....",
              style: "margin-top: 0px; margin-bottom: 0px; height: 120px;",
              id: "text-reject"
            },
          },
          icon: "error",
          buttons: {
            cancel: {
              text: "Annuler",
              value: null,
              visible: !0,
              className: "btn btn-default",
              closeModal: !0
            },
            confirm: {
              text: "CONFIRMER LA REJET",
              value: !0,
              visible: !0,
              className: "btn btn-danger",
              closeModal: 0
            }
          }
        })
          .then((result) => {
            
            if (result) {
                if($('#text-reject').val() == ''){
                    swal('Attention!', 'Le champ est obligatoire!', 'warning');
                    return false;
                }
              $.post(base_url + '/OrderReject', { order_id: $(this).attr('data-order-id'), restaurant_comment: $('#text-reject').val()} )
                .done(function (data) {
                  try {
                    var obj = jQuery.parseJSON(data);
                    if (obj.status === 'success') {
                      swal("Succès!", "La commande a rejeté avec succès!", {
                        icon: "success",
                      })
                      $('.modal-footer').html('<button id="order-rejected" class="btn btn-danger text-uppercase" disabled><i class="fas fa-times-circle"></i>&nbsp;&nbsp; commande rejeté</a>');
                    } else {
                      swal({
                        title: "Erreur!",
                        text: obj.info,
                        icon: "error",
                        closeOnEsc: false,
                        closeOnClickOutside: false
                      })
                    }
                  } catch (err) {
                    swal({
                      title: "Erreur!",
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
          $('#modal-dialog').on('hidden.bs.modal', function (e) {
            location.reload();
          })
        })

    $('.modal').on('click', '#accept-order', function(event){
        event.preventDefault();
        swal({
          title: "Êtes-vous sûr?",
          text: "Voulez vous accepter la commande ?",
          icon: "info",
          buttons: {
            cancel: {
              text: "Annuler",
              value: null,
              visible: !0,
              className: "btn btn-default",
              closeModal: !0
            },
            confirm: {
              text: "CONFIRMER L'ACCEPTATION",
              value: !0,
              visible: !0,
              className: "btn btn-info",
              closeModal: 0
            }
          }
        })
          .then((willAccept) => {
            if (willAccept) {
              $.post(base_url + '/OrderAccept', { order_id: $(this).attr('data-order-id') })
                .done(function (data) {
                  try {
                    var obj = jQuery.parseJSON(data);
                    if (obj.status === 'success') {
                      swal("Succès!", "La commande a accepté avec succès!", {
                        icon: "success",
                      })
                      $('.modal-footer').html('<button class="btn btn-info text-uppercase" disabled><i class="fas fa-hourglass-half"></i>&nbsp;&nbsp; commande accepté & en préparation</a><button id="delivery-order" class="btn btn-green text-uppercase"><i class="fas fa-truck"></i>&nbsp;&nbsp; soumettre pour livraison ?</a>')
                    } else {
                      swal({
                        title: "Erreur!",
                        text: obj.info,
                        icon: "error",
                        closeOnEsc: false,
                        closeOnClickOutside: false
                      })
                    }
                  } catch (err) {
                    swal({
                      title: "Erreur!",
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
          $('#modal-dialog').on('hidden.bs.modal', function (e) {
            location.reload();
          })
        })


    $('.modal').on('click', '#delivery-order', function(event){
        event.preventDefault();
        swal({
          title: "Êtes-vous sûr?",
          text: "Voulez vous soumettre la commande pour la Livraison ?",
          icon: "info",
          buttons: {
            cancel: {
              text: "Annuler",
              value: null,
              visible: !0,
              className: "btn btn-default",
              closeModal: !0
            },
            confirm: {
              text: "CONFIRMER",
              value: !0,
              visible: !0,
              className: "btn btn-info",
              closeModal: 0
            }
          }
        })
          .then((willAccept) => {
            if (willAccept) {
              $.post(base_url + '/OrderDelivery', { order_id: $(this).parent().attr('data-order-id') })
                .done(function (data) {
                  try {
                    var obj = jQuery.parseJSON(data);
                    if (obj.status === 'success') {
                      swal("Succès!", "La commande soumise pour livraison avec succès!", {
                        icon: "success",
                      })
                      $('.modal-footer').html('<button class="btn btn-primary text-uppercase" disabled><i class="fas fa-truck"></i>&nbsp;&nbsp; la commande est en cours de livraison</a>')
                    } else {
                      swal({
                        title: "Erreur!",
                        text: obj.info,
                        icon: "error",
                        closeOnEsc: false,
                        closeOnClickOutside: false
                      })
                    }
                  } catch (err) {
                    swal({
                      title: "Erreur!",
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
                  })
                })
            }
        })
        $('#modal-dialog').on('hidden.bs.modal', function (e) {
          location.reload();
        })
    })
    $('.modal').on('click', '#order-delivred', function(event){
        event.preventDefault();
        swal({
          title: "Êtes-vous sûr?",
          text: "Voulez vous changé l'état du commande à Livré ?",
          icon: "info",
          buttons: {
            cancel: {
              text: "Annuler",
              value: null,
              visible: !0,
              className: "btn btn-default",
              closeModal: !0
            },
            confirm: {
              text: "CONFIRMER",
              value: !0,
              visible: !0,
              className: "btn btn-info",
              closeModal: 0
            }
          }
        })
          .then((willAccept) => {
            if (willAccept) {
              $.post(base_url + '/OrderDelivered', { order_id: $(this).parent().attr('data-order-id') })
                .done(function (data) {
                  try {
                    var obj = jQuery.parseJSON(data);
                    if (obj.status === 'success') {
                      swal("Succès!", "L'état du commande changé à livré avec succès!", {
                        icon: "success",
                      })
                      $('.modal-footer').html('<button class="btn btn-success text-uppercase" disabled><i class="fas fa-check-square"></i>&nbsp;&nbsp; commande livré</button>')
                    } else {
                      swal({
                        title: "Erreur!",
                        text: obj.info,
                        icon: "error",
                        closeOnEsc: false,
                        closeOnClickOutside: false
                      })
                    }
                  } catch (err) {
                    swal({
                      title: "Erreur!",
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
                  })
                })
            }
        })
        $('#modal-dialog').on('hidden.bs.modal', function (e) {
          location.reload();
        })
    })

    // Refresh cart Data
    setInterval(function(){
        // toastr.info('Are you the 6 fingered man?')
        
        $.post(base_url + "/List", { last_order_id: $('#last-order-id').attr('data-id') })
            .done(function (data) {
                try {
                    var result = JSON.parse(data);
                    if (result.status == 'success') {
                        toastr.options = {
                            "closeButton": true,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": true,
                            "positionClass": "toast-top-right",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "50000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        }
                        for(note in result.notifications){
                            toastr["info"](result.notifications[note], '<h5 class="text-white">NOUVELLE COMMANDE</h5>');
                        }
                        $('#last-order-id').attr('data-id', result.last_id);
                        $('#data-tbody-orders tr:first').before(result.data);
                        setTimeout(function(){
                            $('#data-tbody-orders ').find('.selected').each(function(){
                                $(this).removeClass("selected");
                            }); 
                        }, 50000);

                    }
                } catch (error) { console.log("Erreur survenue, veuillez réessayer!"); }
            }, "json");
    }, 2500);

})