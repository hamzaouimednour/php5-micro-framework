$(function () {

  // ================== Switcher Status ==================
  $("#switcher_checkbox").click(function () {
    if ($(this).is(':checked')) {
      $("#switcher_checkbox_label").html('<span class="badge badge-green">Active</span>');
    } else {
      $("#switcher_checkbox_label").html('<span class="badge badge-danger">Inactive</span>');
    }
  });

  // ================== Submit Form ======================

  $('#reset-add-categorie').click(function (event) {
    event.preventDefault();
    $("#add-categorie").removeClass('was-validated');
    $('.needs-validation').find('input:required,textarea:required,select:required').each(function () {
      $(this).removeClass('is-valid is-invalid');
    });
    $('#add-categorie').each(function () {
      this.reset();
    });
    $("#switcher_checkbox_label").html('<span class="badge badge-green">Active</span>');
  })
  $("#add-categorie").submit(function (event) {
    event.preventDefault();

    // Form Validation

    if ($("#add-categorie")[0].checkValidity() === false) {
      event.preventDefault();
      event.stopPropagation();

    } else {
      // alert($(this).serialize());
      $.post(base_url + '/Add', $(this).serialize()) // Serialization Data

        .done(function (data) {
          $("#add-categorie").removeClass('was-validated');
          $('#add-categorie').each(function () {
            this.reset();
          });
          var result = jQuery.parseJSON(data);
          if (result.status !== undefined) {
            
            $("#info-section").html(result.info);
            if (result.status === 'success') {
              $('#reset-add-categorie').trigger('click');
            }
            // $("#add-supplement").removeClass('was-validated');
            dismissAlert("#auto-dismiss", 4000);
          }

        });
    }
    $("#add-categorie").addClass('was-validated');
  });

  // ================== Change Status Eevent ======================   
  $(".btn-status").click(function (event) {
    event.preventDefault();
    var item_id = $(this).parents('tr').attr('id');
    var status = $(this).attr('data-status');
    var statusTxt = $('#status-' + item_id);
    if (status == 1) {
      $(this).find('i').removeClass().addClass('fas fa-eye');
      $(this).attr('data-status', '0');
      $(this).attr('title', 'Activer');
      $(this).attr('data-original-title', 'Activer');
      $(this).tooltip('show');
      statusTxt.html('<h5><span class="label label-secondary"><i class="fas fa-times-circle"></i> Inactive</span></h5>');
    } else {
      $(this).find('i').removeClass().addClass('fas fa-eye-slash');
      $(this).attr('data-status', '1');
      $(this).attr('title', 'Désactiver');
      $(this).attr('data-original-title', 'Désactiver');
      $(this).tooltip('show');
      statusTxt.html('<h5><span class="label label-green"><i class="fas fa-check-circle"></i> Active</span></h5>');
    }
    $.post(base_url + '/Status', { id: item_id, status: status }, function (data) {
      var obj = jQuery.parseJSON(data);
      if (obj.status === 'failed') {
        alert('error');
      }

    });

  });

  // ================== Delete Eevent ======================   
  $(".btn-delete").click(function (event) {
    event.preventDefault();
    var item_id = $(this).parents('tr').attr('id');
    swal({
      title: "Êtes-vous sûr?",
      text: "Tous les repas dans cette catégorie sera supprimé!\nVoulez vous supprimer cet élément ?",
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
          $.post(base_url + '/Delete', { id: item_id })
            .done(function (data) {
              var obj = jQuery.parseJSON(data);
              if (obj.status === 'success') {
                swal("Succès!", "Votre Element Supprimé!", {
                  icon: "success",
                });
                $("#" + item_id).remove();
              } else {
                swal("Erreur!", obj.info, {
                  icon: "error",
                });
              }

            })
            .fail(function () {
              swal("Erreur!", "Réessayer une autre fois!", {
                icon: "error",
              });
            });

        }
      });

  });


  // ================== Edit Button ======================   
  $('.btn-edit').click(function () {
    var item_id = $(this).parents('tr').attr('id');
    $('input[name="item_name"]').val($('#cat-name-' + item_id).text());
    $('input[name="item_id"]').val(item_id);
    $('#modal-dialog').modal('show').on('shown.bs.modal', function () {
      $('[name="item_name"]').trigger('focus');
    });
  });

  $("#modal-form").submit(function (event) {
    event.preventDefault();
    // Form Validation

    if ($("#modal-form")[0].checkValidity() === false) {
      event.preventDefault();
      event.stopPropagation();

    } else {
      $.post(base_url + '/Edit', $(this).serialize()) // Serialization Data

        .done(function (data) {

          var result = jQuery.parseJSON(data);
          if (result.status === 'success') {
            $('#cat-name-' + $('input[name="item_id"]').val()).text($('input[name="item_name"]').val());
            $("#modal-info-section").html(result.info);
            dismissAlert("#auto-dismiss", 2000);
            setTimeout(function () {
              $('#modal-dialog').modal('hide');
            }, 2500);

          }
        });
    }

    $("#modal-form").addClass('was-validated');

  });


  // ================== Multi Delete Button ======================  

  // Listen for click on toggle checkbox
  $('#cssCheckboxAll').click(function (event) {
    if (this.checked) {
      // Iterate each checkbox
      $("input:checkbox[name='multi_items[]']").each(function () {
        this.checked = true;
        $('#multi-delete').prop("disabled", false);
      });
    } else {
      $("input:checkbox[name='multi_items[]']").each(function () {
        this.checked = false;
        $('#multi-delete').prop("disabled", true);
      });
    }
  });

  $("input:checkbox[name='multi_items[]']").change(function () {
    $('#multi-delete').prop('disabled', !$("input:checkbox[name='multi_items[]']:checked").length);
  });

  $('#multi-delete').click(function (event) {
    event.preventDefault();
    var mapItems = $("input:checkbox[name='multi_items[]']:checked").map(function () {
      return this.value;
    }).get();
    swal({
      title: "Êtes-vous sûr?",
      text: "Tous les repas dans les catégories sélectionnés sera supprimés aussi!\nVoulez vous supprimer les éléments sélectionnés ?",
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
          $.post(base_url + '/Multi-delete', { items: mapItems.toString() })
            .done(function (data) {
              var result = jQuery.parseJSON(data);
              if (result.status === 'success') {
                $.each(mapItems, function (index, item_id) {
                  $("#" + item_id).remove();
                });
                swal("Succès!", "Les Éléments sélectionnés ont été supprimés!", {
                  icon: "success",
                });
                $('#cssCheckboxAll').prop('checked', false);
                $('#multi-delete').prop("disabled", true);
              } else {
                swal("Erreur!", result.info, {
                  icon: "error",
                });
              }

            })
            .fail(function () {
              swal("Erreur!", "Réessayer une autre fois!", {
                icon: "error",
              });
            });

        }
      });
  });

});