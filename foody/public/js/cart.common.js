
var handleCartData = function(e) {
    $.post( root_url + "Cart/Init", {init: true})
        .done(function( data ) {
            try {
                var result = JSON.parse(data);
                if(result.status == 'success'){
                    $('.cart-sidebar-body').html(result.info.data);
                    $('#cart-value, #items-cart').text(result.info.count_items);
                    $('#subtotal-cart').text(result.info.subtotal_cart);
                    $('#total-cart').text(result.info.total_cart);
                }else{
                    swal("Erreur !", "Erreur survenue, veuillez réessayer!", "error");
                }
            } catch (error) {  swal("Erreur !", "Erreur survenue, veuillez réessayer!", "error"); }
    }, "json");
},
Cart = function() {
    "use strict";
    var a;
    return {
        init: function(e) {
            e && (a = e), this.initCart()
        },
        initCart: function() {
            handleCartData()
        }
    }
}();