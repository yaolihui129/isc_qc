<?php

namespace Jirapi\Controller;
class TestrunhistoryController extends BasicController
{
    //初始化信息
    function init()
    {
        $data = array(
            'table' => 'ao_69e499_testrunhistory',
            'where' => $_GET,
            'map' => '',
            'order' => '',
            'field' => '',

        );
        return $data;
    }


}