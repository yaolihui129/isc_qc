<?php

namespace Jirapi\Controller;
class TestsuiteController extends BasicController
{
    //初始化信息
    function init()
    {
        $data = array(
            'table' => 'ao_69e499_testsuite',
            'where' => array('PROJECT_ID' => $_GET['project']),
            'map' => '',
            'order' => '',
            'field' => '',

        );
        return $data;
    }

}