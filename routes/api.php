<?php

Route::group(['as' => 'api.', 'namespace' => 'Api'], function() {
    
    Route::group(['prefix' => 'poll', 'as' => 'poll.'], function() {
        
        Route::post('', 'PollController@create')->name('create');
        Route::get('/{id}', 'PollController@get')->name('get');
        Route::post('/vote', 'PollController@vote')->name('vote');
        
    });
    
    
    
});