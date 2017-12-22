<?php

Route::post('ajax/forcetrad','BlueMountainTeam\Translation\Http\Controllers\Ajax\TranslaterController@forceTrad');
Route::post('ajax/quickupdate/translation', 'BlueMountainTeam\Translation\Http\Controllers\Ajax\TranslaterController@quickUpdate');
