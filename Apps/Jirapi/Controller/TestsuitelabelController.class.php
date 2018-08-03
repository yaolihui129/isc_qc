<?php

namespace Jirapi\Controller;
class TestsuitelabelController extends BasicController
{
    //初始化信息
    function init()
    {
        $data = array(
            'table' => 'ao_69e499_testsuitelabel',
            'where' => array('TEST_SUITE_ID' => $_GET['suite']),
            'map' => '',
            'order' => '',
            'field' => '',
        );
        return $data;
    }


}