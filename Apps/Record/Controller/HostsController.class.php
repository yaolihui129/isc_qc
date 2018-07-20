<?php

namespace Record\Controller;

class HostsController extends CommonController
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

        $type = array(
            array('key' => 0, 'value' => '测试'),
            array('key' => 1, 'value' => '预发'),
            array('key' => 2, 'value' => '线上'),
        );
        //封装下拉列表
        $type = $this->select($type, 'type', 0);
        $this->assign("type", $type);

        $this->display();
    }


}