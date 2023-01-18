$(function () {

    //========================== Time Clock Init  ====================================
    $('.time').clockpicker({
        placement: 'bottom',
        align: 'left',
        autoclose: true
    });
    //================================ Show pwd icon ===================================
    $('.pwd-show').click(function () {
        if ($(this).find('i').hasClass('fa-eye')) {
            $(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
            $(this).prev().prop('type', 'password');
        } else {
            $(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
            $(this).prev().prop('type', 'text');
        }
    })
    $("#switcher_checkbox_avaible").click(function () {
        if (this.checked) {
          $(this).val('1');
          $("#switcher_label_avaible").removeClass().addClass('label label-info').html('Livreur Disponible &nbsp;<i class="fas fa-hourglass-end fa-pulse"></i>');
        } else {
          $(this).val('0');
          $("#switcher_label_avaible").removeClass().addClass('label label-warning').html('Livreur Indisponible <i class="fas fa-hourglass"></i>');
        }
    });
    $('input[name="old-pwd"]').keyup(function () {
        if (this.value == '') {
            $(this).removeClass('is-invalid is-valid');
            $(this).parent().find('.valid-tooltip').hide();
            $('input[name="new-pwd"]').prop('required', false);
            $('input[name="new-pwd"]').removeClass('is-invalid is-valid');
            $('#s-account').prop('disabled', false);
        }else{
            $.post(base_url + '/Search', { 'old-pwd': $('input[name="old-pwd"]').val() }) // Serialization Data

                .done(function (data) {
                    var result = jQuery.parseJSON(data);
                    if (result.status === 'failed') {
                        $('input[name="old-pwd"]').removeClass('is-invalid is-valid').addClass('is-invalid');
                        $('#s-account').prop('disabled', true);
                    } else {
                        $('input[name="old-pwd"]').parent().find('.valid-tooltip').text('Mot de passe correct');
                        $('input[name="old-pwd"]').parent().find('.valid-tooltip').show();
                        $('input[name="old-pwd"]').removeClass('is-invalid is-valid').addClass('is-valid');
                        $('#s-account').prop('disabled', false);
                    }
                });
        }
    })

    //================================ Submit Edit Form ===================================
    $('#account-form').submit(function (e) {
        e.preventDefault();
        $('#auto-dismiss').remove();
        /**
         * Password Validation Section
         */
        var pwdTest = true;
        $('input[name="old-pwd"]').parent().find('.valid-tooltip').hide();
        if ($('input[name="old-pwd"]').val() != '') {
            $('input[name="new-pwd"]').prop('required', true);
            if ($('input[name="new-pwd"]').val() == $('input[name="old-pwd"]').val()) {
                var pwdTest = false;
                $('input[name="new-pwd"]').parent().find('.invalid-tooltip').text('Choisissez Mot de passe différent de la dernier');
                $('input[name="new-pwd"]').removeClass('is-invalid is-valid').addClass('is-invalid');
                
            } else {
                $('input[name="new-pwd"]').parent().find('.invalid-tooltip').text('Ce champ devient obligatoire');
                $('input[name="new-pwd"]').removeClass('is-invalid is-valid').addClass('is-valid');
            }
        } else {
            $('input[name="new-pwd"]').prop('required', false);
        }

        /**
         * Form Validation Section
         */
        if ($('#account-form')[0].checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            if (pwdTest) {
                $('#s-account').find('i').removeClass().addClass('fas fa-circle-notch fa-spin');
                $.post(base_url + '/Edit', $(this).serialize()) // Serialization Data

                    .done(function (data) {
                        try {
                            var result = jQuery.parseJSON(data);
                            if (result.status !== undefined) {
                                $("#note-msg").html(result.info);
                                $('#s-account').find('i').removeClass().addClass('fas fa-check-circle');
                                setTimeout(() => {
                                    $('#s-account').find('i').removeClass().addClass('fas fa-save');
                                }, 3000);
                                $("#account-form").removeClass('was-validated');
                                $('input[name="old-pwd"]').val('').removeClass('is-invalid is-valid');
                                $('input[name="new-pwd"]').val('').removeClass('is-invalid is-valid');
                                removeAlert("#auto-dismiss", 5000);
                            }
                        } catch (error) {
                            swal("Erreur!", "Opération échouée, Réssayer une autre fois!", "error");
                        }
                    });
            }
        }
        $("#account-form").addClass('was-validated');


    })
    $('#request-form').submit(function (e) {
        e.preventDefault();
        if ($('#request-form')[0].checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            $.post(base_url + '/Request', $(this).serialize()) // Serialization Data

                .done(function (data) {
                    $("#request-form").removeClass('was-validated');
                    $('#request-form').each(function () {
                        this.reset();
                        $(this).val('')
                    });
                    try {
                        var result = jQuery.parseJSON(data);
                        if (result.status !== undefined) {
                            $("#note-msg-2").html(result.info);
                            dismissAlert("#auto-dismiss", 4000);
                        }
                    } catch (error) {
                        swal("Erreur!", "Opération échouée, Réssayer une autre fois!", "error");
                    }

                });
        }
        $("#request-form").addClass('was-validated');

    })

    /**
     * 
     * Restaurant Submit Form & Controls
     * 
     */

    //======================= Work Time =============================
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
        $('.needs-validation').find('input:required,textarea:required,select:required').bind('focusout keyup change', function () {
            // check element validity and change class
            $(this).removeClass('is-valid is-invalid')
              .addClass(this.checkValidity() ? 'is-valid' : 'is-invalid');
        });
    })

    //=================== Init Dropzone ====================
    Dropzone.autoDiscover = false;
    var myDropzone = new Dropzone("#dropzone", {
        url: base_url + '/Edit',
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
            data = $('#resto-account-form').find(":input:not(:hidden)").serializeArray();
            $.each(data, function (key, el) {
            formData.append(el.name, el.value);
            });
        });
        this.on("success", function (file, response) {
            // console.log(response);
            var result = jQuery.parseJSON(response);
            if (result.status !== undefined) {
            $("#note-msg").html(result.info);
            $('.btn-scroll-to-top').trigger('click');
            dismissAlert("#auto-dismiss", 5000);
            }
        })
        }
    })
    //=================== Init Dropzone ====================
    Dropzone.autoDiscover = false;
    var myCoverDropzone = new Dropzone("#cover_dropzone", {
        url: base_url + '/CoverPhoto',
        autoProcessQueue: false,
        paramName: "Cover",
        maxFilesize: 8, // MB
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
            formData.append('update', 'true');
        });
        this.on("success", function (file, response) {
            var result = jQuery.parseJSON(response);
            if (result.status !== undefined) {
                $("#cover-msg").html(result.info);
                $('.btn-scroll-to-top').trigger('click');
                removeAlert("#auto-dismiss", 5000);
            }
        })
        }
    })

    //=================== Submit form ====================
    $("#resto-account-form").submit(function (event) {
        event.preventDefault();
        var errorWorkTimes = 0 ;
        for (var i = 1; i < 8; i++) {
            if ($("input[name='day-" + i + "-open']").prop("disabled") == false) {

                var start_val = $("input[name='day-" + i + "-open']").val();
                var end_val = $("input[name='day-" + i + "-close']").val();
                
                var end_time = new Date("01/01/2020 " + end_val);
                var start_time = new Date("01/01/2020 " + $("input[name='day-" + i + "-open']").val());
                
                if(start_time == end_time){
                    $("input[name='day-" + i + "-open']").removeClass('is-valid is-invalid').addClass('is-invalid');
                    $("input[name='day-" + i + "-close']").removeClass('is-valid is-invalid').addClass('is-invalid');
                    errorWorkTimes ++;
                }else{
                    $("input[name='day-" + i + "-open']").removeClass('is-valid is-invalid').addClass('is-valid');
                    $("input[name='day-" + i + "-close']").removeClass('is-valid is-invalid').addClass('is-valid');
                }
            }
        }
        var noWorkTimes = 0;
        $("input[name^='day-']").each(function (i, el) {
        if (this.disabled) {
            noWorkTimes++;
        }
        });
        if (noWorkTimes == 14) {
        swal("Attention!", "Au moins travailler un jour, sélectionnez svp le(s) jour(s)!", "warning");
        return false;
        }
        if (errorWorkTimes > 0) {
        swal('Attention!', 'Les heures des jours ne sont pas valide.', 'warning');
        return false;
        }
        if ($('#resto-account-form')[0].checkValidity() === false) {
            swal('Attention!', 'Svp remplir les champs obligatoires.','warning');
            event.preventDefault();
            event.stopPropagation();
        } else {
            $('#s-account').find('i').removeClass().addClass('fas fa-circle-notch fa-spin');
            if (myDropzone.files.length) {
                myDropzone.processQueue(); // launch sending
            } else {
                $.post(base_url + '/Edit', $(this).serialize()) // Serialization Data

                .done(function (data) {
                    var result = jQuery.parseJSON(data);
                    if (result.status !== undefined) {
                        $("#note-msg").html(result.info);
                        $('#s-account').find('i').removeClass().addClass('fas fa-check-circle');
                        setTimeout(() => {
                            $('#s-account').find('i').removeClass().addClass('fas fa-save');
                        }, 3000);
                        $('.btn-scroll-to-top').trigger('click');
                        removeAlert("#auto-dismiss", 6000);
                    }
                });
            }
        }
        $("#resto-account-form").addClass('was-validated');
    })
    $("#s-cover").click(function (event) {
        event.preventDefault();
        if (myCoverDropzone.files.length) {
            myCoverDropzone.processQueue(); // launch sending
        } else {
            swal('Attention!', 'La photo de couverture est obligatoire.', 'warning');
        }
    })
})