<?php

namespace Linker\Controller;

class IndexController extends PublicController
{
    public function index()
    {

        $search = I('search');
        $this->assign('search', $search);
        $where = array('removed' => '0');
        $where['apiName|apiURI'] = array('like', '%' . $search . '%');
//        $data=chaxun('tp_device',$where,'id');
        $data = M('api')->where($where)->select();
        $this->assign('data', $data);

        $this->display();
    }






}