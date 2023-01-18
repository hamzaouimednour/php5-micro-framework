
function initMap(pLat = $('#cityInput').find(":selected").attr('data-lat'), pLng = $('#cityInput').find(":selected").attr('data-lng'), pZoom = 13, initMarker = null) {
  // try{
    
    var mapCanvasModal = document.getElementsByClassName("map")[0];

      var cityLat = parseFloat(pLat);
      var cityLng = parseFloat(pLng);
      var cityPositionCenter = new google.maps.LatLng(cityLat, cityLng);

    var pin_icon = root_html + "public/img/pin.png";
    var marker = new google.maps.Marker({
      title: "<b>Votre position</b>",
      position: cityPositionCenter,
      animation: google.maps.Animation.DROP,
      icon: new google.maps.MarkerImage(pin_icon)
    });
    
    //========================== Modal Map ==================================
    var modalMapOptions = {
      center: cityPositionCenter, zoom: pZoom,
      mapTypeId: google.maps.MapTypeId.HYBRID
    };
    var mapModal = new google.maps.Map(mapCanvasModal, modalMapOptions);
    if(initMarker != null){
      marker.setMap(mapModal);
      placeMarker(mapModal, {lat: cityLat, lng: cityLng});
    }
    google.maps.event.addListener(mapModal, 'click', function (event) {
      marker.setMap(mapModal);
      placeMarker(mapModal, event.latLng);
      document.getElementsByName("modal-latitude")[0].value = parseFloat(event.latLng.lat()).toFixed(6);
      document.getElementsByName("modal-longtitude")[0].value = parseFloat(event.latLng.lng()).toFixed(6);
    })

    //========================== Resto Map ==================================
    var restoGeo = document.getElementById('resto-geo');
    var latR = parseFloat(restoGeo.getAttribute("data-resto-lat"));
    var lngR = parseFloat(restoGeo.getAttribute("data-resto-lng"));
    var restaurantLatLng = new google.maps.LatLng(latR, lngR);
    var mapCanvas = document.getElementsByClassName("map")[1];
    var myCenter = new google.maps.LatLng(latR, lngR);

    infowindowR = new google.maps.InfoWindow({
      content: document.getElementById('resto-info').innerHTML,
    });
    var mapOptions = {
        center: myCenter, zoom: 17,
        mapTypeId: google.maps.MapTypeId.HYBRID
    };

    var directionsDisplay = new google.maps.DirectionsRenderer();
    var map = new google.maps.Map(mapCanvas, mapOptions);

    directionsDisplay.setMap(map);
    // Remove default markersd {A : B}
    directionsDisplay.setOptions({ suppressMarkers: true });

    var iconR = root_html + "public/img/spotlight_pin_v2_blue.png";
    var markerR = new google.maps.Marker({
        title: "Position du Restaurant",
        position: new google.maps.LatLng(latR, lngR),
        animation: google.maps.Animation.DROP,
        icon: new google.maps.MarkerImage(iconR)
    });

    //Events Listeners
    markerR.setMap(map);
    infowindowR.open(map, markerR);
    markerR.addListener('click', function() {
      infowindowR.open(map, markerR);
    });

    if($('#selectAddr').val()){
      var latC = parseFloat($("#selectAddr option:selected").attr('data-lat'));
      var lngC = parseFloat($("#selectAddr option:selected").attr('data-lng'));
      var clientLatLng = new google.maps.LatLng(latC, lngC);

      var iconC = root_html + "public/img/pin.png";
      var markerC = new google.maps.Marker({
          title: "Votre Position",
          position: clientLatLng,
          animation: google.maps.Animation.DROP,
          icon: new google.maps.MarkerImage(iconC)
      });
      var infowindowC = new google.maps.InfoWindow({
        content: '<i class="mdi mdi-home-map-marker"></i>' + $("#selectAddr option:selected").text()
      });

      markerC.setMap(map);
      infowindowC.open(map, markerC);
      markerC.addListener('click', function() {
        infowindowC.open(map, markerC);
      });

      placeRouteDirection(directionsDisplay, restaurantLatLng, clientLatLng);
    }

    $('#selectAddr').on('change', function(){

      var latC = parseFloat($("#selectAddr option:selected").attr('data-lat'));
      var lngC = parseFloat($("#selectAddr option:selected").attr('data-lng'));
      var clientLatLng = new google.maps.LatLng(latC, lngC);

      var iconC = root_html + "public/img/pin.png";
      var markerC = new google.maps.Marker({
          title: "Votre Position",
          position: clientLatLng,
          animation: google.maps.Animation.DROP,
          icon: new google.maps.MarkerImage(iconC)
      });
      var infowindowC = new google.maps.InfoWindow({
        content: '<i class="mdi mdi-home-map-marker"></i>' + $("#selectAddr option:selected").text()
      });

      markerC.setMap(map);
      infowindowC.open(map, markerC);
      markerC.addListener('click', function() {
        infowindowC.open(map, markerC);
      });

      placeRouteDirection(directionsDisplay, restaurantLatLng, clientLatLng);

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

            /**
             * change them with ur own inputs;
             */
            var myroute = response.routes[0];
            var delivery = document.getElementById('delivery-info');

            document.getElementById("checkout-distance").value = myroute.legs[0].distance.value/1000 + " KM";
            document.getElementById("checkout-time").value = ((Math.round((myroute.legs[0].duration.value / 60).toFixed(2) * 2) / 2) + parseInt(delivery.getAttribute('data-avg-preparation'))) + " MIN";
            document.getElementById("checkout-delivery_fee").value = deliveryFee(myroute.legs[0].distance.value/1000, parseFloat(delivery.getAttribute('data-fee')), parseFloat(delivery.getAttribute('data-init-distance')), parseFloat(delivery.getAttribute('data-up-fee')) ) + " DT";
            if((myroute.legs[0].distance.value/1000) > 10){
              swal('Avertissement', "La livraison n'est pas disponible pour cette Adresse!", "warning");
              $('#collapse2').prop('disabled', true);
            }else{
              $('#collapse2').prop('disabled', false);
            }
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

  /**
   * 
   * @param {*} distance 
   * @param {float} deliveryFeeInit 
   * @param {float} distanceInit 
   * @param {float} deliveryFeeUp 
   * @param {float} deliveryMaxFee 
   */
  function deliveryFee(distance, deliveryFeeInit, distanceInit, deliveryFeeUp) {
    deliveryFee = deliveryFeeInit;
    if (distance > distanceInit) { // distanceInit = 4.000 [4KM]
        var distanceUp = distance - distanceInit;
        var decimal = Math.abs(distanceUp) - Math.floor(Math.abs(distanceUp));
        var distanceUpNew = parseFloat( Math.floor(distanceUp) + '.' + Math.floor(decimal * 10) + '00');
        // var quotient = Math.floor(distanceUp / 1.0) + 1;
        // var remainder = mDis % 0.5;
        // var deliveryFeeUp = 0.500;
        var deliveryFee = deliveryFeeInit + (distanceUpNew * deliveryFeeUp);
    }
    if (deliveryFee < deliveryFeeInit) {
        deliveryFee = deliveryFeeInit;
    }
    // else if (deliveryFee > deliveryMaxFee) {
    //     // launch Popover >[Votre adresse est en dehors de notre zone de livraison];
    //     console.log(false);
    //     return false;
    // }
    return deliveryFee.toFixed(3);

  }


  // } catch (e) {$.noop()}
}