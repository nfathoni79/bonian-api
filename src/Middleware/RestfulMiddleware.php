<?php
namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Lib\Token\Authenticate;

/**
 * Restful middleware
 */
class RestfulMiddleware
{

    /**
     * Invoke method.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Message\ResponseInterface $response The response.
     * @param callable $next Callback to invoke the next middleware.
     * @return \Psr\Http\Message\ResponseInterface A response
     * @throws \Exception
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        $params = $request->getAttribute('params');
        if (!array_key_exists('disableAuthorization', $params)) {
            new Authenticate($request);
        }

        return $next($request, $response);

    }
}
