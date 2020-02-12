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
    $marker['position'] = '24.8873002,43.2711261';
    $marker['infowindow_content'] = 'Ayman';
    $marker['icon'] = 'https://lh3.googleusercontent.com/proxy/FzZP28hGynqJTC--UcDBbbr3iJTJaQ_r_oDTgZ5priU5LeGK9302SBH4koMSxpMzSqcBbi6IBkd86jFj0NWcUaLA7-iqGsrq5w';
    $gmaps->add_marker($marker);
    $map = $gmaps->create_map();
    return view('welcome')->with('map', $map);
});
