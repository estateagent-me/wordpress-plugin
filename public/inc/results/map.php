<div class="mb-4">
    <div id="gMap" class="w-100" style="height:800px;">
    
    </div>
</div>

<script type="text/javascript">
function initMap() {
    var locations = [
        <?php foreach ($properties as $property):
            ob_start();
            include 'map.pin-content.php';
            $pin_content = ob_get_clean();
            $pin_content = str_replace("'", "\'", $pin_content);
            $pin_content = str_replace("\n", "", $pin_content);
            ?>
            ['<?php echo $pin_content; ?>', <?php echo $property->lat; ?>, <?php echo $property->long; ?>, 3],
        <?php endforeach; ?>
    ];

    window.map = new google.maps.Map(document.getElementById('gMap'), {
        mapTypeId: google.maps.MapTypeId.ROADMAP,
    });

    var infowindow = new google.maps.InfoWindow();
    var bounds = new google.maps.LatLngBounds();

    for (i = 0; i < locations.length; i++) {
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
            map: map
        });

        bounds.extend(marker.position);

        google.maps.event.addListener(marker, 'click', (function (marker, i) {
            return function () {
                infowindow.setContent(locations[i][0]);
                infowindow.open(map, marker);
            }
        })(marker, i));
    }

    var listener = google.maps.event.addListener(map, "idle", function () {
        google.maps.event.removeListener(listener);
        map.fitBounds(bounds);
    });
}
</script>
<script src="https://maps.googleapis.com/maps/api/js?sensor=false&key=<?php echo get_option('ea-google-maps-api-key'); ?>" async defer></script>