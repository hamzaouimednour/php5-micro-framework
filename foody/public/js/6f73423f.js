$(function () {
    $('input[name="specialty-search[]"]').change(function(){
        $('input[name="specialty-search[]"]').prop('checked', false);
        var r_url = base_url.slice(0, -1);
        window.location.href =  r_url.replace(r_url.split("/").pop(),"") + $(this).attr('data-href');
    })
})