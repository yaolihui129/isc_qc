<?php

namespace Record\Controller;
class OvertimeController extends CommonController
{
    public function index()
    {
        $where['role'] = 'qa';
        $where['deleted'] = '0';
        $where['account'] = array('in', C(QA_TESTER));
        $data = M('user')->where($where)->select();
        $this->assign('data', $data);

        $this->display();
    }

    public function mine()
    {
        $m=M('tp_overtime');
        $where = array('userid' => $_SESSION['id']);
        $where['type']='1';
        $jiab= $m->where($where)->order('riqi desc')->limit(10)->select();
        $jiabNum= $m->where($where)->count();
        $this->assign('arr', $jiab);

        $jiabHour=$m->where($where)->sum('hourlong');

        $where['type']='2';
        $tiaox= $m->where($where)->order('riqi desc')->limit(10)->select();
        $tiaoxNum= $m->where($where)->count();
        $this->assign('tiaox', $tiaox);

        $tiaoxHour=$m->where($where)->sum('hourlong');
        $keyHour=$jiabHour-$tiaoxHour;
        $hour=array($jiabHour,$tiaoxHour,$keyHour,$jiabNum,$tiaoxNum);
        $this->assign('hour', $hour);


        $riqi = date("Y-m-d", time());
        $this->assign('riqi', $riqi);
        $begin = mktime(19, 00);//mktime(hour,minute,second,month,day,year)
        $begin = date('H:i', $begin);
        $this->assign('begin', $begin);
        $end = mktime(21, 00);
        $end = date('H:i', $end);
        $this->assign('end', $end);
        $shif = array(
            array('key' => 0, 'value' => '否'),
            array('key' => 1, 'value' => '是'),
        );
        //封装下拉列表
        $meals = $this->select($shif, 'meals', 1);
        $this->assign("meals", $meals);
        $taxi = $this->select($shif, 'taxi', 0);
        $this->assign("taxi", $taxi);

        $this->display();
    }

    public function xiangq()
    {
        $type=I('type');
        $this->assign('type', $type);

        $where = array('userid' => $_SESSION['id'],'type'=>I('type'));
        $data= M('tp_overtime')->where($where)->order('riqi desc')->select();
        $this->assign('data', $data);

        $this->display();
    }

    public function detailed()
    {
        $user = I('user');
        $this->assign('user', $user);
        $where = array('user' => $user);
        $where['riqi'] = array('gt', '2018-4-10');
        $data = M('tp_overtime')->where($where)->order('riqi desc')->select();
        $this->assign('data', $data);

        $this->display();
    }

    //添加记录
    function add(){
        //判断必填
        if(!I('hourlong')){//工时必填
            $this->error('时长不能为空');
        }elseif (!I('remark')){//备注必填
            $this->error('原因不能为空');
        }else{
            $m = D('tp_overtime');
            $_POST['adder'] = $_SESSION['account'];
            $_POST['moder'] = $_SESSION['account'];
            $_POST['ctime'] = time();
            if (!$m->create()) {
                $this->error($m->getError());
            }
            if ($m->add()) {
                $this->success("成功");
            } else {
                $this->error("失败");
            }
        }

    }

}