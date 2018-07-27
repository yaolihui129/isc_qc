<?php

namespace Jirapi\Controller;
class IndexController extends BasicController
{
    public function index()
    {
        switch ($this->_method) {
            case 'get': // get请求处理代码
                $this->get_list();
                break;
            case 'put': // put请求处理代码
                $this->put_list();
                break;
            case 'post': // post请求处理代码
                $this->post_fun();
                break;
        }
    }

    function get_list()
    {
        $data = array('A', $_GET);
        $this->response($data, 'json');
    }

    function put_list()
    {
        $data = array('A', $_GET);
        $this->response($data, 'json');
    }

    function post_fun()
    {
        $data = array_merge($_POST, $_GET);
        $this->ajaxReturn($data);
    }


}