$(function () {
    $("#edit-options").submit(function (event) {
        event.preventDefault();
        // Form Validation
    
        if ($("#edit-options")[0].checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
    
        } else {
          $.post(base_url + '/Edit', $(this).serialize()) // Serialization Data
    
            .done(function (data) {
              var result = jQuery.parseJSON(data);
              if (result.status != undefined) {
                $("#info-section").html(result.info);
                removeAlert("#auto-dismiss", 5000);
    
              }
            });
        }
        $("#edit-options").addClass('was-validated');
      })
})