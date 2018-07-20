<?php

namespace Report\Controller;
class IndexController extends WebInfoController
{
    public function index()
    {
        $where['testgp'] = 'YX';
        $where['deleted']='0';
        $where['QD']  = array('neq','admin');
        $status = array('doing', 'suspended');//只看这些
        $where['status'] = array('in', $status);
        $data = M('project')->where($where)->order("end desc,id")->select();
        $this->assign('data', $data);

        //检查逾期未归还的设备
        $this->overdue();
        //检查逾期未确认的BUG
        $this->bug_unconfirmed();
        //检查逾期未复测的BUG
        $this->bug_noregress();

        $this->display();
    }

    public function owner(){

        $m=M('project');
        $where['testgp'] = 'YX';
        $where['deleted']='0';
        $where['QD']  = array('neq','admin');
        $status = array('wait', 'doing','suspended');
        $where['status'] = array('in', $status);
        $data = $m->where($where)->field("id,name,code,begin,end,status,QD,order")->order("end desc,id")->select();
        $user=array();
        foreach ($data as $k=>$da){
            if(in_array($da['qd'],$user)){

            }else{
                $user[$k]=$da['qd'];
            }
        }
        $this->assign('user', $user);
        $where['QD']=I('QD',$user[0]);
        $this->assign('owner', $where['QD']);
        $data = $m->where($where)->field("id,name,code,begin,end,status,QD,order")->order("end desc,id")->select();
        $this->assign('data', $data);


        $this->display();
    }

    public function history(){
        $where['testgp'] = 'YX';
        $where['deleted']='0';
        $where['QD']  = array('neq','admin');
        $status = array('done', 'cancel','closed');
        $where['status'] = array('in', $status);
        $data = M('project')->where($where)->order("end desc,id")->select();
        $this->assign('data', $data);

        $this->display();
    }

    public function risk()
    {
        $_SESSION['proid'] = I('proid');
        $where = array("proid" => I('proid'));
        $data = M("tp_risk")->where($where)->select();
        $this->assign("data", $data);

        $this->display();
    }

    public function rules()
    {
        $_SESSION['proid'] = I('proid');
        $where = array("zt_projectstory.project" => $_SESSION['proid'], 'zt_story.deleted' => '0');
        $data = M('story')->join('zt_projectstory ON zt_projectstory.story =zt_story.id')->where($where)
            ->field('
                        zt_story.id as id,
                        zt_story.branch as branch,
                        zt_story.module as module,
                        zt_story.title as title,
                        zt_story.version as version
                ')
            ->order('zt_projectstory.order')->select();

        $this->assign("data", $data);


        $this->display();
    }

    public function test_case()
    {
        $_SESSION['proid'] = I('proid');
        $where = array('zt_projectstory.project' => $_SESSION['proid']);
        $data = M('projectstory')->where($where)->join('zt_story ON zt_story.id =zt_projectstory.story')->select();
        $this->assign('data', $data);

        $this->display();
    }

    public function smoke()
    {
        $_SESSION['proid'] = I('proid');
        $where = array('zt_projectstory.project' => $_SESSION['proid']);
        $data = M('projectstory')->where($where)->join('zt_story ON zt_story.id =zt_projectstory.story')->select();
        $this->assign('data', $data);

        $this->display();

    }

    public function bug()
    {
        $_SESSION['proid'] = I('proid');
        $where = array("project" => I('proid'), "deleted" => '0');
        $m = M("bug");
        $data = $m->where($where)->order('status,id')->select();
        $this->assign('data', $data);

        $var = $m->where($where)->field("openedBy,count(id)")->group('openedBy')->order('count(id) desc')->select();
        $this->assign('var', $var);
        $var = $m->where($where)->field("resolvedBy,count(id)")->group('resolvedBy')->order('count(id) desc')->select();
        $this->assign('var1', $var);
        $var = $m->where($where)->field("closedBy,count(id)")->group('closedBy')->order('count(id) desc')->select();
        $this->assign('var2', $var);
        $var = $m->where($where)->field("module,count(id)")->group('module')->order('count(id) desc')->select();
        $this->assign('var3', $var);
        $var = $m->where($where)->field("assignedTo,count(id)")->group('assignedTo')->order('count(id) desc')->select();
        $this->assign('var4', $var);


        $this->display();
    }

    public function task()
    {
        $_SESSION['proid'] = I('proid');
        $where['project'] = $_SESSION['proid'];
        $where['deleted'] = '0';
        $data = M('task')->where($where)->field('id,name,deadline,status,estimate,consumed,left')->select();
        $this->assign('data', $data);

        $this->display();
    }

}