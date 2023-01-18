$(function () {
    $('.starrr').starrr({
        rating: $('#rate-choice').val(),
      change: function(e, value){
        if (value) {
          $('#rate-choice').val(value);
        }
      }
    });
}) 
