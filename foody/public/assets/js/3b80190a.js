$(function () {
    $("#select2insidemodal").select2({
        dropdownParent: $("#modal-dialog")
    });

    $("#switcher_checkbox_status").click(function () {
        if ($(this).is(':checked')) {
            $(this).val('1');
            $("#switch_checkbox_status").removeClass().addClass('label label-green').html('Promotion Active &nbsp;<i class="fas fa-eye"></i>');
        } else {
            $(this).val('0');
            $("#switch_checkbox_status").removeClass().addClass('label label-danger').html('Promotion Inactive <i class="fas fa-eye-slash"></i>');
        }
    });
    $("#modal-switcher_checkbox_status").click(function () {
        if ($(this).is(':checked')) {
            $(this).val('1');
            $("#modal-switch_checkbox_status").removeClass().addClass('label label-green').html('Promotion Active &nbsp;<i class="fas fa-eye"></i>');
        } else {
            $(this).val('0');
            $("#modal-switch_checkbox_status").removeClass().addClass('label label-danger').html('Promotion Inactive <i class="fas fa-eye-slash"></i>');
        }
    });
    $("input[type=radio][name='voucher_type']").on('change',function () {
        $('.addon-voucher-type').text(this.value);
    });
    $('input[name="modal_voucher_type"]').on('change',function () {
        $('#addon-voucher-type').text(this.value);
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
            text: "Voulez vous supprimer les éléments sélectionnés ?",
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
                    $.post(base_url + '/Multi-delete', { items: mapItems.toString()})
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

    // ================== Delete Eevent ======================   
    $(".btn-delete").click(function (event) {
        event.preventDefault();
        var item_id = $(this).parents('tr').attr('id');
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
    //==========================================================================
    $('#add-discount').submit(function (event) {
        event.preventDefault();
        // Form Validation

        if ($("#add-discount")[0].checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();

        } else {
            if ($('#datetimepicker3').val() >= $('#datetimepicker4').val()) {
                swal("Erreur Validité du Promo!", "la date du fin doit étre supérieur du date de début", "error").then(function () {
                    $('#datetimepicker4').focus()
                });
                $('#datetimepicker4').removeClass('is-valid').addClass('is-invalid')
            } else {
                $.post(base_url + '/Add', $(this).serialize() ) // Serialization Data

                    .done(function (data) {

                        var result = jQuery.parseJSON(data);
                        if (result.status != undefined) {
                            $("#info-section").html(result.info);
                            $('#reset-discount-form').trigger('click')
                            removeAlert("#close-note", 5000);

                        }
                    });
            }
        }

        $("#add-discount").addClass('was-validated');

    })
    //==========================================================================
    $('#reset-discount-form').click(function (event) {
        event.preventDefault();
        $("#add-discount").removeClass('was-validated');
        $('.needs-validation').find('input:required,textarea:required,select:required').each(function () {
          $(this).removeClass('is-valid is-invalid');
        });
        $('#add-discount').each(function () {
          this.reset();
        });
        $("#switch_checkbox_status").removeClass().addClass('label label-green').html('Promotion Active <i class="fas fa-eye"></i>');
    })

    $('.btn-edit').click(function () {
        
        $(".select2-modal").select2({
            dropdownParent: $("#modal-dialog")
        });
    
    //========================= GET promo DATA ================================
    $.post(base_url + '/Fetch', { promo_id: $(this).parents('tr').prop('id') })

        .done(function (data) {

            var myObject = jQuery.parseJSON(data);

            for (var propertyName in myObject) {
            //
                if (myObject.hasOwnProperty(propertyName) && $("[name='modal-" + propertyName + "']").length != 0) {
                    if (myObject[propertyName] != null) {
                        if(propertyName == "discount_item_id"){
                            $("[name='modal-" + propertyName + "']").val(myObject[propertyName]).trigger('change');
                        }else{
                            $("[name='modal-" + propertyName + "']").val(myObject[propertyName]);
                        }
                    }
                }
            }
            if (myObject.status == 1) {
                $("#modal-switcher_checkbox_status").prop('checked', true);
                $("#modal-switch_checkbox_status").removeClass().addClass('label label-green').html('Promotion Active &nbsp;<i class="fas fa-eye"></i>');
              } else {
                $("#modal-switcher_checkbox_status").prop('checked', false);
                $("#modal-switch_checkbox_status").removeClass().addClass('label label-danger').html('Promotion Inactive <i class="fas fa-eye-slash"></i>');
              }
        })

        //============================ Launch Modal ========================================
        $(this).html('<div class="spinner-border spinner-border-sm text-light" role="status"></div>');
        setTimeout(function () {
            $('#modal-dialog').modal('show');
            $('.btn-edit').html('Editer');
        }, 500)
    })
    $('#modal-dialog').on('hide.bs.modal', function (e) {
        $("#modal-form").removeClass('was-validated');
        $('.needs-validation').find('input:required,textarea:required,select:required').each(function () {
          $(this).removeClass('is-valid is-invalid');
        });
        $('#modal-form').each(function () {
          this.reset();
        });
    })
        //==========================================================================
        $('#modal-form').submit(function (event) {
            event.preventDefault();
            // Form Validation
    
            if ($("#modal-form")[0].checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
    
            } else {
                if ($('#datetimepicker-modal-1').val() >= $('#datetimepicker-modal-2').val()) {
                    swal("Erreur Validité du Promo!", "la date du fin doit étre supérieur du date de début", "error").then(function () {
                        $('#datetimepicker-modal-2').focus()
                    });
                    $('#datetimepicker-modal-2').removeClass('is-valid').addClass('is-invalid')
                } else {
                    $.post(base_url + '/Edit', $(this).serialize() ) // Serialization Data
    
                        .done(function (data) {

                            var result = jQuery.parseJSON(data);
                            if (result.status != undefined) {
                                $("#modal-info-section").html(result.info);
                                removeAlert("#close-note", 5000);
    
                            }
                        });
                }
            }
    
            $("#modal-form").addClass('was-validated');
            $('#modal-dialog').on('hide.bs.modal', function (e) {
                location.reload();
            })
    
        })
})