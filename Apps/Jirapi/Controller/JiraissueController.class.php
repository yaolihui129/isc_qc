<?php

namespace Jirapi\Controller;
class JiraissueController extends BasicController
{
    public function story()
    {
        switch ($this->_method) {
            case 'get': // get请求处理代码
                $this->story_get_list();
                break;
            case 'put': // put请求处理代码
                $this->story_put_list();
                break;
            case 'post': // post请求处理代码
                $this->story_post_fun();
                break;
        }
    }

    function story_get_list()
    {
        $where = array('pkey' => $_GET['projectKey']);
        $where = array('PROJECT' => getId('project', $where));
        $where['issuetype'] = array('in', '10005,10006');
        $where['issuestatus'] = array('not in', '1,3,10000,10002');
        $data = M('jiraissue')->where($where)->select();
        $this->response($data, 'json');
    }

    function story_put_list()
    {
        $data = array('A', $_GET);
        $this->response($data, 'json');
    }

    function story_post_fun()
    {
        $where = array('pkey' => $_GET['projectKey']);
        $where = array('PROJECT' => getId('project', $where));
        $where['issuetype'] = array('in', '10005,10006');
//        $where=array_merge($where,getJsonToArray($_POST));
        $where = array_merge($where, getJsonToArray());
        $data = M('jiraissue')->where($where)->select();

        $this->ajaxReturn($data);
    }

    public function plan()
    {
        switch ($this->_method) {
            case 'get': // get请求处理代码
                $this->plan_get_list();
                break;
            case 'put': // put请求处理代码
                $this->plan_put_list();
                break;
            case 'post': // post请求处理代码
                $this->plan_post_fun();
                break;
        }
    }

    function plan_get_list()
    {
        $where = array('pkey' => $_GET['projectKey']);
        $where = array('PROJECT' => getId('project', $where));
        $where['issuetype'] = '10102';
//        $where['issuestatus']=array('not in','1,3,10000,10002');
        $data = M('jiraissue')->where($where)->select();
        $this->response($data, 'json');
    }

    function plan_put_list()
    {
        $data = array('A', $_GET);
        $this->response($data, 'json');
    }

    function plan_post_fun()
    {
        $where = array('pkey' => $_GET['projectKey']);
        $where = array('PROJECT' => getId('project', $where));
        $where['issuetype'] = '10102';
        $where = array_merge($where, getJsonToArray($_POST));
        $data = M('jiraissue')->where($where)->select();

        $this->ajaxReturn($data);
    }


}