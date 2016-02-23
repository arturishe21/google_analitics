<?php

Route::group(array('prefix' => Config::get('builder::admin.uri'), 'before' => array('auth_admin', 'check_permissions')), function() {

    Route::any('analitics/visitors-overview',  array(
            'as' => 'visitors-overview',
            'uses' => 'Vis\Analitics\AnaliticsController@getVisitors')
    );
    Route::any('analitics/browsers',  array(
            'as' => 'visitors-overview',
            'uses' => 'Vis\Analitics\AnaliticsController@getBrowsers')
    );

    Route::any('analitics/setting',  array(
            'as' => 'analitics_setting',
            'uses' => 'Vis\Analitics\AnaliticsController@getSettings')
    );
    Route::any('analitics/load_statistic',  array(
            'as' => 'load_analitics',
            'uses' => 'Vis\Analitics\AnaliticsController@getStatistic')
    );

    Route::post('analitics/load_statistic_browsers',  array(
            'as' => 'load_analitics_browsers',
            'uses' => 'Vis\Analitics\AnaliticsController@getStatisticBrowsers')
    );
    Route::any('analitics/geography',  array(
            'as' => 'visitors-overview',
            'uses' => 'Vis\Analitics\AnaliticsController@getGeography')
    );


});
