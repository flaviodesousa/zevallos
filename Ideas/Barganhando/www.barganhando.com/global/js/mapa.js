function initialize() {
    var latlng = new google.maps.LatLng($('#latitude').val(), $('#longitude').val());	
	//alert($('#longitude').val());
    var myOptions = {
      zoom: 15,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.HYBRID
    };
    var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	var marker = new google.maps.Marker({
      position: latlng, 
      map: map
  });
	var contentString = $('#endereco').val();//Bermuda Triangle Polygon
	var infowindow = new google.maps.InfoWindow({
		content: contentString
	});
	google.maps.event.addListener(marker, 'click', function() {
	  infowindow.open(map,marker);
	});
}
	   