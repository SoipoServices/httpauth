<?php

namespace SoipoServices\HttpAuth\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpKernel\Exception\HttpException;

class HttpAuth
{

    public function handle(Request $request, Closure $next)
    {
        if (!$this->isWhiteListed() && $this->isEnabledInEnv()) {
            if ($request->hasHeader('Authorization') === false) {
                // Display login prompt
                header('WWW-Authenticate: Basic realm="HiBit"');
                throw new HttpException(Response::HTTP_UNAUTHORIZED);
            }

            $credentials = base64_decode(substr($request->header('Authorization'), 6));
            list($username, $password) = explode(':', $credentials);
            if(!$this->isAuthenticated($password)){
                if ($username !== $this->getUser() || $password !== $this->getPassword()) {
                    // Provided username or password does not match, throw an exception
                    // Alternatively, the login prompt can be displayed once more
                    header('WWW-Authenticate: Basic realm="HiBit"');
                    throw new HttpException(Response::HTTP_UNAUTHORIZED);
                }
            }
            
            return $next($request);
        }
        return $next($request);
    }

    protected function isEnabledInEnv():bool{
        $config = config('httpauth.environments');
        if(empty($config)){
            return true;
        }
        return App::environment(explode(',',$config));
        
    }


    protected function getUser():string{
       return config('httpauth.username');
    }

    protected function getPassword():string{
        return config('httpauth.password');
    }

    protected function isWhiteListed():bool
    {
        $ips = explode(',',config('httpauth.whitelist'));
        return array_search(request()->server('REMOTE_ADDR'), $ips) !== false;
    }

    protected function isAuthenticated($password):bool
    {
        $username = request()->server('PHP_AUTH_USER');
        if ($username === null || $this->getUser() != $username) {
            return false;
        }
        return $this->getPassword() === request()->server('PHP_AUTH_PW');
    }
}
