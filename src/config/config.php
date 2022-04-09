<?php

return [
    // Prefix for admin routes
    'prefix' => 'admin',
    // Prefix for api routes
    'api_prefix' => 'api/admin',

    // middleware for the routes group
    'middleware' => ['auth'],
    // middleware for api routes group
    'api_middleware' => ['auth'],

    // layout to be used by all the views
    'layout' => 'seamless::layout',
];
