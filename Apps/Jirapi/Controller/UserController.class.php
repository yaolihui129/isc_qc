<?php

namespace Jirapi\Controller;
class UserController extends BasicController
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
        $m = M('ao_2d3bea_user_index');
        if ($_GET) {
            $where = array('USER_KEY' => $_GET['USER_KEY']);
            $data = $m->where($where)->find();
        } else {
            $data = array('暂不提供该功能');
//            $data=$m->select();
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
        $this->ajaxReturn($data);
    }

}