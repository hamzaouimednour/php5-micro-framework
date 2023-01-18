/**
 * 
 */

var base_url = window.location.origin + window.location.pathname;

if(base_url.endsWith("/")){
    base_url.slice(0, -1);
}

var root_url = $('meta[name="url"]').prop('content');

var root_html = $('meta[name="base-html"]').attr('content');

$(function () {

    $('[data-toggle="tooltip"]').tooltip({container:'body', trigger: 'hover', placement:"bottom"});
    $('[data-toggle="popover"]').popover({container:'body', trigger: 'hover click', placement:"top"});

    // ================== Check required All inputs ==================
    $('.needs-validation').find('input:required,textarea:required,select:required').bind('focusout keyup change', function () {
        // check element validity and change class
        $(this).removeClass('is-valid is-invalid')
          .addClass(this.checkValidity() ? 'is-valid' : 'is-invalid');
    });

    // ================== Login Modal > show password ==================
    $("#show-password").on('click', function (event) {
        event.preventDefault();
        if ($('#show-hide-password input').attr("type") == "text") {
            $('#show-hide-password input').attr('type', 'password');
            $("#show-password").find('i').removeClass("mdi-eye").addClass("mdi-eye-off");
        }else{
            $('#show-hide-password input').attr('type', 'text');
            $("#show-password").find('i').removeClass("mdi-eye-off").addClass("mdi-eye");
        }
    });
    $(".show-pwd").on('click', function (event) {
        event.preventDefault();
        var input = $(this).parent().prev();
        if (input.attr("type") == "text") {
            input.attr('type', 'password');
            $(this).find('i').removeClass("mdi-eye").addClass("mdi-eye-off");
        }else{
            input.attr('type', 'text');
            $(this).find('i').removeClass("mdi-eye-off").addClass("mdi-eye");
        }
    });
    $("#update-user-account").on('click', function (event) {
        event.preventDefault();
        
        if ($('#update-account-info')[0].checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            var pwdTest = true;
        if ($('input[name="user-newpwd"]').val() == $('input[name="user-oldpwd"]').val()) {
            var pwdTest = false;
            $('input[name="user-newpwd"]').parent().find('.invalid-feedback').text('Choisissez Mot de passe différent de la dernier');
            $('input[name="user-newpwd"]').removeClass('is-invalid is-valid').addClass('is-invalid');
            
        } else {
            $('input[name="user-newpwd"]').parent().find('.invalid-feedback').text('Ce champ devient obligatoire');
            $('input[name="user-newpwd"]').removeClass('is-invalid is-valid').addClass('is-valid');
        }
            if (pwdTest) {
                $('#update-user-account').find('i').removeClass().addClass('fas fa-circle-notch fa-spin');
                var data = {oldpwd: $('input[name="user-oldpwd"]').val() , newpwd: $('input[name="user-newpwd"]').val() };
                $.post(root_url + "user/account/Edit", data) // Serialization Data

                    .done(function (data) {
                        try {
                            var result = jQuery.parseJSON(data);
                            if (result.status !== undefined) {
                                $('#account-update-info').html(result.info);
                                $('#update-user-account').find('i').removeClass().addClass('fas fa-check-circle');
                                setTimeout(() => {
                                    $('#update-user-account').find('i').removeClass().addClass('fas fa-save');
                                }, 3000);
                                $("#update-account-info").removeClass('was-validated');
                                $('input[name="user-oldpwd"]').val('').removeClass('is-invalid is-valid');
                                $('input[name="user-newpwd"]').val('').removeClass('is-invalid is-valid');
                                removeAlert("#auto-dismiss", 5000);
                            }
                        } catch (error) {
                            swal("Erreur!", "Opération échouée, Réssayer une autre fois!", "error");
                        }
                    });
            }
        }
        $("#update-account-info").addClass('was-validated');
    });
    $('#bd-profile-modal').on('hidden.bs.modal', function (e) {
        $("#update-account-info").removeClass('was-validated');
        $('input[name="user-oldpwd"]').val('').removeClass('is-invalid is-valid');
        $('input[name="user-newpwd"]').val('').removeClass('is-invalid is-valid');
      })
    $('input[name="user-oldpwd"]').keyup(function () {
        if (this.value == '') {
            $(this).removeClass('is-invalid is-valid');
            // $(this).parent().find('.valid-feedback').hide();
            // $(this).parent().find('.invalid-feedback').hide();
            $('#update-user-account').prop('disabled', true);
        }else{
            $.post(root_url + 'user/account/Search', { oldpwd: $('input[name="user-oldpwd"]').val() }) // Serialization Data

                .done(function (data) {
                    var result = jQuery.parseJSON(data);
                    if (result.status === 'failed') {
                        // $('input[name="user-oldpwd"]').parent().find('.invalid-feedback').show();
                        $('input[name="user-oldpwd"]').removeClass('is-invalid is-valid').addClass('is-invalid');
                        // $('input[name="user-oldpwd"]').parent().find('.valid-feedback').hide();
                        $('#update-user-account').prop('disabled', true);
                    } else {
                        // $('input[name="user-oldpwd"]').parent().find('.valid-feedback').show();
                        $('input[name="user-oldpwd"]').parent().find('.valid-feedback').text('Mot de passe correct');
                        // $('input[name="user-oldpwd"]').parent().find('.invalid-feedback').hide();
                        $('input[name="user-oldpwd"]').removeClass('is-invalid is-valid').addClass('is-valid');
                        $('#update-user-account').prop('disabled', false);
                    }
                });
        }
    })
    // ================== Login Modal > Login ==================
    $('#signin-btn').on( "click", function( event ) {
        event.preventDefault();
        var checkValidationErrors = 0;
        $("#login input:required").each(function (i, el) {
            if(!this.checkValidity()){ checkValidationErrors++; }
            $(this).removeClass('is-valid is-invalid').addClass(this.checkValidity() ? 'is-valid' : 'is-invalid');
        })
        if(checkValidationErrors == 0){

            var email = $("input[name='signin-email']").val();
            var pass  = $("input[name='signin-pass']").val();
            $("#signin-btn").prop("disabled", true);
            $('#signin-btn').find('i').removeClass().addClass('spinner');
            if($('#remember_me_checkbox').prop('checked')){
                var data = { username: email, password: pass, remember_me: '1' }
            }else{
                var data = { username: email, password: pass, remember_me: '0' }
            }
            $.post( root_url + "user/login", data)
                .done(function(data){
                    try{
                        var obj = jQuery.parseJSON( data );
                        
                        if(obj.status === 'success'){
                            $('#signin-btn').find('i').removeClass().addClass('mdi mdi-checkbox-marked-circle');
                            $('#login-note-section').html(obj.info);
                            setTimeout(function () {
                                if($('#page-redirect').attr('data-redirect')){
                                    location.href = $('#page-redirect').attr('data-redirect');
                                }else{
                                    location.reload();
                                }
                            }, 2500);
                            
                        }else{
                            $('#signin-btn').find('i').removeClass('spinner').addClass('mdi mdi-login');
                            $("#signin-btn").prop("disabled", false);
                            $('#login-note-section').html(obj.info);
                            setTimeout(function () {
                                $('#auto-dismiss').slideUp(300, function() { $(this).remove(); });
                            }, 5000);
                        }
                    } catch (error) {
                        swal('Echec', 'opération a échoué s\'il vous plaît essayez à nouveau', 'error');
                    }
                });
        }
    });

    // ================== Login Modal > SignUp ==================
    $('#signup-btn').on( "click", function( event ) {
        event.preventDefault();
        var checkValidationErrors = 0;
        $("#register input:required").each(function (i, el) {
            if(!this.checkValidity()){ checkValidationErrors++; }
            $(this).removeClass('is-valid is-invalid').addClass(this.checkValidity() ? 'is-valid' : 'is-invalid');
        })
        if(checkValidationErrors == 0){
            var request = {
                name: $("input[name='signup-name']").val(),
                email: $("input[name='signup-email']").val(),
                phone: $("input[name='signup-phone']").val(),
                pass: $("input[name='signup-pass']").val()
            };
            $("#signup-btn").prop("disabled", true);
            $('#signup-btn').find('i').removeClass().addClass('spinner');

            $.post( root_url + "user/signup", request)
                .done(function(data){
                    try {
                        var obj = jQuery.parseJSON( data );
                        if(obj.status === 'success'){
                            $('#signup-btn').find('i').removeClass().addClass('mdi mdi-checkbox-marked-circle');
                            $("#signup-btn").prop("disabled", false);
                            $('#register-note-section').html(obj.info);
                            $('#register').find('input,textarea,select').each(function () {
                                $(this).removeClass('is-valid is-invalid').val('');
                            })
                            setTimeout(function () {
                                $('#auto-dismiss').slideUp(300, function() { $(this).remove(); });
                            }, 8000);
                        }else{
                            $('#signup-btn').find('i').removeClass().addClass('mdi mdi-checkbox-marked-circle');
                            $("#signup-btn").prop("disabled", false);
                            $('#register-note-section').html(obj.info);
                            setTimeout(function () {
                                $('#auto-dismiss').slideUp(300, function() { $(this).remove(); });
                            }, 8000);
                        }
                    } catch (error) {
                        swal('Echec', 'opération a échoué s\'il vous plaît essayez à nouveau', 'error');
                    }
                    
                });
        }
    });

    // ================== Login Modal > Reset ==================
    $("#bd-login-modal").on('hide.bs.modal', function(){
        $("#form-user").removeClass('was-validated');
        $('#form-user').find('input,textarea,select').each(function () {
            $(this).removeClass('is-valid is-invalid');
        })
        $('#form-user').each(function () {
            this.reset();
        })
        $('#login-note-section').html('');
        $('#register-note-section').html('');
    });

})
function removeAlert(alert_id, timeout_secs) {
    window.setTimeout(function () {
      $(alert_id).fadeTo(50, 0).slideUp(500, function () {
        $(this).remove();
      });
    }, timeout_secs);
}