<?php

namespace Report\Controller;
class RiskController extends WebInfoController
{
    public function index()
    {
        $where=array('deleted'=>'0');
        $data = M("tp_risk")->where($where)->order('ctime desc')->select();
        $this->assign("data", $data);

        $this->display();
    }



}