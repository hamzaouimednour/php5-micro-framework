function initMap(pLat = "35.735877", pLng = "9.482569", pZoom = 7, initMarker = null) {
    try{
      
      var mapCanvasModal = document.getElementsByClassName("map")[0];

      var cityLat = parseFloat(pLat);
        var cityLng = parseFloat(pLng);
        var cityPositionCenter = new google.maps.LatLng(cityLat, cityLng);

      var pin_icon = root_html + "public/img/pin.png";
      var marker = new google.maps.Marker({
        title: 'Votre position',
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
      var infowindow = new google.maps.InfoWindow({
        content: '<i class="mdi mdi-map-marker-radius"></i> <b>Votre position</b>'
      });
      infowindow.open(mapCanvasModal, marker);
      google.maps.event.addListener(mapModal, 'click', function (event) {
        marker.setMap(mapModal);
        placeMarker(mapModal, event.latLng);
        infowindow.open(mapCanvasModal, marker);
        document.getElementsByName("modal-latitude")[0].value = parseFloat(event.latLng.lat()).toFixed(6);
        document.getElementsByName("modal-longtitude")[0].value = parseFloat(event.latLng.lng()).toFixed(6);
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
$('#cityInput').change(function(){
    initMap($(this).find(":selected").attr('data-lat'), $(this).find(":selected").attr('data-lng'), 13);
})