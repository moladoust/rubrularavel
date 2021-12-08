<?php

use Moladoust\Rubrularavel\Facades\Rubrularavel;

Route::get('/test', function () {
    $res = Rubrularavel::sendRequest('/webinar/course/5811', [], 'GET');
    dd($res);
    return 'hi ';
});