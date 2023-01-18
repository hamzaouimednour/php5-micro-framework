$(function () {
    $(".show-pwd").click(function (event) {
        event.preventDefault();
        if ($(this).parent().prev().attr("type") == "text") {
            $(this).parent().prev().attr('type', 'password');
            $(this).find('i').removeClass("mdi-eye").addClass("mdi-eye-off");
        }else{
            $(this).parent().prev().attr('type', 'text');
            $(this).find('i').removeClass("mdi-eye-off").addClass("mdi-eye");
        }
    });
})