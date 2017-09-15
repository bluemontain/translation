<?php

Route::post('ajax/forcetrad','BlueMountainTeam\Translation\Http\Controllers\Ajax\TranslaterController@forceTrad');
Route::post('quickupdate', 'BlueMountainTeam\Translation\Http\Controllers\Ajax\TranslaterController@postQuickUpdate');
