<?php

namespace Jira\Controller;
class ApiController extends WebInfoController
{
    public function index()
    {
        $search = I('search');
        $this->assign('search', $search);
        $where = array('removed' => '0');
        $where['apiName|apiURI'] = array('like', '%' . $search . '%');
        $where['apiName'] = array('neq', '示例接口');
        $project = $this->projectID();
        $where['projectID'] = array('in', $project);
        $data = M('eo_api')->where($where)->order('projectID')->select();
        $this->assign('data', $data);

        $this->display();
    }


}