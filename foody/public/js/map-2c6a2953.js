function initMap() {
  try{
    
    var mapCanvasModal = document.getElementsByClassName("map")[0];
    var mapCanvas = document.getElementsByClassName("map")[1];

    var cityLat = parseFloat("33.8869");
    var cityLng = parseFloat("9.5375");
    var cityPositionCenter = new google.maps.LatLng(cityLat, cityLng);
    
    var lat = parseFloat(document.getElementsByName("latitude")[0].value);
    var lng = parseFloat(document.getElementsByName("longitude")[0].value);
    var positionCenter = new google.maps.LatLng(lat, lng);

    var mapOptions = {
      center: positionCenter, zoom: 16,
      mapTypeId: google.maps.MapTypeId.HYBRID
    };
    var pin_icon = root_html + "public/img/pin.png";
    var marker = new google.maps.Marker({
      title: "Votre Position",
      position: positionCenter,
      animation: google.maps.Animation.DROP,
      icon: new google.maps.MarkerImage(pin_icon)
    });
    
    //========================== Default Map ==================================
    var map = new google.maps.Map(mapCanvas, mapOptions);

    google.maps.event.addListener(map, 'click', function (event) {
      marker.setMap(map);
      placeMarker(map, event.latLng);
      document.getElementsByName("latitude")[0].value = parseFloat(event.latLng.lat()).toFixed(6);
      document.getElementsByName("longitude")[0].value = parseFloat(event.latLng.lng()).toFixed(6);
    })

    //========================== Modal Map ==================================
    var modalMapOptions = {
      center: cityPositionCenter, zoom: 13,
      mapTypeId: google.maps.MapTypeId.HYBRID
    };
    var mapModal = new google.maps.Map(mapCanvasModal, modalMapOptions);

    google.maps.event.addListener(mapModal, 'click', function (event) {
      marker.setMap(mapModal);
      placeMarker(mapModal, event.latLng);
      document.getElementsByName("modal-latitude")[0].value = parseFloat(event.latLng.lat()).toFixed(6);
      document.getElementsByName("modal-longitude")[0].value = parseFloat(event.latLng.lng()).toFixed(6);
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