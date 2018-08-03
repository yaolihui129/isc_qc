<?php

namespace Jirapi\Controller;
class TestrunbugController extends BasicController
{
    //初始化信息
    function init()
    {
        $data = array(
            'table' => 'ao_69e499_testrunbug',
            'where' => array('TEST_RUN_ID' => $_GET['run_id']),
            'map' => '',
            'order' => '',
            'field' => '',

        );
        return $data;
    }

}