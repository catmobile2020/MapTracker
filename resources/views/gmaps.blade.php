<!DOCTYPE html>
<html>
<head>
    <title>Laravel 5 - Multiple markers in google map using gmaps.js</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="http://maps.google.com/maps/api/js?key=AIzaSyA0lPbpCDgpB0I9-j0WMZQdua2N_I78ugg"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.24/gmaps.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="https://js.pusher.com/5.1/pusher.min.js"></script>


    <style type="text/css">
        #mymap {
            width: 100vw;
            height: 100vh !important;
            padding: 0 !important;
        }
    </style>


</head>
<body id="mymap">





<script type="text/javascript">


    var locations = @php print_r(json_encode($locations)) @endphp;


    var mymap = new GMaps({
        el: '#mymap',
        lat: 24.8873002,
        lng: 43.2711261,
        zoom:6.2
    });


    $.each( locations, function( index, value ){
        mymap.addMarker({
            lat: value.checks.latitude,
            lng: value.checks.longitude,
            title: value.checks.city,
            click: function(e) {

                Swal.fire(
                    value.name,
                    'This is '+value.checks.city+' Serving patient at '+ value.checks.updated_at,
                    'question',
                    1500
                )
            }
        });
    });

    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('e3ec18d082222380144d', {
        cluster: 'eu',
        forceTLS: true
    });

    var channel = pusher.subscribe('map-channel');
    channel.bind('map-event', function(data) {
        mymap.removeMarkers();
        var locations = JSON.stringify(data.locations);

        $.each( JSON.parse(locations), function( index, value ){
            mymap.addMarker({
                lat: value.checks.latitude,
                lng: value.checks.longitude,
                title: value.checks.city,
                icon:'https://img.icons8.com/ios/50/000000/marker-storm--v1.png',
                click: function(e) {

                    Swal.fire(
                        value.name,
                        'This is '+value.checks.city+' Serving patient at '+ value.checks.updated_at,
                        'question',
                        1500
                    )
                }
            });
            Swal.fire({
                position: 'top-end',
                title: value.name +' Serving patient in ' +value.checks.city+ ' at '+ value.checks.updated_at,
                showConfirmButton: false,
                timer: 1500
            })
        });
    });


</script>


</body>
</html>
