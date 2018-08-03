<?php

namespace Jirapi\Controller;
class TeststepController extends BasicController
{
    //初始化信息
    function init()
    {
        $data = array(
            'table' => 'ao_69e499_teststep',
            'where' => array('TC_ID' => $_GET['tc']),
            'map' => '',
            'order' => '',
            'field' => '',

        );
        return $data;
    }


}