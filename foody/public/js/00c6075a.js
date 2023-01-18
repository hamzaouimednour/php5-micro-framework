$(function () {

    $('#rate-modal-form').on('submit', function(event){
        event.preventDefault();

        if ($("#rate-modal-form")[0].checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();

        } else {
            $.post(root_url + 'user/rate/Add', $(this).serialize()) // Serialization Data

            .done(function (data) {
                var result = JSON.parse(data);
                if (result.status !== undefined) {
                    $("#rate-info-section").html(result.info);
                    removeAlert("#auto-dismiss", 5000);
                    $("#rate-modal-form").removeClass('was-validated');
                }
            });
        }
        $("#rate-modal-form").addClass('was-validated');
    })


    // Listen for click on toggle checkbox
    $('#cat-all').click(function (event) {
        if (this.checked) {
            // Iterate each checkbox
            $("input:checkbox[name='cat-menu[]']").each(function () {
                this.checked = false;
            });
            $('.card-item').parent().show();
            // $('#card-row').find("div .d-none:not(:contains(''))").parent().hide();
    
        }
    });

    $("input:checkbox[name='cat-menu[]']").change(function () {
        var selected_menus = $("input:checkbox[name='cat-menu[]']:checked");
        (selected_menus.length > 0) ? $('#cat-all').prop('checked', false) : $('#cat-all').prop('checked', true);
        var catArray = [];
        selected_menus.each(function () {
            catArray.push( ':contains("' + this.value + '")');
        })
        $('.card-item').parent().show();
        if(catArray.length){
            $('#card-row').find("div .menu-id:not(" + catArray.join(', ') + ")").parent().hide();
        }
    });
    $('#filter-dishes').keyup(function (){
        $('.card-item').parent().show();
        var filter = $(this).val().toLowerCase(); // get the value of the input, which we filter on
        $('#card-row').find("div .dish-name:not(:contains(" + filter + "))").parent().hide();
    })

})