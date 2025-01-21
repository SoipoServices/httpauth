<?php

namespace Soiposervices\HttpAuth;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use SoipoServices\HttpAuth\Middleware\HttpAuth;
use Symfony\Component\HttpKernel\Exception\HttpException;

test('is whitelisted ip', function () {

    $request = Request::create("/");
    
    Config::set('httpauth.whitelist','127.0.0.1');

    $middleware = new HttpAuth();
    $next = function()
    {
        return response('This is a secret place');
    };
    $response = $middleware->handle($request, $next);

    expect($response)->toBeInstanceOf(Response::class);   

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

    expect($response)->toBeInstanceOf(Response::class);
});

test('Is enabled by default', function () {

    $request = Request::create("/");
    Config::set('httpauth.environments','');

    $middleware = new HttpAuth();
    $next = function()
    {
        return response('This is a secret place');
    };
    try {
        $middleware->handle($request, $next);
    } catch (HttpException $e) {
        expect($e->getStatusCode())->toBe(Response::HTTP_UNAUTHORIZED);
    }
    
});

test('Login with wrong credentials', function () {

    $request = Request::create("/");
    $request->headers->set('Authorization',"Basic ".base64_encode(("user:password")));

    $next = function()
    {
        return response('This is a secret place');
    };
    
    Config::set('httpauth.environments','testing');

    $middleware = new HttpAuth();

    try {
        $middleware->handle($request, $next);
    } catch (HttpException $e) {
        expect($e->getStatusCode())->toBe(Response::HTTP_UNAUTHORIZED);
    }

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

    Config::set('httpauth.environments','testing');

    $middleware = new HttpAuth();
    $next = function()
    {
        return response('This is a secret place');
    };
    try {
        $middleware->handle($request, $next);
    } catch (HttpException $e) {
        expect($e->getStatusCode())->toBe(Response::HTTP_UNAUTHORIZED);
    }

});
