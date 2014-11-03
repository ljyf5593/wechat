<?php

Route::set('wechat', 'wechat(/<controller>(/<action>(/<id>)))')
    ->defaults(array(
        'directory'  => 'wechat',
        'controller' => 'home',
        'action'     => 'index',
    ));