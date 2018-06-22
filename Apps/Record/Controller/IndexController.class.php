<?php

namespace Record\Controller;
class IndexController extends CommonController
{
    //进行中迭代列表
    public function index()
    {
        $status = array('wait', 'doing','suspended');//只看这些
        $map['status'] = array('in', $status);
        $map['deleted'] = '0';
        $map['QD']  = array('neq','admin');
        $m=M('project');
        $arr = M('project')->where($map)->order("status desc,end desc")->select();
        $this->assign('arr', $arr);
        //进行中的迭代数量
        $count=$m->where($map)->count();
        $this->assign('count', $count);
        //我负责的迭代数量
        $where=array('testgp'=>'YX','QD'=>$_SESSION['account'],'deleted'=>'0');
        $mine=$m->where($where)->count();
        $this->assign('mine', $mine);

        $this->display();
    }
    //我负责
    public function mine(){

        $where['testgp'] = 'YX';
        $where['deleted']='0';
        $where['QD']=$_SESSION['account'];
        $m=M('project');
        $count=$m->where($where)->count();
        $this->assign('mine', $count);

        $search = I('search');
        $this->assign('search', $search);
        $where['name|code'] = array('like', '%' . $search . '%');
        $arr = $m->where($where)->field("id,name,code,begin,end,status,QD,order")->order("end desc,id")->select();
        $this->assign('arr', $arr);

        $status = array('wait', 'doing','suspended');//只看这些
        $map['status'] = array('in', $status);
        $map['deleted'] = '0';
        $map['QD']  = array('neq','admin');
        //进行中的迭代数量
        $count=$m->where($map)->count();
        $this->assign('count', $count);

        $this->theme('')->display();
    }
    //历史迭代
    public function history(){
        $search = I('search');
        $this->assign('search', $search);
        $map['testgp'] = 'YX';
        $status = array('done', 'cancel','closed');
        $map['QD']  = array('neq','admin');
        $map['status'] = array('in', $status);
        $map['name|code'] = array('like', '%' . $search . '%');
        $map['deleted'] = '0';
        $m=M('project');
        $arr = $m->where($map)->order("status desc,end desc")->select();
        $this->assign('arr', $arr);

        //我负责的迭代数量
        $where=array('testgp'=>'YX','QD'=>$_SESSION['account'],'deleted'=>'0');
        $mine=$m->where($where)->count();
        $this->assign('mine', $mine);

        $status = array('wait', 'doing','suspended');//只看这些
        $var['QD']  = array('neq','admin');
        $var['status'] = array('in', $status);
        $var['deleted'] = '0';
        //进行中的迭代数量
        $count=$m->where($var)->count();
        $this->assign('count', $count);


        $this->display();
    }



}