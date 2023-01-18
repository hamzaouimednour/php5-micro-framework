
var base_url = window.location.origin + window.location.pathname;

if(base_url.endsWith("/")){
    base_url.slice(0, -1);
}

var root_html = $('meta[name="base-html"]').attr('content');

$(function () {

  $('[data-toggle="tooltip"]').tooltip({
    trigger: 'hover'
  })
  $('#close-note').click(function(){
    $(this).parent().slideUp(300, function() { $(this).remove(); });
  });
  // ================== Login Backend ==================
  $("form#backend-login-form").on("submit", function (event) {

    $("#login-btn").find("span").addClass("spinner-border spinner-border-sm");
    $("#login-btn").prop("disabled", true);

    event.preventDefault();

    var request = $(this).serialize();

    $.post(base_url + '/auth', request)
      .done(function (data) {

        var obj = jQuery.parseJSON(data);

        if (obj.status === "success") {
          $("#login-btn").find("span").removeClass("spinner-border spinner-border-sm").addClass("ion-md-checkmark-circle");
          setTimeout(function () {
            $(location).attr('href', obj.url);
          }, 2000);
        } else {
          $("#login-btn").find("span").removeClass("spinner-border spinner-border-sm");
          $("#login-btn").prop("disabled", false);
          swal('Echoué','Les informations sont incorrect, éssayer a nouveau!','error');
        }

      });
  });

});

// =============== Form Validate Function ===============
function validateForm(from_id) {
  'use strict';
  window.addEventListener('load', function () {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName(from_id);
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function (form) {
      form.addEventListener('submit', function (event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
}

// =============== Auto Dismiss Function ===============
function dismissAlert(alert_id, timeout_secs) {
  window.setTimeout(function () {
    $(alert_id).fadeTo(50, 0).slideUp(500, function () {
      $(this).hide();
    });
  }, timeout_secs);
}
function removeAlert(alert_id, timeout_secs) {
  window.setTimeout(function () {
    $(alert_id).fadeTo(50, 0).slideUp(500, function () {
      $(this).remove();
    });
  }, timeout_secs);
}
// =============== Bootstrap Validation on Focus/out ===============
$('.needs-validation').find('input:required,textarea:required,select:required').bind('focusout keyup change', function () {
  // check element validity and change class
  $(this).removeClass('is-valid is-invalid')
    .addClass(this.checkValidity() ? 'is-valid' : 'is-invalid');
});
function encryptInt(num) {
  return (num + 180) * 6;
}

//=========================== G-MAP ===================================
function initMap() {
  try{
    var lat = parseFloat(document.getElementById("latitudeR").value);
    var lng = parseFloat(document.getElementById("longitudeR").value);
    var mapCanvas = document.getElementById("map");
    var positionCenter = new google.maps.LatLng(lat, lng);
    var mapOptions = {
      center: positionCenter, zoom: 16,
      mapTypeId: google.maps.MapTypeId.HYBRID
    };
    var pin_icon = root_html + "public/img/pin.png";
    var map = new google.maps.Map(mapCanvas, mapOptions);
    var marker = new google.maps.Marker({
      title: '<i class="fas fa-map-marker-alt"></i> Votre Position',
      position: positionCenter,
      animation: google.maps.Animation.DROP,
      icon: new google.maps.MarkerImage(pin_icon)
    });
    marker.setMap(map);
    google.maps.event.addListener(map, 'click', function (event) {
      placeMarker(map, event.latLng);
      document.getElementById("latitudeR").value = parseFloat(event.latLng.lat()).toFixed(6);
      document.getElementById("longitudeR").value = parseFloat(event.latLng.lng()).toFixed(6);
    })

    // Functions Section
    function placeMarker(map, location) {
      if (!marker || !marker.setPosition) {
        marker = new google.maps.Marker({
          position: location,
          map: map,
          icon: pin_icon
        });
      } else { marker.setPosition(location); }
    }
  } catch (e) {$.noop()}
}