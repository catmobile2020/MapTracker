<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use FarhanWazir\GoogleMaps\GMaps;

Route::get('/', function () {
    $config = array();
    $config['center'] = '24.8873002,43.2711261';
    $config['zoom'] = '6.5';
    $config['map_height'] = "100vh";
    $config['map_width'] = '100vw';
    $config['scrollwheel'] = true;
    $gmaps = new GMaps($config);
    $gmaps->initialize($config);



    //add marker
    $marker['position'] = '21.8873002,44.2711261';
    $marker['infowindow_content'] = 'Ayman';

    $marker2['position'] = '24.8873002,43.2711261';
    $marker2['infowindow_content'] = 'Mohamed';

    $marker3['position'] = '22.8873002,42.2711261';
    $marker3['infowindow_content'] = 'Andrew';


    $gmaps->add_marker($marker);
    $gmaps->add_marker($marker2);
    $gmaps->add_marker($marker3);
    $map = $gmaps->create_map();
    return view('welcome')->with('map', $map);
});


Route::get('gmaps', 'CheckController@gmaps');
