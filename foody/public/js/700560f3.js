$(function () {

    $('#modal-apartment-select').on('change', function(){
        if(this.value == 'A'){
            $('#modal-apartment-extand').removeClass('d-none');
            $('#modal-apartment-extand').show();
            $('#modal-apartment-extand :input').each(function (i, el) {
                $(this).prop('required', true);
            })
        }else{
            $('#modal-apartment-extand').hide();
            $('#modal-apartment-extand :input').each(function (i, el) {
                $(this).prop('required', false);
            })
        }
    })
    $("#bd-address-modal .select2").select2({
        dropdownParent: $('#bd-address-modal .modal-content')
    });

    //======================== Save DATA from Modal =========================
    $('#addr-edit-submit').on('click', function (event) {
      event.preventDefault();
      // Form Validation
      if ($("#addr-add-form")[0].checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();

      } else {
          $.post(base_url + '/Edit', $("#addr-add-form").serialize()) // Serialization Data

          .done(function (data) {
              var result = JSON.parse(data);
              if (result.status !== undefined) {
                  $("#alert-section").html(result.info);
                  $(".modal").animate({ scrollTop: 0 }, "slow");
                  removeAlert("#auto-dismiss", 5000);
                  $("#addr-add-form").removeClass('was-validated');
              }
          });
      }
      $("#addr-add-form").addClass('was-validated');
      $('#bd-address-modal').on('hide.bs.modal', function (e) {
          location.reload();
      })
    })

    
    $("#addr-add-form").on('submit', function (event) {
        event.preventDefault();
        // Form Validation
        if ($("#addr-add-form")[0].checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();

        } else {
            $.post(base_url + '/Add', $(this).serialize()) // Serialization Data

            .done(function (data) {
                // alert(data)
                var result = JSON.parse(data);
                if (result.status !== undefined) {
                    $("#alert-section").html(result.info);
                    $(".modal").animate({ scrollTop: 0 }, "slow");
                    removeAlert("#auto-dismiss", 5000);
                    $("#addr-add-form").removeClass('was-validated');
                    $('#addr-add-form input, #addr-add-form textarea').each(function( item ){
                      $(this).val('');
                    })
                    $('#addr-add-form select').each(function( item ){
                      $(this).val('').trigger('change');
                      $(this).removeClass('is-valid is-invalid');
                    })
                }
            });
        }
        $("#addr-add-form").addClass('was-validated');
        $('#bd-address-modal').on('hide.bs.modal', function (e) {
            location.reload();
        })
    });

    //================================ remove item ==============================
    $('.btn-delete').click(function(e){
        e.preventDefault();
        var item = $(this);
        swal({
            title: "Êtes-vous sûr?",
            text: "Voulez vous supprimer cette adresse?",
            icon: "warning",
            buttons: {
              cancel: {
                text: "Annuler",
                value: null,
                visible: !0,
                className: "btn btn-default",
                closeModal: !0
              },
              confirm: {
                text: "Supprimer",
                value: !0,
                visible: !0,
                className: "btn btn-warning",
                closeModal: 0
              }
            }
          })
            .then((willDelete) => {
              if (willDelete) {
                $.post(base_url + '/Delete', { item_id: item.attr('data-id') })
                  .done(function (data) {
                      try {
                        var result = JSON.parse(data);
                        if (result.status === 'success') {
                            
                            item.parents('.col-sm-6').remove();
                          swal("Succès!", "Votre adresse a été supprimé avec succès!", {
                            icon: "success",
                          }) .then((willDelete) => {
                            if($('#row-addr').length < 2){
                                location.reload();
                            }
                          })
                          
                        } else {
                          swal("Erreur!", result.info, {
                            icon: "error",
                          });
                        }
                      } catch (e) { $.noop() }
      
                  })
                  .fail(function () {
                    swal("Erreur!", "Opération a échoué, s'il vous plaît essayez à nouveau!", {
                      icon: "error",
                    });
                  });
                }
            });
    })

    //============================= Edit item modal ================================
    $('.btn-edit').click(function(e){
        e.preventDefault();
        var item = $(this);
        item.find('i').removeClass().addClass('fas fa-circle-notch fa-spin');
        $.post(base_url + '/Fetch', { item_id: item.attr('data-id') })
            .done(function (data) {
              try {
                    var result = JSON.parse(data);
                    if (result.status === 'success') {

                        var myObject = result.data;
                        for (var propertyName in myObject) {
                          if (myObject.hasOwnProperty(propertyName) && $("[name='modal-" + propertyName + "']").length != 0) {
                            if (myObject[propertyName] != null) {
                              if(propertyName == 'building' && myObject[propertyName] == 'A' ){
                                $('#modal-apartment-extand').removeClass('d-none');
                                $('#modal-apartment-extand').show();
                                $('#modal-apartment-extand :input').each(function (i, el) {
                                    $(this).prop('required', true);
                                })
                              }
                              if(propertyName == 'city_id' || propertyName == 'building'){
                                $("[name='modal-" + propertyName + "']").val(myObject[propertyName]).trigger('change');
                              }else{
                                $("[name='modal-" + propertyName + "']").val(myObject[propertyName]);
                              }
                              
                            }
                          }
                        }
                        initMap(myObject.latitude, myObject.longtitude, 18, true);
                        $('#addr-edit-submit').removeClass('d-none');
                        $('#addr-add-submit').addClass('d-none');
                        $('[name="item_id"]').val(item.attr('data-id'));
                        $('#bd-address-modal').modal('show');
                    } else {
                        swal("Erreur!", result.info,"error");
                    }
                } catch (e) { swal("Erreur!", "Opération a échoué, s'il vous plaît essayez à nouveau!","error"); }
      
            })
            item.find('i').removeClass().addClass('mdi mdi-pencil');
    })
    $('#bd-address-modal').on('hide.bs.modal', function (e) {
      $('#addr-edit-submit').addClass('d-none');
      $('#addr-add-submit').removeClass('d-none');
      $('#addr-add-form input, #addr-add-form textarea').not('[name="modal-address_name"]').each(function( item ){
        $(this).val('');
      })
      $('[name="modal-address_name"]').val($('[name="modal-address_name"]').attr('data-value'));
      $('#addr-add-form select').each(function( item ){
        $(this).val('').trigger('change');
        $(this).removeClass('is-valid is-invalid');
      })
    })
    $('#add-address-modal').on('click', function (e) {
      initMap("35.735877","9.482569", 7);
      $('#bd-address-modal').modal('show');
    })
    
})