<?php

namespace Jirapi\Controller;
class ProjectController extends BasicController
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
        $m = M('project');
        if ($_GET) {
            $data = $m->find($_GET['id']);
        } else {
            $data = $m->select();
        }
        $this->ajaxReturn($data);
    }

    function put_list()
    {
        $data = array('暂不提供该功能');
        $this->response($data, 'json');
    }

    function post_fun()
    {
        $data = array('暂不提供该功能');
//        $data=array_merge($_POST,$_GET);
        $this->ajaxReturn($data);
    }

}