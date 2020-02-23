<!DOCTYPE html>
<html>
<head>
    <title>Laravel 5 - Multiple markers in google map using gmaps.js</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="http://maps.google.com/maps/api/js?key=AIzaSyA0lPbpCDgpB0I9-j0WMZQdua2N_I78ugg"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.24/gmaps.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

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
        zoom:6.5
    });


    $.each( locations, function( index, value ){
        mymap.addMarker({
            lat: value.checks.latitude,
            lng: value.checks.longitude,
            title: value.checks.city,
            id: value.id,
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


</script>


</body>
</html>
