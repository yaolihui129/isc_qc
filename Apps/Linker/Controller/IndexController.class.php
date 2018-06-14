<?php

namespace Linker\Controller;

class IndexController extends PublicController
{
    public function index()
    {

        $search = I('search');
        $this->assign('search', $search);
        $where=array('deleted'=>'0');
        $where['projectName|projectVersion'] = array('like', '%' . $search . '%');
//        $data=chaxun('tp_device',$where,'id');
        $data=M('project')->where($where)->select();
        $this->assign('data', $data);
        dump($data);

        $this->display();
    }






}