<?php

namespace Soiposervices\HttpAuth;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use SoipoServices\HttpAuth\Middleware\HttpAuth;
use Symfony\Component\HttpKernel\Exception\HttpException;

test('is whitelisted ip', function () {

    $request = Request::create("/");
    
    Config::set('httpauth.whitelist',['127.0.0.1']);

    $middleware = new HttpAuth();
    $next = function()
    {
        return response('This is a secret place');
    };
    $response = $middleware->handle($request, $next);

    expect($response)->toBeNull();
});


test('Is not enabled in environment', function () {

    $request = Request::create("/");
    Config::set('httpauth.environments','production');

    $middleware = new HttpAuth();
    $next = function()
    {
        return response('This is a secret place');
    };
    $response = $middleware->handle($request, $next);

    expect($response)->toBeNull();
});


test('Login with wrong credentials', function () {

    $request = Request::create("/");
    $request->headers->set('Authorization',"123456".base64_encode(("user:password")));

    $next = function()
    {
        return response('This is a secret place');
    };
    
    Config::set('httpauth.environments','testing');

    $middleware = new HttpAuth();

    expect($middleware->handle($request, $next))->toThrow(HttpException::class);

});

test('Login with valid credentials', function () {

    $request = Request::create("/");
    $request->headers->set('Authorization',"123456".base64_encode(("admin:adminpass")));

    $next = function()
    {
        return response('This is a secret place');
    };
    
    Config::set('httpauth.environments','testing');

    $middleware = new HttpAuth();

    $response =$middleware->handle($request, $next);
    expect($response)->toBeInstanceOf(Response::class);   

});


test('Display login form', function () {

    $request = Request::create("/");
    $request->server('REMOTE_ADDR','127.0.0.1');

    Config::set('httpauth.environments','testing');

    $middleware = new HttpAuth();
    $next = function()
    {
        return response('This is a secret place');
    };
    $response = $middleware->handle($request, $next);

    expect($response)->toBeNull();
});
