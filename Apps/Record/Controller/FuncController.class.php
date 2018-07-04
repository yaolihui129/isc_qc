<?php

namespace Record\Controller;

class FuncController extends CommonController
{
    //模块列表
    public function module()
    {
        //平台信息
        $where['product'] = 6;
        $where['deleted'] = '0';

        $branch = array(32,38,37,39,53,54,55,56,57,58,59,60);
        $where['id'] = array('in', $branch);
        $data = M('branch')->where($where)->order('`order`')->select();
        $this->assign("data", $data);

        //模块信息
        $id = I('id', $data[0][id]);
        $this->assign("id", $id);
        $map = array('branch' => $id,'type'=>'story', 'deleted' => '0');
        $module = M('module')->where($map)->order('path,`order`')->select();
        $this->assign("module", $module);

        $this->display();
    }

    //功能列表
    public function index()
    {
        $m = D('module');
        $arr = $m->find(I('id'));
        $this->assign("arr", $arr);

        $map = array('parent' => $arr['id'], 'type' => 'story', 'deleted' => "0");
        $subModule = $m->where($map)->select();
        $this->assign("sub", $subModule);

        $where = array('branch' => $arr['branch'], 'type' => 'story', 'deleted' => "0");
        $data = $m->where($where)->order('path,`order`')->select();
        $this->assign("data", $data);
        /* 实例化模型*/
        $m = D("tp_func");
        $map['module'] = I('id');
        $map['deleted'] = '0';
        $func = $m->where($map)->order("sn")->select();
        $this->assign("func", $func);


        /* 添加*/
        $count = $m->where($map)->count() + 1;
        $this->assign("c", $count);
        //获取项目数据
        $project = $this->projectDict();
        //封装下拉列表
        $project = $this->select($project, 'project', 1);
        $this->assign("project", $project);
        $this->display();
    }

    //修改功能
    public function mod()
    {
        $m = D("tp_func");
        $func = $m->find(I('id'));
        $this->assign("func", $func);

        $where = array('module' => $func['module'], 'deleted' => '0');
        $data = $m->where($where)->order("sn")->select();
        $this->assign("data", $data);


        $a = $this->projectDict(50);
        $project = $this->select($a, 'project', $func['project']);
        $this->assign("project", $project);


        $this->display();
    }

    //迭代功能点
    public function func()
    {
        $_SESSION['proid'] = I('proid');
        $m = M("tp_func_range");
        $where = array("project" => $_SESSION['proid'], "deleted" => '0', 'type' => '1');
        $data = $m->where($where)->order('sn')->select();
        $this->assign("data", $data);


        $pros = $this->project('', 20);
        $this->assign('pros', $pros);

        $this->display();
    }

    //修改迭代功能点
    public function mod_func()
    {
        if (I('branch')) {
            $_SESSION['modFunc']['branch'] = I('branch');
        }
        $where = array("project" => $_SESSION['proid'], "deleted" => '0', 'type' => '1');
        $data = M("tp_func_range")->where($where)->order('sn')->select();
        $this->assign("data", $data);

        $map = array("project" => $_SESSION['proid']);
        $branch = M("projectproduct")->where($map)->select();
        if (!$branch[0]['branch'] == '0') {//不为0
            foreach ($branch as $k => $br) {
                $branch[$k] = $br['branch'];
            }
        } else {//为0
            $var = array('product' => $branch[0]['product'], 'deleted' => '0');
            $branch = M('branch')->where($var)->order('`order`')->select();
            foreach ($branch as $k => $br) {
                $branch[$k] = $br['id'];
            }
        }

        $this->assign('branch', $branch);

        foreach ($data as $da) {
            $a[] = $da['func'];
        }
        if ($a) {
            $rr['id'] = array('not in', $a);
        }
        $rr['branch'] = $_SESSION['modFunc']['branch'];
        $rr['deleted'] = '0';
        $func = M('tp_func')->where($rr)->select();
        $this->assign('func', $func);


        $this->display();
    }

