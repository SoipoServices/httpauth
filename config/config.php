<?php

/*
 * These are the configurations for the http basic
 */
return [
    //These are the username and password used for http auth
    'username'=>env('HTTP_AUTH_USER','admin'),
    'password'=>env('HTTP_AUTH_PASS','adminpass'),
    //This configuration can be used to whitelist some ip
    'whitelist' => [],
    'environments' => env('HTTP_AUTH_ENABLED_ENV','prod,stage')
];