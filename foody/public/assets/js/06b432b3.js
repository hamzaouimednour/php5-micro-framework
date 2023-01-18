$(function () {

  var user_auth = $('table').attr('data-auth');
  $('.time').clockpicker({
    placement: 'bottom',
    align: 'left',
    autoclose: true
  });
  $("#switcher_checkbox_status").click(function () {
    if ($(this).is(':checked')) {
      $(this).val('1');
      $("#switch_checkbox_status").removeClass().addClass('label label-green').html('Utilisateur est Active &nbsp;<i class="fas fa-eye"></i>');
    } else {
      $(this).val('0');
      $("#switch_checkbox_status").removeClass().addClass('label label-danger').html('Utilisateur est Inactive <i class="fas fa-eye-slash"></i>');
    }
  });
  $("#switcher_checkbox_avaible").click(function () {
    if ($(this).is(':checked')) {
      $(this).val('1');
      $("#switcher_label_avaible").removeClass().addClass('label label-info').html('Livreur Disponible &nbsp;<i class="fas fa-hourglass-end"></i>');
    } else {
      $(this).val('0');
      $("#switcher_label_avaible").removeClass().addClass('label label-warning').html('Livreur Indisponible <i class="fas fa-hourglass"></i>');
    }
  });
  $("#switcher_checkbox_credibility").click(function () {
    if ($(this).is(':checked')) {
      $(this).val('1');
      $("#switcher_label_credibility").removeClass().addClass('label label-info').html('Client est de confiance &nbsp;<i class="fas fa-thumbs-up"></i>');
    } else {
      $(this).val('0');
      $("#switcher_label_credibility").removeClass().addClass('label label-warning').html('Client n\'est pas de confiance <i class="fas fa-thumbs-down"></i>');
    }
  });

  // ================== Change Status Eevent ======================   
  $('#data-table-default').on('click', '.btn-status', function (event) {
    event.preventDefault();
    var item_id = $(this).attr('data-id');
    var status = $(this).attr('data-status');
    var statusTxt = $('#status-' + item_id);
    if (status == 1) {
      $(this).html('&nbsp;<i class="fa fa-user"></i>&nbsp;');
      $(this).attr('data-status', '0');
      $(this).attr('title', 'Activer');
      $(this).attr('data-original-title', 'Activer');
      $(this).tooltip('show');
      statusTxt.html('<span class="label label-secondary"><i class="fas fa-times-circle"></i> Inactive</span>');
    } else {
      $(this).html('<i class="fas fa-user-alt-slash"></i>');
      $(this).attr('data-status', '1');
      $(this).attr('title', 'Inactiver');
      $(this).attr('data-original-title', 'Inactiver');
      $(this).tooltip('show');
      statusTxt.html('<span class="label label-green"><i class="fas fa-check-circle"></i> Active</span>');
    }
    $.post(base_url + '/Status', { id: item_id, status: status, user_auth: user_auth }, function (data) {
      try {
        var obj = jQuery.parseJSON(data);
        if (obj.status === 'failed') {
          swal({
            title: "Erreur!",
            text: "Operation echoué, Réessayer une autre fois!",
            icon: "error",
            closeOnEsc: false,
            closeOnClickOutside: false
          }).then(function (isConfirm) {
            if (isConfirm) {
              location.reload();
            }
          })
        }
      } catch (err) {
        // swal("Erreur!", "Operation echoué, Réessayer une autre fois!", "error");
        swal({
          title: "Erreur!",
          text: "Operation echoué, Réessayer une autre fois!",
          icon: "error",
          closeOnEsc: false,
          closeOnClickOutside: false
        }).then(function (isConfirm) {
          if (isConfirm) {
            location.reload();
          }
        })
      }

    });

  });

  // ================== Delete Item ======================   
  $('#data-table-default').on('click', '.btn-delete', function (event) {
    event.preventDefault();
    var item_id = $(this).attr('data-id');
    swal({
      title: "Êtes-vous sûr?",
      text: "Voulez vous supprimer cet élément ?",
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
          $.post(base_url + '/Delete', { id: item_id, user_auth: user_auth })
            .done(function (data) {
              try {
                var obj = jQuery.parseJSON(data);
                if (obj.status === 'success') {
                  swal("Succès!", "Votre Element Supprimé!", {
                    icon: "success",
                  });
                  $("#" + item_id).remove();
                } else {
                  swal({
                    title: "Erreur!",
                    text: obj.info,
                    icon: "error",
                    closeOnEsc: false,
                    closeOnClickOutside: false
                  }).then(function (isConfirm) {
                    if (isConfirm) {
                      location.reload();
                    }
                  })
                }
              } catch (err) {
                swal({
                  title: "Erreur!",
                  text: "Operation echoué, Réessayer une autre fois!",
                  icon: "error",
                  closeOnEsc: false,
                  closeOnClickOutside: false
                }).then(function (isConfirm) {
                  if (isConfirm) {
                    location.reload();
                  }
                })
              }

            })
            .fail(function () {
              swal("Erreur!", "Operation echoué, Réessayer une autre fois!", {
                icon: "error",
              });
            });

        }
      });
  })

  //======================== Edit Button =========================
  $('#data-table-default').on('click', '.btn-edit', function (event) {

    event.preventDefault();
    var user_id = $(this).attr('data-id');
    if ($(this).hasClass('btn-r')) {
      var baseurl = base_url.replace(
        new RegExp("(.*/)[^/]+$"),
        "$1");
      $(location).attr('href', baseurl + 'Edit/' + user_auth + '/' + encryptInt(Number($(this).attr('data-id'))));
    }
    var a = $(this).closest(".panel");
    if (!$(a).hasClass("panel-loading")) {
      var t = $(a).find(".panel-body");
      $(a).addClass("panel-loading");
      $(t).prepend('<div class="panel-loader"><span class="spinner-small"></span></div>');
    }
    $.post(base_url + '/Fetch', { user_uid: $(this).attr('data-id'), user_auth: user_auth })

      .done(function (data) {
        var myObject = jQuery.parseJSON(data);
        if (typeof (myObject.status) !== 'undefined') {

          return false;
        }

        for (var propertyName in myObject) {
          //
          if (myObject.hasOwnProperty(propertyName) && $("[name='" + propertyName + "']").length != 0) {
            if (myObject[propertyName] != null) {
              $("[name='" + propertyName + "']").val(myObject[propertyName]);
            }
          }
        }
        if (myObject.user_status == 1) {
          $("#switcher_checkbox_status").prop('checked', true);
          $("#switch_checkbox_status").removeClass().addClass('label label-green').html('Utilisateur est Active &nbsp;<i class="fas fa-eye"></i>');
        } else {
          $("#switcher_checkbox_status").prop('checked', false);
          $("#switch_checkbox_status").removeClass().addClass('label label-danger').html('Utilisateur est Inactive <i class="fas fa-eye-slash"></i>');
        }
        switch (Number(user_auth)) {
          case 1:

            break;
          case 2:

            break;
          case 3:
            if (myObject.availability == 1) {
              $("#switcher_checkbox_avaible").prop('checked', true);
              $("#switcher_label_avaible").removeClass().addClass('label label-info').html('Livreur Disponible &nbsp;<i class="fas fa-hourglass-end"></i>');
            } else {
              $("#switcher_checkbox_avaible").prop('checked', false);
              $("#switcher_label_avaible").removeClass().addClass('label label-warning').html('Livreur Indisponible <i class="fas fa-hourglass"></i>');
            }
            $('#btn-send-pwd').attr('data-id', user_id);

            break;
          case 4:
            if (myObject.credibility == 1) {
              $("#switcher_checkbox_credibility").prop('checked', true);
              $("#switcher_label_credibility").removeClass().addClass('label label-info').html('Client est de confiance &nbsp;<i class="fas fa-thumbs-up"></i>');
            } else {
              $("#switcher_checkbox_credibility").prop('checked', false);
              $("#switcher_label_credibility").removeClass().addClass('label label-warning').html('Client n\'est pas de confiance <i class="fas fa-thumbs-down"></i>');
            }
            break;
          default:
            console.log("Error occurred: User Authority");

        }
        $('input[name="user_auth"]').val(user_auth);
        // Display Modal
        setTimeout(function () {
          $(a).removeClass("panel-loading").find(".panel-loader").remove();
          $('#modal-dialog').modal('show');
        }, 500);

      });
  });
  $('#modal-dialog').on('shown.bs.modal', function () {
    $('#datepicker-birthdate-modal').datepicker({
      format: "yyyy-mm-dd",
      orientation: "bottom",
      todayHighlight: !0,
      autoclose: !0,
      endDate: '-1d'
    })
  });

  //======================== Save DATA from Modal =========================
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
          if (result.status !== undefined) {
            $("#modal-info-section").html(result.info);
            dismissAlert("#auto-dismiss", 2000);
            setTimeout(function () {
              $('#modal-dialog').modal('hide');
              location.reload();
            }, 2500);
          }
        });
    }
    $("#modal-form").addClass('was-validated');
  });
  function resetModalForm() {
    $("#modal-form").removeClass('was-validated');
    $('.needs-validation').find('input:required,textarea:required,select:required').each(function () {
      $(this).removeClass('is-valid is-invalid');
    });
    $('#modal-form').each(function () {
      this.reset();
    });
    if ($("#passwordStrengthDiv2").length != 0) {
      $("#passwordStrengthDiv2").removeClass().addClass("is0 m-t-5");
    }
    $("#switcher_checkbox_status").val('1');
    $("#switch_checkbox_status").removeClass().addClass('label label-green').html('Utilisateur est Active &nbsp;<i class="fas fa-eye"></i>');
  }
  $('#modal-dialog').on('hidden.bs.modal', function (e) {
    resetModalForm();
  })

  //=========================== Edit Item ===============================
  $(".work-time").change(function () {
    var dayId = $(this).prop('id');
    if (this.checked) {
      $('input[name="' + dayId + '-open"]').prop('disabled', false);
      $('input[name="' + dayId + '-open"]').prop('required', true);
      $('input[name="' + dayId + '-close"]').prop('disabled', false);
      $('input[name="' + dayId + '-close"]').prop('required', true);
    } else {
      $('input[name="' + dayId + '-open"]').prop('disabled', true).val('');
      $('input[name="' + dayId + '-open"]').prop('required', false);
      $('input[name="' + dayId + '-close"]').prop('disabled', true).val('');
      $('input[name="' + dayId + '-close"]').prop('required', false);
    }
  })
  
  //======================= Delivery Type =============================
  $('select[name="restaurant-delivery_type"]').change(function () {
    if ($(this).children("option:selected").val() == 1) {
      $("#delivery-options :input").each(function (i, el) {
        $(this).prop('type', 'text');
        $(this).prop('required', true);
      })
      $('#delivery-options').removeClass().show();
    } else {
      $("#delivery-options :input").each(function (i, el) {
        $(this).prop('type', 'hidden');
        $(this).prop('required', false);
      })
      $('#delivery-options').hide();

    }
  })

  //=================== Init Dropzone ====================
  Dropzone.autoDiscover = false;
  try {
    var myDropzone = new Dropzone("#dropzone", {
      url: base_url + '/Save',
      autoProcessQueue: false,
      paramName: "Logo",
      maxFilesize: 5, // MB
      uploadMultiple: false,
      maxFiles: 1,
      parallelUploads: 1,
      acceptedFiles: ".jpeg,.jpg,.png,.gif",
      maxfilesexceeded: function (file) {
        this.removeAllFiles();
        this.addFile(file);
      },
      init: function () {
        this.on('error', function (file, errorMessage) {
          if (errorMessage.indexOf('files of this type') !== -1) {
            this.removeFile(file);
          }
        });
        this.on("complete", function (file) {
          this.removeFile(file);
        });
        this.on('sending', function (file, xhr, formData) {
          // Append all form inputs to the formData Dropzone will POST
          data = $('#user-form').find(":input:not(:hidden)").serializeArray();
          $.each(data, function (key, el) {
            formData.append(el.name, el.value);
          });
        });
        this.on("success", function (file, response) {
          // console.log(response);
          var result = jQuery.parseJSON(response);
          if (result.status !== undefined) {
            $("#note-msg").html(result.info);
            dismissAlert("#auto-dismiss", 10000);
          }
        })
      }
    })
  } catch (e) { console.log('Init Dropzone failed') }
  //=================== Submit form ====================
  $("#user-form").submit(function (event) {
    event.preventDefault();

    // var noWorkTimes = 0;
    // $("input[name^='day-']").each(function (i, el) {
    //   if (this.disabled) {
    //     noWorkTimes++;
    //   }
    // });
    // if (noWorkTimes == 14) {
    //   swal("Attention!", "Au moins travailler un jour, sélectionnez svp un jour!", "warning");
    //   return false;
    // }

    if ($('#user-form')[0].checkValidity() === false) {
      event.preventDefault();
      event.stopPropagation();
    } else {
      if (myDropzone.files.length) {
        myDropzone.processQueue(); // launch sending
      } else {
        $.post(base_url + '/Save', $(this).serialize()) // Serialization Data

          .done(function (data) {
            // console.log(data)
            var result = jQuery.parseJSON(data);
            if (result.status !== undefined) {
              $("#note-msg").html(result.info);
              $('.btn-scroll-to-top').trigger('click');
              dismissAlert("#auto-dismiss", 10000);
            }
          });
      }
    }
    $("#user-form").addClass('was-validated');
  })


  //=================================================================================
  $('#partner-accept').click(function(event){
    event.preventDefault();
    swal({
      title: "Êtes-vous sûr?",
      text: "Voulez vous accepter la demande de partenariat ?",
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
          text: "Accepter",
          value: !0,
          visible: !0,
          className: "btn btn-success",
          closeModal: 0
        }
      }
    })
      .then((willDelete) => {
        if (willDelete) {
          $.post(base_url + '/PartnerRequestAccept', {request: true})
            .done(function (data) {
              try {
                var obj = jQuery.parseJSON(data);
                if (obj.status === 'success') {
                  swal("Succès!", "La demande de partenariat accepté avec succès!", {
                    icon: "success",
                  }).then(function (isConfirm) {
                    if (isConfirm) {
                      location.reload();
                    }
                  })
                } else {
                  swal({
                    title: "Erreur!",
                    text: obj.info,
                    icon: "error",
                    closeOnEsc: false,
                    closeOnClickOutside: false
                  }).then(function (isConfirm) {
                    if (isConfirm) {
                      location.reload();
                    }
                  })
                }
              } catch (err) {
                swal({
                  title: "Erreur!",
                  text: "Operation echoué, réessayer une autre fois!",
                  icon: "error",
                  closeOnEsc: false,
                  closeOnClickOutside: false
                }).then(function (isConfirm) {
                  if (isConfirm) {
                    location.reload();
                  }
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
  $('#partner-refuse').click(function(event){
    event.preventDefault();
    swal({
      title: "Êtes-vous sûr?",
      text: "Voulez vous refuser la demande de partenariat ?",
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
          text: "Refuser",
          value: !0,
          visible: !0,
          className: "btn btn-danger",
          closeModal: 0
        }
      }
    })
      .then((willDelete) => {
        if (willDelete) {
          $.post(base_url + '/PartnerRequestRefuse', {request: false})
            .done(function (data) {
              
              try {
                var obj = jQuery.parseJSON(data);
                if (obj.status === 'success') {
                  swal("Succès!", "La demande de partenariat refusé avec succès!", {
                    icon: "success",
                  }).then(function (isConfirm) {
                    if (isConfirm) {
                      location.reload();
                    }
                  })
                } else {
                  swal({
                    title: "Erreur!",
                    text: obj.info,
                    icon: "error",
                    closeOnEsc: false,
                    closeOnClickOutside: false
                  }).then(function (isConfirm) {
                    if (isConfirm) {
                      location.reload();
                    }
                  })
                }
              } catch (err) {
                swal({
                  title: "Erreur!",
                  text: "Operation echoué, réessayer une autre fois!",
                  icon: "error",
                  closeOnEsc: false,
                  closeOnClickOutside: false
                }).then(function (isConfirm) {
                  if (isConfirm) {
                    location.reload();
                  }
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


    // Sending ...
    $('body').on('click', '#send-pwd', function(event){
      event.preventDefault();
      if($('#user-pwd').val() == ''){
        return false;
      }
      swal({
        title: "Êtes-vous sûr?",
        text: "Voulez vous envoyer cette mot de passe au partenaire ?",
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
            text: "Envoyer",
            value: !0,
            visible: !0,
            className: "btn btn-green",
            closeModal: 0
          }
        }
      })
        .then((willDelete) => {
          if (willDelete) {
            $.post(base_url + '/SendPwd', {'user-auth': $('#user-pwd').attr('data-auth'), 'user-id': $('#btn-send-pwd').attr('data-id'), 'user-pwd': $('#user-pwd').val() })
              .done(function (data) {
                try {
                  var obj = jQuery.parseJSON(data);
                  if (obj.status === 'success') {
                    swal("Succès!", "Email envoyé avec succès!", {
                      icon: "success",
                    })
                  } else {
                    swal("Echec!", "Echec d'envoyé l'email !", {
                      icon: "error",
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
    })
})