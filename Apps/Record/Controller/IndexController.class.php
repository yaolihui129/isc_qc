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
        $where = array('type' => 'waterfall', 'QD' => $_SESSION['account'], 'deleted' => '0');
        $mine=$m->where($where)->count();
        $this->assign('mine', $mine);

        $this->display();
    }
    //我负责
    public function mine(){

        $where['type'] = 'waterfall';
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
        $map['type'] = 'waterfall';
        $status = array('done', 'cancel','closed');
        $map['QD']  = array('neq','admin');
        $map['status'] = array('in', $status);
        $map['name|code'] = array('like', '%' . $search . '%');
        $map['deleted'] = '0';
        $m=M('project');
        $arr = $m->where($map)->order("status desc,end desc")->select();
        $this->assign('arr', $arr);

        //我负责的迭代数量
        $where = array('type' => 'waterfall', 'QD' => $_SESSION['account'], 'deleted' => '0');
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

    public function assigne()
    {
        $where['type'] = 'waterfall';
        $where['deleted'] = '0';
        $where['status'] = 'wait';
        $data = M('project')->where($where)->order("end desc,id")->select();
        $this->assign('data', $data);

        $this->display();
    }

    //抽签
    function draw()
    {
        //1.先生成一个随机数
        $draw = C(DRAW);
        $key = rand(0, sizeof($draw) - 1);
        $draw = $draw[$key];
        //2.先查库，如果有值直接返回“您已经完成抽签”
        $project = I('project');
        $where = array('project' => $project, 'deleted' => '0');
        $m = M('tp_project_assigne');
        $data = $m->where($where)->select();
        $arr = '';
        $user = '';
        $keys = '';
        foreach ($data as $d) {
            $arr[] = $d['draw'];
            $user[] = $d['name'];
            $keys[] = $d['key'];
        }
        $name = I("name");
        if (in_array($name, $user)) {//判断此人是否已经抽过
            $this->error("您已经完成此项目的抽签！");
        } else {
            if (in_array("$draw", $arr)) {
                $this->error("你抽中的是" . $draw . ',签码重复请重抽');
            } else {
                $_GET['project'] = $project;
                $_GET['name'] = $name;
                $_GET['k'] = $key;
                $_GET['draw'] = $draw;
                $_GET['table'] = 'tp_project_assigne';
                $this->insert();
                if ($draw == 'JOKER') { //直接更新迭代负责人
                    $_GET = array();
                    $_GET['id'] = $project;
                    $_GET['QD'] = $name;
                    $_GET['table'] = 'project';
                    $this->update();
                } else {
                    $data = $m->where($where)->order('k desc')->select();
                    if (sizeof($data) >= (sizeof(C(QC_TESTER)) - 1)) {
                        $_GET = array();
                        $_GET['id'] = $project;
                        $_GET['QD'] = $data[0]['name'];
                        $_GET['table'] = 'project';
                        $this->update();
                    }
                }
            }

        }
    }

    //放弃
    function renounce()
    {
        $name = I('name');
        if (countMyProject($name) < 3) {
            $this->error("你负责的迭代小于3个，不能允许被放弃！");
        } else {
            $project = I('project');
            $where = array('project' => $project, 'renounce' => '0', 'deleted' => '0');
            $where['name'] = array('neq', $name);
            $m = M('tp_project_assigne');
            $data = $m->where($where)->order('k desc')->select();

            if ($data) {//有其他人可以转移
                //更改项目负责人
                $_GET = array();
                $_GET['id'] = $project;
                $_GET['QD'] = $data[0]['name'];
                $_GET['table'] = 'project';
                $this->update();
                $arr = $m->where(array('name' => $name, 'project' => $project, 'renounce' => '0', 'deleted' => '0'))->find();
                //todo更改签码标识
                $_GET = array();
                $_GET['id'] = $arr['id'];
                $_GET['renounce'] = '1';
                $_GET['table'] = 'tp_project_assigne';
                $this->update();

            } else {
                $this->error("没有其他人可承接，你不能放弃该迭代");
            }
        }

    }
}