<?php
/**
 * Created by PhpStorm.
 * User: ridwan
 * Date: 13/02/2019
 * Time: 12:01
 */

namespace App\Auth;

use Cake\Auth\BaseAuthenticate;
use Cake\Http\ServerRequest;
use Cake\Http\Response;
use Cake\Controller\ComponentRegistry;
use Cake\Http\Exception\UnauthorizedException;

/**
 * Class AjaxAuthenticate
 * @package App\Auth
 */

class AjaxAuthenticate extends BaseAuthenticate
{

    /**
     * AjaxAuthenticate constructor.
     * @param ComponentRegistry $registry
     * @param $config
     */
    public function __construct(ComponentRegistry $registry, $config)
    {
        parent::__construct($registry, $config);
    }

    /**
     * @param ServerRequest $request
     * @param array $fields
     * @return bool
     */
    protected function _checkFields(ServerRequest $request, array $fields)
    {

        foreach ([$fields['username'], $fields['password']] as $field) {
            $value = $request->getData($field);

            if (empty($value) || !is_string($value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param ServerRequest $request
     * @param Response $response
     * @return mixed
     */
    public function authenticate(ServerRequest $request, Response $response)
    {
        $fields = $this->_config['fields'];
        if (!$this->_checkFields($request, $fields)) {
            return false;
        }

        return $this->_findUser(
            $request->getData($fields['username']),
            $request->getData($fields['password'])
        );
    }

    /**
     * @param ServerRequest $request
     * @param Response $response
     * @return Response
     */
    public function unauthenticated(ServerRequest $request, Response $response)
    {
        //$Exception = new UnauthorizedException();
        //throw $Exception;
        return $response->withStatus(403, 'Unauthorized')
            ->withAddedHeader('content-type', 'application/json')
            ->withStringBody(json_encode(['status' => 'ERROR', 'code' => 403, 'message' => 'Unauthorized', 'result' => []]));
    }

}