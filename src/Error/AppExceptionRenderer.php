<?php


namespace App\Error;
use Cake\Error\ExceptionRenderer;


class AppExceptionRenderer extends ExceptionRenderer
{

    protected function errorTemplate(\Exception $error)
    {
        return json_encode([
            'status' => 'ERROR',
            'code' => $error->getCode(),
            'message' => $error->getMessage()
        ]);
    }

    function InvalidToken(\Exception $error)
    {
        $response = $this->controller->response;
        return $response->withType('application/json')
            ->withStatus($error->getCode(), $error->getMessage())
            ->withStringBody($this->errorTemplate($error));
    }


    function MissingToken(\Exception $error)
    {
        $response = $this->controller->response;
        return $response->withType('application/json')
            ->withStatus($error->getCode(), $error->getMessage())
            ->withStringBody($this->errorTemplate($error));
    }


    function ExpiredToken(\Exception $error)
    {
        $response = $this->controller->response;
        return $response->withType('application/json')
            ->withStatus($error->getCode(), $error->getMessage())
            ->withStringBody($this->errorTemplate($error));
    }

    function MissingController(\Exception $error)
    {
        $response = $this->controller->response;
        return $response->withType('application/json')
            ->withStatus($error->getCode(), $error->getMessage())
            ->withStringBody($this->errorTemplate($error));
    }

    function MissingAction(\Exception $error)
    {
        $response = $this->controller->response;
        return $response->withType('application/json')
            ->withStatus($error->getCode(), $error->getMessage())
            ->withStringBody($this->errorTemplate($error));
    }


}