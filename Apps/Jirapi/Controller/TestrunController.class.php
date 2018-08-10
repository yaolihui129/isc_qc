<?php

namespace Jirapi\Controller;
class TestrunController extends BasicController
{
    //初始化信息
    function init()
    {
        $data = array(
            'table' => 'AO_69E499_TESTRUN',
            'where' => array('TEST_CYCLE_ID' => $_GET['cycle']),
            'map' => '',
            'order' => '',
            'field' => '',
        );
        return $data;
    }

    function post()
    {
        $var = $this->init();
        $where = getJsonToArray();
        $data = M($var['table'])->where($where)->select();
        $this->ajaxReturn($data);
    }

}