<?php

namespace Jira\Controller;
class IndexController extends WebInfoController
{
    public function index()
    {
        $_SESSION['type']['index'] = I('type');
        if ($_SESSION['type']['index'] == 'done') {
            $where['issuestatus'] = array('in', '3,10002');
        } elseif ($_SESSION['type']['index'] == 'assigned') {
            $_SESSION['url'] = '/' . C(PRODUCT) . '/Index/assigned/project/' . $_SESSION['project'];
            $this->isLogin();
            $where['issuestatus'] = array('in', '1,10000,10015,10016,10017');
        } else {
            $where['issuestatus'] = array('not in', '1,3,10000,10002');
        }
        $_SESSION['project'] = I('project', '10006');
        $where['PROJECT'] = intval($_SESSION['project']);
        $where['issuetype'] = array('in', '10005,10006');

        $search = trim(I('search'));
        $_SESSION['search']['index'] = $search;
        $this->assign('search', $search);

        $where['SUMMARY|issuenum'] = array('like', '%' . $search . '%');
        $_SESSION['map']['index'] = $where;

        $url = C(JIRAPI) . "/Jirapi/issue";
        $data = httpJsonPost($url, json_encode($where));
        $data = json_decode(trim($data, "\xEF\xBB\xBF"), true);
        $this->assign('data', $data);

        $project = $this->projectDict();
        $this->assign('project', $project);

        $this->display();
    }

    public function assigned()
    {
        $this->isLogin();
        $_SESSION['type']['index'] = I('type');
        if ($_SESSION['type']['index'] == 'done') {
            $where['issuestatus'] = array('in', '3,10002');
        } elseif ($_SESSION['type']['index'] == 'assigned') {
            $_SESSION['url'] = '/' . C(PRODUCT) . '/Index/assigned/project/' . $_SESSION['project'];
            $this->isLogin();
            $where['issuestatus'] = array('in', '1,10000,10015,10016,10017');
        } else {
            $where['issuestatus'] = array('not in', '1,3,10000,10002');
        }
        $_SESSION['project'] = I('project', '10006');
        $where['PROJECT'] = intval($_SESSION['project']);
        $where['issuetype'] = array('in', '10005,10006');

        $search = trim(I('search'));
        $_SESSION['search']['index'] = $search;
        $this->assign('search', $search);

        $where['SUMMARY|issuenum'] = array('like', '%' . $search . '%');
        $_SESSION['map']['index'] = $where;

        $url = C(JIRAPI) . "/Jirapi/issue";
        $data = httpJsonPost($url, json_encode($where));
        $data = json_decode(trim($data, "\xEF\xBB\xBF"), true);
        $this->assign('data', $data);

        $project = $this->projectDict();
        $this->assign('project', $project);

        $this->display();
    }

    //抽签
    function draw()
    {
        $this->isLogin();
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
        $name = $_SESSION['user'];
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
                if ($draw == 'JOKER') { //创建测试计划
                    $_GET = array();
                    $_GET['id'] = $project;
                    $_GET['QD'] = $name;
                    //调用创建测试计划的接口

                } else {
                    $data = $m->where($where)->order('k desc')->select();
                    if (sizeof($data) >= (sizeof(C(QC_TESTER)) - 1)) {
                        $_GET = array();
                        $_GET['id'] = $project;
                        $_GET['QD'] = $data[0]['name'];
                        //调用创建测试计划的接口

                    }
                }
            }

        }
    }

    //放弃
    function renounce()
    {
        $name = $_SESSION['user'];
        $project = I('project');
        $where = array('project' => $project, 'renounce' => '0', 'deleted' => '0');
        $where['name'] = array('neq', $name);
        $m = M('tp_project_assigne');
        $data = $m->where($where)->order('k desc')->select();

        if ($data) {//有其他人可以转移
            //todo
            $_GET = array();
            $_GET['id'] = $project;
            $_GET['QD'] = $data[0]['name'];
            //调用接口更改测试计划负责人

            //todo
            //是否可以传入ID
            $arr = $m->where(array('name' => $name, 'project' => $project, 'renounce' => '0', 'deleted' => '0'))->find();

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