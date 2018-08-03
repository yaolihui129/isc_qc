<?php

namespace Jirapi\Controller;
class TestrunController extends BasicController
{
    //初始化信息
    function init()
    {
        $data = array(
            'table' => 'ao_69e499_testrun',
            'where' => array('TEST_CYCLE_ID' => $_GET['cycle']),
            'map' => '',
            'order' => '',
            'field' => '',

        );
        return $data;
    }

}