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
        if (!$this->isWhiteListed() && App::environment($this->getEnabledEnvironments())) {
            if ($request->hasHeader('Authorization') === false) {
                // Display login prompt
                header('WWW-Authenticate: Basic realm="HiBit"');
                exit;
            }

            $credentials = base64_decode(substr($request->header('Authorization'), 6));
            list($username, $password) = explode(':', $credentials);
            if(!$this->isAuthenticated($password)){
                if ($username !== $this->getUser() || $password !== $this->getPassword()) {
                    // Provided username or password does not match, throw an exception
                    // Alternatively, the login prompt can be displayed once more
                    throw new HttpException(Response::HTTP_UNAUTHORIZED);
                }
            }
            
            return $next($request);
        }
    }

    protected function getEnabledEnvironments():array{
        return explode(',',config('httpauth.environments'));
     }

     
    protected function getUser():string{
       return config('httpauth.username');
    }

    protected function getPassword():string{
        return config('httpauth.password');
    }

    protected function isWhiteListed():bool
    {
        $ips = config('httpauth.whitelist',[]);
        return array_search(request()->server('REMOTE_ADDR'), $ips) !== false;
    }

    protected function isAuthenticated($password):bool
    {
        $username = request()->server('PHP_AUTH_USER');
        if ($username === null) {
            return false;
        }

        return $password === request()->server('PHP_AUTH_PW');
    }
}
