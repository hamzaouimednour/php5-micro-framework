var root_html = $('meta[name="base-html"]').attr('content');

function initMap(pLat = "35.896235", pLng = "9.861597", pZoom = 7) {
    try{
        var lat = parseFloat(pLat);
        var lng = parseFloat(pLng);
        document.getElementsByName("latitudeR")[0].value = '';
        document.getElementsByName("longitudeR")[0].value = '';
      var mapCanvas = document.getElementById("map");
      var positionCenter = new google.maps.LatLng(lat, lng);
      var mapOptions = {
        center: positionCenter, zoom: pZoom,
        mapTypeId: google.maps.MapTypeId.HYBRID
      };
      var pin_icon = root_html + "public/img/pin.png";
      var map = new google.maps.Map(mapCanvas, mapOptions);
      var marker = new google.maps.Marker({
        title: "Votre Position",
        position: positionCenter,
        animation: google.maps.Animation.DROP,
        icon: new google.maps.MarkerImage(pin_icon)
      });
      google.maps.event.addListener(map, 'click', function (event) {
        marker.setMap(map);
        placeMarker(map, event.latLng);
        document.getElementsByName("latitudeR")[0].value = parseFloat(event.latLng.lat()).toFixed(6);
        document.getElementsByName("longitudeR")[0].value = parseFloat(event.latLng.lng()).toFixed(6);
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
    // =============== Bootstrap Validation on Focus/out ===============
    $('.needs-validation').find('input:required,textarea:required,select:required').bind('focusout keyup change', function () {
        // check element validity and change class
        $(this).removeClass('is-valid is-invalid')
        .addClass(this.checkValidity() ? 'is-valid' : 'is-invalid');
    });

    var base_url = window.location.origin + window.location.pathname;

$(function () {

    $('#close-note').click(function(){
      $(this).parent().slideUp(300, function() { $(this).remove(); });
    });

    $('#cityInput').change(function(){
        initMap($(this).find(":selected").attr('data-lat'), $(this).find(":selected").attr('data-lng'), 13);
    })

    $("#restaurant-form").submit(function (event) {
        event.preventDefault();
    
        // Form Validation
    
        if ($("#restaurant-form")[0].checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
    
        } else {
    
          $.post(base_url + '/Add', $(this).serialize()) // Serialization Data
    
            .done(function (data) {
                try {
                    var result = jQuery.parseJSON(data);
                    if (result.status !== undefined) {
                        $("#info-section").html(result.info);
                        window.setTimeout(function () {
                          $("#auto-dismiss").fadeTo(50, 0).slideUp(500, function () {
                            $(this).remove();
                          });
                        }, 10000);
                        $("html, body").animate({ scrollTop: 0 }, "slow");
                    }
                } catch (e) {
                    swal("Erreur!", "Operation echoué, Réessayer une autre fois!", "error");
                }
              
            });
        }
        $("#restaurant-form").addClass('was-validated');
      });
})

