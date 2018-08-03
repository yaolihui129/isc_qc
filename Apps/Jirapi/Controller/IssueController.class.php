<?php

namespace Jirapi\Controller;
class IssueController extends BasicController
{
    //初始化信息
    function init()
    {
        $data = array(
            'table' => 'jiraissue',
            'where' => $_GET,
            'map' => '',
            'order' => $_GET['order'],
            'field' => 'id,issuenum,PROJECT,REPORTER,ASSIGNEE,SUMMARY,issuetype,issuestatus,RESOLUTIONDATE,DESCRIPTION',

        );
        return $data;
    }

    function put()
    {
        $data = getJsonToArray();
        if ($data['ID']) {
            //重新封装数组
            $var['ID'] = $data['ID'];
            if ($data['issuestatus']) {
                $var['issuestatus'] = $data['issuestatus'];
            }
            if ($data['SUMMARY']) {
                $var['SUMMARY'] = $data['SUMMARY'];
            }
            if ($data['DESCRIPTION']) {
                $var['DESCRIPTION'] = $data['DESCRIPTION'];
            }
            if ($data['ASSIGNEE']) {
                $var['ASSIGNEE'] = $data['ASSIGNEE'];
            }
            if ($data['PRIORITY']) {
                $var['PRIORITY'] = $data['PRIORITY'];
            }
            $var['UPDATED'] = date('Y-m-d H:i:s', time());
            //更新数据
            $data = $this->update($data, 'jiraissue');


        } else {
            $var = $this->query('jiraissue', "", "ID desc");
            //查询按issuenum排序,获取最大的issuenum
            $where = array('PROJECT' => $data['PROJECT']);
            $order = 'issuenum desc';
            $arr = $this->query('jiraissue', $where, $order);
            //封装插入的数组
            $data['ID'] = intval($var[0]['id']) + 1;
            $data['issuestatus'] = 1;
            $data['issuenum'] = $arr[0]['issuenum'] + 1;
            $data['PRIORITY'] = 2;
            $data['VOTES'] = 0;
            $data['WATCHES'] = 1;
            $data['CREATOR'] = $data['REPORTER'];
            $data['CREATED'] = date('Y-m-d H:i:s', time());
            $data['UPDATED'] = $data['CREATED'];
            $data['WORKFLOW_ID'] = intval($var[0]['workflow_id']) + 1;
            //写库操作
            $data = $this->insert($data, 'jiraissue');
        }
        $this->ajaxReturn($data);
    }

    function post()
    {
        $where = getJsonToArray();
        $data = M('jiraissue')->where($where)
            ->field('id,issuenum,PROJECT,REPORTER,ASSIGNEE,SUMMARY,issuetype,issuestatus,RESOLUTIONDATE,DESCRIPTION')
            ->select();
        $this->ajaxReturn($data);
    }


    public function issuenum()
    {
        switch ($this->_method) {
            case 'get': // get请求处理代码
                $this->issuenum_get_list();
                break;
            case 'put': // put请求处理代码
                $this->put();
                break;
            case 'post': // post请求处理代码
                $this->post_fun();
                break;
        }
    }

    function issuenum_get_list()
    {
        $var = $this->init();
        if ($_GET) {
            $arr = explode('-', $_GET['issuenum']);
            $where = array('pkey' => $arr[0]);
            $data = $this->find_one('project', $where);
            $where = array('issuenum' => $arr[1], 'PROJECT' => $data['id']);
            $data = $this->find_one($var['table'], $where);
        } else {
            $data = array('暂不提供该功能');
        }
        $this->ajaxReturn($data);
    }

}