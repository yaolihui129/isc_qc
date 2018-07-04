<?php

namespace Report\Controller;
class OvertimeController extends WebInfoController
{
    public function index()
    {
        $where['role']='qa';
        $where['deleted']='0';
        $where['account'] = array('in', C(QA_TESTER));
        $data=M('user')->where($where)->select();
        $this->assign('data', $data);


        $this->display();
    }

    public function xiangq()
    {
        $type=I('type');
        $this->assign('type', $type);
        $user = I('user');
        $this->assign('user', $user);

        $where = array('user' => $user, 'type' => $type);
        $data= M('tp_overtime')->where($where)->order('riqi desc')->select();
        $this->assign('data', $data);

        $this->display();
    }


    public function detailed(){
        $user = I('user');
        $this->assign('user', $user);
        $where = array('user' => $user);
        $where['riqi']  = array('gt','2018-4-10');
        $data = M('tp_overtime')->where($where)->order('riqi desc')->select();
        $this->assign('data', $data);

        $this->display();
    }


}