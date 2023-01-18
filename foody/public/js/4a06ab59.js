$(function () {

    $("#bd-address-modal .select2").select2({
        dropdownParent: $('#bd-address-modal .modal-content')
    });
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
    
    //check collapases
    $('#collapse2').click(function(e){
        e.preventDefault();
        if(!$('#selectAddr').val()){
            e.stopPropagation();
            $('#selectAddr').addClass('is-invalid');
            return false;
        }
        $.post(base_url + '/Address', { addr_id: $('#selectAddr').val() }) // Serialization Data

        .done(function (data) {
            var result = JSON.parse(data);
            if (result.status == 'failed') {
                swal('Erreur!', result.info, 'error');
                e.stopPropagation();
                $('#selectAddr').addClass('is-invalid');
                return false;
            }
            $('#checkout-delivery').text($('#checkout-delivery_fee').val().slice(0, -3));
        })
    })

    $('#order-submit').click(function(e){
        e.preventDefault();
        e.stopPropagation();
        $(this).html('CONFIRMER LA COMMANDE &nbsp;<i class="fas fa-circle-notch fa-spin"></i>');
        $.post(base_url + '/Submit', { order_error: $('[name="order-err"]:checked').val() }) // Serialization Data

        .done(function (data) {
            try{
                var result = JSON.parse(data);
                if (result.status == 'failed') {
                    $('.order-done').html('<i class="mdi mdi-close-circle-outline text-danger"></i><h4 class="text-danger">Opération a échoué, veuillez réessayer plus tard.</h4><p>des erreurs se sont produites lors du traitement de votre demande. si ce message apparaît plusieurs fois, contactez-nous.</p>');
                }else{
                    $('.order-done').html('<i class="mdi mdi-check-circle-outline text-secondary"></i><h4 class="text-success">Félicitations! Votre commande a été soumise.</h4><p>Nous vous remercions d’avoir visité notre site Web et effectué votre achat. Nous nous engageons à faire de notre mieux pour répondre à tous vos besoins, nous espérons qu’ils vous plairont. Si vous avez une demande ou une question, contactez-nous.</p>');
                }
                $('#collapsefour').collapse("show")	;
                
            }catch{
                swal('Echéc!', 'Erreur survenue, veuillez réessayer!', 'error');
            }
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
                var result = JSON.parse(data);
                if (result.status !== undefined) {
                    $("#alert-section").html(result.info);
                    $(".modal").animate({ scrollTop: 0 }, "slow");
                    removeAlert("#auto-dismiss", 5000);
                    $("#addr-add-form").removeClass('was-validated');
                    $('#addr-add-form input, #addr-add-form textarea').each(function( item ){
                      $(this).val('');
                    })
                    if(result.addr_count == 1){
                        $('#selectAddr').append('<option value="" disabled selected>Sélectionner adresse de livraison</option>');
                    }
                    $('#selectAddr').append(result.data);
                    $('#new-address').removeClass('d-none');
                    setTimeout(function () {
                        $('#bd-address-modal').modal('hide');
                    }, 6000);
                    
                }
            });
        }
        $("#addr-add-form").addClass('was-validated');
    });


    // Refresh cart Data
    setInterval(function(){
        $.post(base_url + "/List", { refresh: true })
            .done(function (data) {
                try {
                    var result = JSON.parse(data);
                    if (result.status == 'success') {
                        $('#checkout-content').html(result.data);
                    }
                } catch (error) { console.log("Erreur survenue, veuillez réessayer!"); }
            }, "json");
    }, 1000);
})