function initMap(pLat = "35.735877", pLng = "9.482569", pZoom = 20) {
    // try{
      
      var mapModal = document.getElementById("map");

      var cityLat = parseFloat($('#delivery-geo').attr('data-lat'));
        var cityLng = parseFloat($('#delivery-geo').attr('data-lng'));
        var yourPosition = new google.maps.LatLng(cityLat, cityLng);

      var pin_icon = root_html + "public/img/pin.png";
      var marker = new google.maps.Marker({
        title: 'Votre position',
        position: yourPosition,
        animation: google.maps.Animation.DROP,
        icon: new google.maps.MarkerImage(pin_icon)
      });
      
      //========================== Modal Map ==================================
      var modalMapOptions = {
        center: yourPosition, zoom: 17,
        mapTypeId: google.maps.MapTypeId.HYBRID
      };
      var map = new google.maps.Map(mapModal, modalMapOptions);

        marker.setMap(map);

      var infowindow = new google.maps.InfoWindow({
        content: '<i class="mdi mdi-map-marker-radius"></i> <b>Votre position</b>'
      });

      infowindow.open(map, marker);
      marker.addListener('click', function() {
        infowindow.open(map, marker);
      });

      var latR = parseFloat(pLat);
      var lngR = parseFloat(pLng);
      var newLatLng = new google.maps.LatLng(latR, lngR);

      var directionsDisplay = new google.maps.DirectionsRenderer();

      directionsDisplay.setMap(map);
      // Remove default markersd {A : B}
      directionsDisplay.setOptions({ suppressMarkers: true });


      var new_pin_icon = root_html + "public/img/spotlight_pin_v2_blue.png";
      var newMarker = new google.maps.Marker({
        title: 'Destination',
        position: newLatLng,
        animation: google.maps.Animation.DROP,
        icon: new google.maps.MarkerImage(new_pin_icon)
      });

      newMarker.setMap(map);

      var infowindowC = new google.maps.InfoWindow({
        content: '<i class="mdi mdi-home-map-marker"></i> Destination'
      });
      infowindowC.open(map, newMarker);
      newMarker.addListener('click', function() {
        infowindowC.open(map, newMarker);
      });



        placeRouteDirection(directionsDisplay, yourPosition, newLatLng);

    // } catch (e) {$.noop()}

  /**
   * 
   * @param {*} directionsDisplay 
   * @param {*} restaurantLatLng 
   * @param {*} customerLatLng 
   */
  function placeRouteDirection(directionsDisplay, restaurantLatLng, customerLatLng) {
    var directionsService = new google.maps.DirectionsService();
    var start = new google.maps.LatLng(restaurantLatLng.lat(), restaurantLatLng.lng());
    var end = new google.maps.LatLng(customerLatLng.lat(), customerLatLng.lng());

    var request = {
        origin: start,
        destination: end,
        travelMode: google.maps.TravelMode.DRIVING
    };
    directionsService.route(request, function (response, status) {
        if (status == google.maps.DirectionsStatus.OK) {
            directionsDisplay.setDirections(response);
            directionsDisplay.setMap(map);
            map.setZoom(15)
            /**
             * change them with ur own inputs;
             */
            var myroute = response.routes[0];

            document.getElementById("checkout-distance").value = myroute.legs[0].distance.value/1000 + " KM";
            document.getElementById("checkout-time").value = (Math.round((myroute.legs[0].duration.value / 60).toFixed(2) * 2) / 2) + " MIN";

        } else {
            console.log("Directions Request from " + start.toUrlValue(6) + " to " + end.toUrlValue(6) + " failed: " + status);
        }
    });
  }


  /**
   * 
   * @param {*} restaurantLatLng 
   * @param {*} customerLatLng 
   */
  function distance(restaurantLatLng, customerLatLng) {
    var startPoint = new google.maps.LatLng(restaurantLatLng.lat(), restaurantLatLng.lng());
    var endPoint = new google.maps.LatLng(customerLatLng.lat(), customerLatLng.lng());

    distanceBetween = google.maps.geometry.spherical.computeDistanceBetween(startPoint, endPoint);
    if (distanceBetween < 1000) distanceBetween = Math.round(distanceBetween);
    return Math.round(distanceBetween) / 1000;
  }
}
$(function () {
    

    var table = 0 !== $("#data-table-orders").length && $("#data-table-orders").DataTable({
        responsive: !0,
        "order": [[ 2, "desc" ]],
        "lengthMenu": [10, 15, 25, 50, 100],
        "pageLength": 15
    })
    
    $('#data-table-orders').on('click', '.btn-resto', function (event) {
        event.preventDefault();
        var id = $(this).parents('tr').prop('id');
        $('#modal-title-order').text('Chemin vers Restaurant');
        initMap($(this).attr('data-lat'), $(this).attr('data-lng'));
        $('#destination-address').text($('#resto-addr-' + id).text());
        $('#open-gps').attr('href', 'https://www.google.com/maps/dir/?api=1&origin='+$('#delivery-geo').attr('data-lat')+','+$('#delivery-geo').attr('data-lng')+'&destination='+$(this).attr('data-lat')+','+$(this).attr('data-lng')+'&travelmode=driving&basemap=satellite');
        $('#modal-dialog').modal('show');
    })
    
    $('#data-table-orders').on('click', '.btn-customer', function (event) {
        event.preventDefault();
        var id = $(this).parents('tr').prop('id');
        $('#modal-title-order').text('Chemin vers Client');
        initMap($(this).attr('data-lat'), $(this).attr('data-lng'));
        $('#destination-address').text($('#customer-addr-' + id).text());
        $('#open-gps').attr('href', 'https://www.google.com/maps/dir/?api=1&origin='+$('#delivery-geo').attr('data-lat')+','+$('#delivery-geo').attr('data-lng')+'&destination='+$(this).attr('data-lat')+','+$(this).attr('data-lng')+'&travelmode=driving&basemap=satellite');
        $('#modal-dialog').modal('show');
    })
    $('#data-table-orders').on('click', '.btn-delivred', function(event){
        event.preventDefault();
        swal({
          title: "Êtes-vous sûr?",
          text: "Voulez vous changé l'état du commande à Livré ?",
          icon: "info",
          buttons: {
            cancel: {
              text: "Annuler",
              value: null,
              visible: !0,
              className: "btn btn-default",
              closeModal: !0
            },
            confirm: {
              text: "CONFIRMER",
              value: !0,
              visible: !0,
              className: "btn btn-info",
              closeModal: 0
            }
          }
        })
          .then((willAccept) => {
            if (willAccept) {
              $.post(base_url + '/OrderDelivered', { order_id: $(this).parents('tr').prop('id') })
                .done(function (data) {
                  try {
                    var obj = jQuery.parseJSON(data);
                    if (obj.status === 'success') {
                      swal("Succès!", "L'état du commande changé à livré avec succès!", {
                        icon: "success",
                      }).then((willAccept) => {
                        if (willAccept) {
                            location.reload();
                        }
                    })
                    } else {
                      swal({
                        title: "Erreur!",
                        text: obj.info,
                        icon: "error",
                        closeOnEsc: false,
                        closeOnClickOutside: false
                      })
                    }
                  } catch (err) {
                    swal({
                      title: "Erreur!",
                      text: "Operation echoué, réessayer une autre fois!",
                      icon: "error",
                      closeOnEsc: false,
                      closeOnClickOutside: false
                    })
                  }
    
                })
                .fail(function () {
                  swal("Erreur!", "Operation echoué, réessayer une autre fois!", {
                    icon: "error",
                  })
                })
            }
        })
    })

})