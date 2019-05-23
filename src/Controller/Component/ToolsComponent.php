<?php
namespace App\Controller\Component;

use Cake\Controller\Component;


/**
 * Tools component
 */
class ToolsComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    public function initialize(array $config)
    {

    }

    public function maskPhone($number)
    {
        if (!empty($number) && is_string($number) && ($len = strlen($number)) > 9) {
            return substr($number, 0, 6) .
                str_repeat('*', $len - (6 + 3)) .
                substr($number, -3);
        }

        return $number;
    }



}
