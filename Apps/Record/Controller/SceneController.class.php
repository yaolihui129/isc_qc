<?php

namespace Record\Controller;
class SceneController extends CommonController
{
    //迭代场景
    public function index()
    {
        $_SESSION['proid'] = I('proid');
        if (I('copy')) {
            $_SESSION['copy'] = I('copy');
        }

        $m = D("tp_scene");
        $where = array("project" => $_SESSION['proid'], "deleted" => '0');
        $data = $m->where($where)->order('sn')->select();
        $this->assign("data", $data);

        $pros = $this->project('', 20);
        $this->assign('pros', $pros);

        if ($_SESSION['proid'] == $_SESSION['copy']) {

        } else {
            if ($_SESSION['copy']) {
                $this->assign('copy', '【当前迭代：' . getName('project', $_SESSION['copy']) . '】');
            }
        }
        $count = $m->where($where)->count() + 1;
        $this->assign('c', $count);
        //获取项目数据
        $project = $this->projectDict();
        //封装下拉列表
        $project = $this->select($project, 'project', $_SESSION['proid']);
        $this->assign("project", $project);

        $this->display();
    }

    //场景修改
    public function mod()
    {
        $m = D("tp_scene");
        $where = array("project" => $_SESSION['proid'], "deleted" => '0');
        $data = $m->where($where)->order('sn')->select();
        $this->assign("data", $data);

        $scene = $m->find(I('id'));
        $this->assign("scene", $scene);

        $this->display();
    }

    //场景功能点配置
    public function mod_func()
    {
        if (I('branch')) {
            $_SESSION['modTestFunc']['branch'] = I('branch');
        }
        $id = I('scene');
        $m = D("tp_scene");
        $arr = $m->find($id);
        $this->assign("arr", $arr);

        $map = array('scene' => $id, "deleted" => '0');
        $data = M('tp_scene_func')->where($map)->order('sn')->select();
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

        $rr['branch'] = $_SESSION['modTestFunc']['branch'];
        $rr['deleted'] = '0';
        $func = M('tp_func')->where($rr)->order('path')->select();
        $this->assign('func', $func);

        $this->display();
    }

    //场景功能点
    public function func()
    {
        $m = D("tp_scene");
        $where = array("project" => $_SESSION['proid'], "deleted" => '0');
        $data = $m->where($where)->order('sn')->select();
        $this->assign("data", $data);
        $id = I('id');
        $arr = $m->find($id);
        $this->assign("arr", $arr);

        $map = array('scene' => $id, "deleted" => '0');
        $func = M('tp_scene_func')->where($map)->order('sn')->select();
        $this->assign("func", $func);

        $this->display();
    }

    //场景分派
    public function assignment()
    {
        $where = array("project" => $_SESSION['proid'], "deleted" => '0');
        $data = M("tp_scene")->where($where)->order('sn')->select();
        $this->assign("data", $data);

        $where['owner'] = I('user', $_SESSION['account']);
        $this->assign("owner", $where['owner']);
        $myScene = M("tp_my_scene")->where($where)->order('sn desc')->select();
        $this->assign("myScene", $myScene);

        $users = ['yaolihui', 'fanqiao', 'wangchenzi', 'menghuihui', 'lixm', 'qinzx'];
        $this->assign("users", $users);

        $this->display();
    }

