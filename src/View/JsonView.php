<?php

namespace App\View;

use Cake\Core\Exception\Exception;
use Cake\View\View;

/**
 * Json View
 *
 * Default view class for rendering API response
 */
class JsonView extends View
{

    /**
     * Renders api response
     *
     * @param string|null $view Name of view file to use
     * @param string|null $layout Layout to use.
     * @return string|null Rendered content or null if content already rendered and returned earlier.
     * @throws Exception If there is an error in the view.
     */
    public function render($view = null, $layout = null)
    {


        $this->response = $this->response->withType('json')
                ->withAddedHeader('Access-Control-Allow-Headers', 'Content-Type, api_key, Authorization')
                ->withAddedHeader('Access-Control-Allow-Methods', 'GET, POST, DELETE, PUT, OPTIONS')
                ->withAddedHeader('Access-Control-Allow-Origin', '*');

        $this->layout = "ajax";

        $message = null;

        $content = [
            'status' => 'OK'
        ];

        $code = $this->response->getStatusCode();
        $content['code'] = (int) $code;


        if ($code != 200) {
            $content['status'] = "ERROR";
            $message = $this->response->getReasonPhrase();
        }

        if ($message) {
            $content['message'] = $message;
        }


        //check request is paging
        if ($paging = $this->request->getParam('paging')) {
            $paging = array_values($paging)[0];
            $content['paging'] = $paging;
        }

        $content['result'] = $this->viewVars;

        $this->Blocks->set('content', $this->renderLayout(json_encode($content), $this->layout));

        $this->hasRendered = true;

        return $this->Blocks->get('content');
    }
}
