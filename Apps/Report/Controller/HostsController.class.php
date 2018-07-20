<?php

namespace Report\Controller;
class HostsController extends WebInfoController
{
    public function index()
    {
        $where = array('type' => '0', 'deleted' => '0');
        $m = M('tp_hosts');
        $test = $m->where($where)->order('hosts')->select();
        $this->assign("test", $test);
        $where = array('type' => '1', 'deleted' => '0');
        $yufa = $m->where($where)->order('hosts')->select();
        $this->assign("yufa", $yufa);

        $this->display();
    }


}