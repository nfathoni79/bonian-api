<?php
/**
 * Created by PhpStorm.
 * User: ridwan
 * Date: 13/02/2019
 * Time: 11:44
 */

namespace App\Auth;

use Cake\Auth\BaseAuthorize;
use Cake\Http\ServerRequest;

/**
 * Class AjaxAuthorize
 * @package App\Auth
 */
class AjaxAuthorize extends BaseAuthorize
{

    /**
     * @param array|\ArrayAccess $user
     * @param ServerRequest $request
     * @return bool
     */
    public function authorize($user, ServerRequest $request)
    {
        // Do things here.
        return !empty($user);
    }

}