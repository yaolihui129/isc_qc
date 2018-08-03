<?php

namespace Jirapi\Controller;
class TestsuitememberController extends BasicController
{
    //初始化信息
    function init()
    {
        $data = array(
            'table' => 'ao_69e499_testsuitemember',
            'where' => array('SEQ_BALANCER' => $_GET['seq']),
            'map' => '',
            'order' => 'SEQUENCE',
            'field' => '',

        );
        return $data;
    }

}