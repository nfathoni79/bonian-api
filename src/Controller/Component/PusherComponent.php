<?php
namespace App\Controller\Component;

use Cake\ORM\Locator\TableLocator;
use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Pusher\Pusher;
use Cake\Log\Log;
use Cake\Core\Configure;
/**

 * Pusher component
 */

class PusherComponent extends Component
{


    public function initialize(array $config)
    {
        $config = Configure::read('Pusher');
        $this->_appKey = $config['appKey'];
        $this->_appSecret = $config['appSecret'];
        $this->_appId = $config['appId'];
    }

    /**
     * @return Pusher|null
     */
    public function Pusher()
    {
        $options = array(
            'cluster' => 'ap1',
            'useTLS' => true
        );

        $pusher = null;

        try {
            $pusher =  new Pusher(
                $this->_appKey,
                $this->_appSecret,
                $this->_appId,
                $options
            );
        } catch(\Exception $e) {}

        return $pusher;
    }


    function trigger($channel, $event, $data){
        $options = array(
            'cluster' => 'ap1',
            'useTLS' => true
        );

        $pusher = new Pusher(
            $this->_appKey,
            $this->_appSecret,
            $this->_appId,
            $options
        );

        $pusher->trigger($channel, $event, $data);
    }

//    function contribution(){
//        $contribution = TableRegistry::get('MemberPanel.Contributions');
//        $toPusher = $contribution->find()
//            ->select([
//                'total' => 'SUM(Contributions.nvx + Contributions.nvx_bonus)',
//                'title' => 'Clients.name',
//                'created' => 'Contributions.created',
//            ])
//            ->contain(['Clients'])
//            ->group(['Contributions.id'])
//            ->order(['Contributions.id' => 'DESC'])
//            ->limit('5')
//            ->all()
//            ->toArray();
//
//        $data = [];
//
//        foreach($toPusher as $k => $val){
//            $data[$k]['title'] = $val['title'];
//            $data[$k]['total'] = $val['total'];
//            $time = new Time($val['created']);
//            $data[$k]['time'] = $time->timeAgoInWords([$time]);
//        }
//        return $data;
//    }
}