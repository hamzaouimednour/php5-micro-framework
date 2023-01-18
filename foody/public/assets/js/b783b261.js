$(function () {
    var urLocation = (window.location).toString().toLowerCase();
    // $('.default-select2').select2();
    
    if(urLocation.search('add') != -1 || urLocation.search('edit') != -1){
        if(urLocation.search('add') != -1){
            $('#price-size').hide();
        }else{
            if($('[name="dish_price"]').val() == '' || $('[name="dish_price"]').val() == null){
                $( "#switcher_checkbox" ).prop('checked', true);
                $( "#switcher_checkbox" ).val('1');
                $("#switch_status").removeClass().addClass('label label-green').html('prix est en fonction de la taille <i class="fas fa-check-circle"></i>');
                $('#price-default').hide();
                $('#price-size').show();
            }else{
                $( "#switcher_checkbox" ).val('-1');
                $('#price-size').hide();
                $('#price-default').show();
                $("#switch_status").removeClass().addClass('label label-purple').html('prix en fonction de la taille &nbsp;<i class="fas fa-question-circle"></i>');
            
            }
        }
        
        $(document).on('focus', '[name="dish-size[]"], [name="dish-price[]"]', function(){
            $(this).css('border', '');
        });
        $(document).on('focusout', '[name="dish-size[]"], [name="dish-price[]"]', function(){
            str = this.value.replace(/ +(?= )/g,'');
                if(str === '' || str === ' '){
                    $(this).css('border', '1px solid #ff5b57' );
                    error = true;
                }
        });
     
        // dismissAlert('.note', 4000);
        $("#switcher_checkbox").click(function () {
            if ($(this).is(':checked')) {
                $(this).val('1');
                $("#switch_status").removeClass().addClass('label label-green').html('prix est en fonction de la taille <i class="fas fa-check-circle"></i>');
                $('#price-default').hide();
                $('#price-size').show();
            } else {
                $(this).val('-1');
                $('#price-size').hide();
                $('#price-default').show();
                $("#switch_status").removeClass().addClass('label label-purple').html('prix en fonction de la taille &nbsp;<i class="fas fa-question-circle"></i>');
            }
        });
        $("#switcher_checkbox_status").click(function () {
            if ($(this).is(':checked')) {
                $(this).val('1');
                $("#switch_checkbox_status").removeClass().addClass('label label-green').html('Plat est Visible &nbsp;<i class="fas fa-eye"></i>');
            } else {
                $(this).val('0');
                $("#switch_checkbox_status").removeClass().addClass('label label-danger').html('Plat est Invisible <i class="fas fa-eye-slash"></i>');
            }
        });
        $('#size-row').click(function () {
            var dataSize = parseInt($(this).parent().parent().attr('data-size'));
            if(dataSize < 7){
                $('#price-size').append('<div class="form-inline m-b-10"><div class="form-group m-r-10"> <input type="text" class="form-control input-sm" name="dish-size[]" placeholder="Entrer taille"> </div> <div class="form-group m-r-10"> <input type="text" class="form-control input-sm" name="dish-price[]" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" placeholder="Prix"> </div><button type="button" class="btn btn-default btn-sm" id="remove-size-row" data-toggle="tooltip" data-placement="right" title="" data-original-title="Supprimer ce taille"><i class="fas fa-times-circle"></i></button></div>');
                $(this).parent().parent().attr('data-size', dataSize+1);
                $("[data-toggle='tooltip']").tooltip();
                $('[data-toggle="popover"]').popover()
            }
        });
        $(document).on('click', '#remove-size-row', function(){
            var dataSize = parseInt($(this).parent().parent().attr('data-size'));
            $('[data-toggle="tooltip"]').tooltip("hide");
            $(this).parent().parent().attr('data-size', dataSize-1);
            $(this).parent().remove();
        });
    
        //=================== Init Dropzone ====================
        if($('#dropzoneFrom').length){
            Dropzone.autoDiscover = false;
            var myDropzone = new Dropzone("#dropzoneFrom", {
                url: base_url + '/Save',
                autoProcessQueue: false,
                paramName: "Image",
                maxFilesize: 5, // MB
                uploadMultiple: false,
                maxFiles: 1,
                parallelUploads: 1,
                acceptedFiles: ".jpeg,.jpg,.png,.gif",
                maxfilesexceeded: function(file) {
                    this.removeAllFiles();
                    this.addFile(file);
                },
                init: function() {
                    this.on('error', function(file, errorMessage) {
                        if (errorMessage.indexOf('files of this type') !== -1) {
                            this.removeFile(file);
                        }
                    });
                    this.on("complete", function(file) {
                        this.removeFile(file);
                    });
                    this.on('sending', function(file, xhr, formData) {
                        // Append all form inputs to the formData Dropzone will POST
                        if($('#switcher_checkbox').val() == '1'){
                            data = $($('#dish-add-form')[0].elements).not('[name="dish_price"]').serializeArray();
                        }else{
                            data = $($('#dish-add-form')[0].elements).not('[name="dish-size[]"], [name="dish-price[]"]').serializeArray();
                        }
                        $.each(data, function(key, el) {
                            formData.append(el.name, el.value);
                        });
                    });
                    this.on("success", function(file, response) {
                        // console.log(response);
                        var result = jQuery.parseJSON(response);
                        if (result.status !== undefined) {
                            $("#note-msg").html(result.info);
                            dismissAlert("#auto-dismiss", 8000);
                        }
                    });
                }
            });
        }
        //=================== Add Item ====================
        
        $('#add-dish').click(function(e){
            // alert($('#dish-add-form').serialize());
            e.preventDefault();
            var uploadStatus = true;
            var validationError = false;
            var priceSwitcher = $('#switcher_checkbox').val();
            $('.needs-validation').find('input:required,textarea:required,select:required').each(function () {
                // check element validity and change class
                
                if(priceSwitcher == '1' && this.name == 'dish_price'){
                    $(this).removeClass('is-valid is-invalid');
                    // $.noop();
                }else{
                    $(this).removeClass('is-valid is-invalid')
                        .addClass(this.checkValidity() ? 'is-valid' : 'is-invalid');
                    if(!this.checkValidity()){ validationError = true; }
                }
            });
            $('[name="dish-size[]"], [name="dish-price[]"]').css('border','');
            if(priceSwitcher == '1'){
                $("input[name='dish-size[]'], input[name='dish-price[]'").each(function() {
                    str = this.value.replace(/ +(?= )/g,'');
                    if(str === '' || str === ' '){
                        $(this).css('border', '1px solid #ff5b57' );
                        validationError = true;
                    }
                });
            }
            
            if (validationError) {
                e.preventDefault();
                e.stopPropagation();
                
            } else {
                if(urLocation.search('add') != -1){
                    $(this).html('<span class="spinner-border spinner-border-sm"></span> &nbsp; Ajouter');
                }else{
                    $(this).html('<span class="spinner-border spinner-border-sm"></span> &nbsp; Modifier');
                }
                    if (myDropzone.files.length) {
                    myDropzone.processQueue(); // upload files and submit the form
                    $('#add-dish').html('<i class="fas fa-check-circle"></i> &nbsp;Ajouter');
                    if(urLocation.search('add') != -1){
                        $('#reset-dish-form').trigger('click');
                    }
                    
                }else{
                    if($('#switcher_checkbox').val() == '1'){
                        data = $($('#dish-add-form')[0].elements).not('[name="dish_price"]').serialize();
                    }else{
                        data = $($('#dish-add-form')[0].elements).not('[name="dish-size[]"], [name="dish-price[]"]').serialize();
                    }
                    $.post(base_url + '/Save', data)
                        .done(function (data) {
                            if(urLocation.search('add') != -1){
                                $('#add-dish').html('<i class="fas fa-check-circle"></i> &nbsp;Ajouter');
                            }else{
                                $('#add-dish').html('<i class="fas fa-check-circle"></i> &nbsp;Modifier');
                            }
                            var result = jQuery.parseJSON(data);
                            if (result.status !== undefined) {
                                $("#note-msg").html(result.info);
                                if(urLocation.search('add') != -1){
                                    $('#reset-dish-form').trigger('click');
                                }
                                dismissAlert("#auto-dismiss", 8000);
                            }
                        });
                }
            }
        })
        $('#reset-dish-form').click(function(e){
            e.preventDefault();
            if(urLocation.search('edit') != -1){
                history.back(1);
            }else{
                $('#add-dish').html('Ajouter');
                $('#price-size').hide();
                $('#price-default').show();
                $('#selectInput1').val('').trigger("change");
                $('#selectInput2').val('').trigger("change");
                $('#switcher_checkbox').val('-1');
                $('.needs-validation').find('input:required,textarea:required,select:required').each(function () {
                    // check element validity and change class
                    $(this).removeClass('is-valid is-invalid');
                });
                $("#switch_status").removeClass().addClass('label label-purple').html('prix en fonction de la taille &nbsp;<i class="fas fa-question-circle"></i>');
                myDropzone.removeAllFiles();
                $("#dish-add-form").removeClass('was-validated');
                $("#dish-add-form").trigger('reset');
                $("#switcher_checkbox_status").val('1');
                $("#switch_checkbox_status").removeClass().addClass('label label-green').html('Plat est Visible &nbsp;<i class="fas fa-eye"></i>');
            }
        })
        // use .on() for bind event on dynamic element.
    }

    //========================= Search Filter =============================
    $('#filter-dishes').keyup(function (){
        $('.card').parent().show();
        var filter = $(this).val().toLowerCase(); // get the value of the input, which we filter on
        $('#result-dishes').find("div .d-none:not(:contains(" + filter + "))").parent().hide();
    })

    // ================== Delete Eevent ======================   
    $(".btn-delete").click(function (event) {
        event.preventDefault();
        var item_id = $(this).parents('.pr-items').attr('id');
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
            $.post(base_url + '/Delete', { 'dish-id': item_id, 'data-price': $(this).attr('data-price'), 'data-extras': $(this).attr('data-extras') })
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
                swal("Erreur!", "Opération échouée, Réessayer une autre fois!", {
                    icon: "error",
                });
                });

            }
        });

    });

    
	$('.btn-edit').click(function() {
        var item_id = $(this).parents('.pr-items').attr('id');
        $(location).attr('href', base_url + '/Edit/' + item_id);
	});
})