    //单个添加功能点（或影响）
    function add()
    {
        $id = I('funcid');
        $arr = M("tp_func")->find($id);
        $project = $_SESSION['proid'];
        $m = D("tp_func_range");
        $where = array("project" => $project, "deleted" => '0');
        $c = $m->where($where)->count();
        if (!$m->create($_GET)) {
            $this->error($m->getError());
        }
        $_GET['func'] = $id;
        $_GET['sn'] = $c + 1;
        $_GET['type'] = I('type');
        $_GET['path'] = $arr['path'];
        $_GET['module'] = $arr['module'];
        $_GET['branch'] = $arr['branch'];
        $_GET['project'] = $project;
        $_GET['adder'] = $_SESSION['account'];
        $_GET['moder'] = $_SESSION['account'];
        $_GET['ctime'] = time();
        if ($m->add($_GET)) {
            $this->success("添加成功！");
        } else {
            $this->error("添加失败");
        }
    }

    //影响范围
    public function range()
    {//迭代测试（含影响范围）
        $_SESSION['proid'] = I('proid');
        $m = D("tp_func_range");
        $where = array("project" => $_SESSION['proid'], "deleted" => '0');
        $data = $m->where($where)->order('sn')->select();
        $this->assign("data", $data);


        $pros = $this->project('', 20);
        $this->assign('pros', $pros);

        $map = array("project" => $_SESSION['proid']);
        $branch = M("projectproduct")->where($map)->select();
        if (!$branch[0]['branch'] == '0') {//不为0
            foreach ($branch as $k => $br) {
                $branch[$k] = $br['branch'];
            }
        } else {//为0
            $var = array('product' => $branch[0]['product'], 'deleted' => '0');
            $branch = M('branch')->where($var)->select();
            foreach ($branch as $k => $br) {
                $branch[$k] = $br['id'];
            }
        }
        $this->assign('branch', $branch);
        $rr = array('branch' => $_SESSION['modFunc']['branch'], "deleted" => '0');
        $func = M('tp_func')->where($rr)->select();
        $this->assign('func', $func);

        $this->display();
    }

    //修改影响范围
    public function mod_range()
    {
        if (I('branch')) {
            $_SESSION['modFunc']['branch'] = I('branch');
        }
        $m = D("tp_func_range");
        $where = array("project" => $_SESSION['proid'], "deleted" => '0');
        $data = $m->where($where)->order('sn')->select();
        $this->assign("data", $data);

        $map = array("project" => $_SESSION['proid']);
        $branch = M("projectproduct")->where($map)->select();
        if (!$branch[0]['branch'] == '0') {//不为0
            foreach ($branch as $k => $br) {
                $branch[$k] = $br['branch'];
            }
        } else {//为0
            $var = array('product' => $branch[0]['product'], 'deleted' => '0');
            $branch = M('branch')->where($var)->select();
            foreach ($branch as $k => $br) {
                $branch[$k] = $br['id'];
            }
        }

        $this->assign('branch', $branch);

        foreach ($data as $da) {
            $a[] = $da['func'];
        }
        if ($a) {
            $rr['id'] = array('not in', $a);
        }
        $rr['branch'] = $_SESSION['modFunc']['branch'];
        $rr['deleted'] = '0';
        $func = M('tp_func')->where($rr)->select();
        $this->assign('func', $func);


        $this->display();
    }

    //拉取当前迭代的功能点
    function getFunc()
    {
        $project = $_SESSION['proid'];
        $where = array("project" => $project, "deleted" => '0');
        $data = M("tp_func")->where($where)->order('sn')->select();
        if ($data) {
            $m = D("tp_func_range");
            $m->create($_GET);
            $_GET['adder'] = $_SESSION['account'];
            $_GET['moder'] = $_SESSION['account'];
            foreach ($data as $da) {
                $c = $m->where($where)->count();
                $_GET['sn'] = $c + 1;
                $_GET['func'] = $da['id'];
                $_GET['type'] = 1;
                $_GET['path'] = $da['path'];
                $_GET['module'] = $da['module'];
                $_GET['branch'] = $da['branch'];
                $_GET['project'] = $project;
                $_GET['ctime'] = time();
                $m->add($_GET);
            }
            $this->success("拉取功能点成功！");
        } else {
            $this->error("没有标记的功能点！");
        }

    }



}