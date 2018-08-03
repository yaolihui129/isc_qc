<?php

namespace Jirapi\Controller;
class TestcycleController extends BasicController
{
    function init()
    {
        //初始化信息
        $data = array(
            'table' => 'ao_69e499_testcycle',
            'where' => array('TP_ID' => $_GET['tp']),
            'map' => '',
            'order' => '',
            'field' => '',

        );
        return $data;
    }

    public function plan()
    {
        switch ($this->_method) {
            case 'get': // get请求处理代码
                $this->get();
                break;
            case 'put': // put请求处理代码
                $this->put();
                break;
            case 'post': // post请求处理代码
                $this->post();
                break;
            case 'delete': // delete请求处理代码
                $this->delete();
                break;
            case 'head': // head请求处理代码
                $this->head();
                break;
        }
    }


}