    //场景分派
    public function assignto()
    {
        $scene = I('scene');
        $flow = I('flow');
        $user = I('user');
        $project = $_SESSION['proid'];
        //查询SN值
        $where = array('owner' => $user, 'project' => $project, "deleted" => '0');
        $m = D('tp_my_scene');
        $c = $m->where($where)->count();
        //插入myScene数据，得到myScene的ID
        $_GET['sn'] = $c + 1;
        $_GET['scene'] = $scene;
        $_GET['flow'] = $flow;
        $_GET['owner'] = $user;
        $_GET['project'] = $project;
        $_GET['adder'] = $_SESSION['account'];
        $_GET['moder'] = $_SESSION['account'];
        $_GET['ctime'] = time();
        if (!$m->create($_GET)) {
            $this->error($m->getError());
        }
        $mySceneid = $m->add($_GET);
        //根据$scene，查询$sceneFunc
        $map = array('scene' => $scene, "deleted" => '0');
        $sceneFunc = M('tp_scene_func')->where($map)->select();
        //把查询到的$sceneFunc插入tp_my_scene_func
        $table = D('tp_my_scene_func');
        foreach ($sceneFunc as $sf) {
            $var['sn'] = $sf['sn'];
            $var['func'] = $sf['func'];
            $var['scene'] = $sf['scene'];
            $var['scenefunc'] = $sf['id'];
            $var['myscene'] = $mySceneid;
            $var['adder'] = $_SESSION['account'];
            $var['moder'] = $_SESSION['account'];
            $var['ctime'] = time();
            if (!$table->create($var)) {
                $this->error($table->getError());
            }
            $mySceneFuncid = $table->add($var);
        }
        if ($mySceneFuncid) {
            $this->success("分派成功");
        } else {
            $this->error("分派失败");
        }
    }

    //我的必测任务
    public function my_scene()
    {
        $map['testgp'] = 'YX';
        $map['deleted'] = '0';
        $map['status'] = array('in', array('wait', 'doing'));
        $arr = M('project')->where($map)->select();
        foreach ($arr as $k => $ar) {
            $arr[$k] = $ar['id'];
        }
        $where['project'] = array('in', $arr);
        $where['owner'] = $_SESSION['account'];
        $where['deleted'] = '0';

        $m = D("tp_my_scene");
        $data = $m->where($where)->order('project,ctime')->select();
        if ($data) {
            $project = array();
            foreach ($data as $k => $da) {
                if (!in_array($da['project'], $project)) {
                    $project[$k] = $da['project'];
                }
            }
            $this->assign("project", $project);
            $pro = I('project', $project[0]);
            $this->assign("pro", $pro);
            $where['project'] = $pro;
            $data = $m->where($where)->order('ctime desc')->select();
            $this->assign("data", $data);
        } else {
            $this->error('还没有给你分派必测点！');
        }

        $this->display();
    }

    //执行我的测试
    public function run_my_scene()
    {
        $where = array('project' => I('project'), 'owner' => $_SESSION['account'], 'deleted' => '0');
        $myScene = D("tp_my_scene")->where($where)->order('project,ctime desc')->select();
        $this->assign("myScene", $myScene);
        $scene = I('myScene');
        $this->assign("scene", $scene);
        $where = array('myscene' => $scene, 'deleted' => '0');
        $data = M('tp_my_scene_func')->where($where)->order('ctime')->select();
        $this->assign("data", $data);

        $this->display();
    }

    //添加必测点功能
    function addTestFunc()
    {
        $id = I('funcid');
        $arr = M("tp_func")->find($id);

        $scene = I('scene');
        $m = D("tp_scene_func");
        $where = array("scene" => $scene, "deleted" => '0');
        $c = $m->where($where)->count();
        if (!$m->create($_GET)) {
            $this->error($m->getError());
        }
        $_GET['func'] = $arr['id'];
        $_GET['sn'] = $c + 1;
        $_GET['scene'] = $scene;
        $_GET['path'] = $arr['path'];
        $_GET['module'] = $arr['module'];
        $_GET['branch'] = $arr['branch'];
        $_GET['adder'] = $_SESSION['account'];
        $_GET['moder'] = $_SESSION['account'];
        $_GET['ctime'] = time();
        if ($m->add($_GET)) {
            $this->success("添加成功！");
        } else {
            $this->error("添加失败");
        }
    }

