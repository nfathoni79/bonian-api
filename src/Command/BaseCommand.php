<?php
namespace App\Command;

use Cake\Console\Command;
use Cake\Core\Configure;

/**
 * Base
 *
 */
class BaseCommand extends Command
{

    /**
     * return void
     */
    public function initialize()
    {
        parent::initialize();
        Configure::write('debug', false);
    }

    public function __construct()
    {
        parent::__construct();
        Configure::write('debug', false);
    }

}
