<!DOCTYPE html>
<html>
<head>
    <title>Laravel 5 - Multiple markers in google map using gmaps.js</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="http://maps.google.com/maps/api/js"></script>
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
            lat: value.latitude,
            lng: value.longitude,
            title: value.city,
            click: function(e) {

                Swal.fire(
                    value.user.name,
                    'This is '+value.city+' Serving patient at '+ value.updated_at,
                    'question',
                    1500
                )
            }
        });
    });


</script>


</body>
</html>