    //场景复制
    function copy()
    {
        if ($_SESSION['copy']) {
            $id = I('scene');
            $m = D("tp_scene");
            $arr = $m->find($id);
            $_POST['sn'] = $m->where(array("project" => $_SESSION['proid'], "deleted" => '0'))->count() + 1;
            $_POST['level'] = $arr['level'];
            $_POST['swho'] = $arr['swho'];
            $_POST['swhen'] = $arr['swhen'];
            $_POST['scene'] = $arr['scene'];
            $_POST['status'] = '4';
            $_POST['project'] = $_SESSION['copy'];
            $_POST['flow'] = $arr['flow'];
            $_POST['sourceid'] = $id;
            $_POST['adder'] = $_SESSION['account'];
            $_POST['moder'] = $_SESSION['account'];
            $_POST['ctime'] = time();
            if (!$m->create()) {
                $this->error($m->getError());
            }
            $sceneid = $m->add();
            if ($sceneid) {
                //查询原场景的功能点
                $table = D('tp_scene_func');
                $map = array('scene' => $id, "deleted" => '0');
                $data = $table->where($map)->order('sn,id')->select();
                if (!$table->create($_GET)) {
                    $this->error($table->getError());
                }
                foreach ($data as $da) {
                    $map = array('scene' => $sceneid, "deleted" => '0');
                    $c = $table->where($map)->count();
                    $_GET['func'] = $da['func'];
                    $_GET['sn'] = $c + 1;
                    $_GET['scene'] = $sceneid;
                    $_GET['path'] = $da['path'];
                    $_GET['module'] = $da['module'];
                    $_GET['branch'] = $da['branch'];
                    $_GET['adder'] = $_SESSION['account'];
                    $_GET['moder'] = $_SESSION['account'];
                    $_GET['ctime'] = time();
                    $a[] = $table->add($_GET);
                }
                if ($a) {
                    $this->success("复制成功");
                } else {
                    if ($m->delete($sceneid)) {
                        $this->error("插入功能点失败，过程将回滚");
                    }
                }
            } else {
                $this->error("复制失败");
            }
        } else {
            $this->error("无当前迭代信息");
        }
    }

    //标记成功
    function pass()
    {
        $_GET['result'] = 1;
        $_GET['moder'] = $_SESSION['account'];
        $m = D('tp_my_scene_func');
        if ($m->save($_GET)) {
            //查询功能点数和成功的功能点数
            $myScene = I('myscene');
            $funNum = countId('tp_my_scene_func', 'myscene', $myScene);
            $where = array('myscene' => $myScene, 'deleted' => '0', 'result' => '1');
            $passNum = $m->where($where)->count();
            //成功数等于功能点数
            if ($funNum == $passNum) {
                //标记我的场景测试结果
                $_GET['id'] = $myScene;
                D('tp_my_scene')->save($_GET);
                //标记场景测试结果
                $_GET['id'] = I('sceneid');
                D('tp_scene')->save($_GET);
            }
            $this->success("OK！");
        } else {
            $this->error("标记失败！");
        }
    }

    //标记失败
    function fail()
    {
        $_POST['result'] = 2;
        $_POST['moder'] = $_SESSION['account'];
        if (D('tp_my_scene_func')->save($_POST)) {
            //更新我的场景
            $_GET['id'] = I('myscene');
            $_GET['result'] = 2;
            $_GET['moder'] = $_SESSION['account'];
            D('tp_my_scene')->save($_GET);
            //更新场景
            $_GET['id'] = I('scene');
            D('tp_scene')->save($_GET);
            //更新场景功能点
            $_GET['id'] = I('scenefunc');
            D('tp_scene_func')->save($_GET);
            //更新功能点
            $_GET['id'] = I('func');
            $_GET['result'] = '失败';
            D('tp_func')->save($_GET);
            $this->success("OK！");
        } else {
            $this->error("修改失败！");
        }
    }

    //标记阻塞
    function block()
    {
        $_GET['result'] = 3;
        $_GET['moder'] = $_SESSION['account'];
        $m = D('tp_my_scene_func');
        $arr = $m->find(I('id'));
        //更新我的功能点
        if ($m->save($_GET)) {
            //更新我的场景
            $_GET['id'] = $arr['myscene'];
            D('tp_my_scene')->save($_GET);
            //更新场景
            $_GET['id'] = $arr['scene'];
            D('tp_scene')->save($_GET);
            //更新场景功能点
            $_GET['id'] = $arr['scenefunc'];
            D('tp_scene_func')->save($_GET);
            //更新功能点
            $_GET['id'] = $arr['func'];
            $_GET['result'] = '阻塞';
            D('tp_func')->save($_GET);
            $this->success("OK！");
        } else {
            $this->error("修改失败！");
        }


    }

}