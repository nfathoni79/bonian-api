<?php
namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use \Cake\ORM\Locator\TableLocator;
use App\Exception\InvalidTokenException;
use App\Exception\ExpiredTokenException;
use Cake\I18n\Time;
use App\Lib\Token\Authenticate;

/**
 * Authorization middleware
 */
class AuthorizationMiddleware
{

    /**
     * Invoke method.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Message\ResponseInterface $response The response.
     * @param callable $next Callback to invoke the next middleware.
     * @return \Psr\Http\Message\ResponseInterface A response
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        $params = $request->getAttribute('params');

        if (!array_key_exists('disableAuthorization', $params)) {
            /**
             * @var \App\Lib\Token\Authenticate $authorization
             */
            if ($authorization = $request->getAttribute('authorization')) {
                //check valid token
                $authTable = (new TableLocator())->get('CustomerAuthenticates');
                $find = $authTable->find()
                    ->where([
                        'token' => $authorization->getToken()
                    ])
                    ->first();

                if ($find) {
                    /**
                     * @var \Cake\I18n\FrozenTime $expired;
                     */

                    $find->set('modified', (Time::now())->format('Y-m-d H:i:s'));
                    $authTable->save($find);

                    $expired = $find->get('expired');
                    if ($expired->lt(Time::now())) {
                        throw new ExpiredTokenException();
                    }

                } else {
                    throw new InvalidTokenException();
                }

            } else {
                throw new InvalidTokenException();
            }
        }


        return $next($request, $response);
    }
}
