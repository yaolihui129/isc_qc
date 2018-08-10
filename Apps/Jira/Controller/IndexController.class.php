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
        $pro = M('project')->find($_SESSION['project']);
        $_SESSION['pkey'] = $pro['pkey'];
        $_SESSION['pname'] = $pro['pname'];
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

    public function started()
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
        $map = array('deleted' => '0');
        $pending = M('tp_project_pending')->where($map)->select();
        if ($pending) {
            foreach ($pending as $pend) {
                $p[] = $pend['id'];
            }
            $where['ID'] = array('not in', $p);
        }

        $url = C(JIRAPI) . "/Jirapi/issue";
        $data = httpJsonPost($url, json_encode($where));
        $data = json_decode(trim($data, "\xEF\xBB\xBF"), true);
        $this->assign('data', $data);

        $project = $this->projectDict();
        $this->assign('project', $project);

        $this->display();
    }

    public function pending()
    {
        $this->isLogin();
        $map = array('status' => '0');
        $m = M('tp_project_pending');
        $data = $m->where($map)->order('ctime desc')->select();
        foreach ($data as $da) {
            $arr[] = $da['issueid'];
        }
        $where['ID'] = array('in', $arr);
        $where['issuestatus'] = array('in', '10002,10003,10004,10005');
        $url = C(JIRAPI) . "/Jirapi/issue";
        $var = httpJsonPost($url, json_encode($where));
        $var = json_decode(trim($var, "\xEF\xBB\xBF"), true);
        if ($var) {
            foreach ($var as $da) {
                $this->changeStatus($da['id'], $da['issuestatus']);
            }
            $data = $m->where($map)->order('ctime desc')->select();
        }
        $this->assign('data', $data);
        $this->display();
    }

    //加入分派
    function jion()
    {
        if ($_SESSION['user'] == 'ylh') {
            $id = I('issueid');
            $url = C(JIRAPI) . "/Jirapi/issue/" . $id;
            $data = httpGet($url);
            $data = json_decode(trim($data, "\xEF\xBB\xBF"), true);
            $_GET['table'] = 'tp_project_pending';
            $_GET['id'] = $id;
            $_GET['pkey'] = $_SESSION['pkey'] . '-' . $data['issuenum'];
            $_GET['issuetype'] = $data['issuetype'];
            $_GET['issuestatus'] = $data['issuestatus'];
            $_GET['pname'] = $data['summary'];
//dump($_GET);
            $this->insert();
        } else {
            $this->error('对不起，你没有权限！');
        }
    }

    function chehui()
    {
        $project = I('project');
        $where = array('project' => $project);
        $m = D('tp_project_assigne');
        $data = $m->where($where)->select();
        if ($data) {
            foreach ($data as $da) {
                $m->delete($da['id']);
            }
        }
        $_GET['id'] = $project;
        $_GET['table'] = 'tp_project_pending';
        $this->realdel();
    }
    //抽签
    function draw()
    {
        $this->isLogin();
        //1.先生成一个随机数
        if (in_array($_SESSION['user'], C(QA_TESTER))) {
            $project = I('project');
            $name = $_SESSION['user'];
            $draw = C(DRAW);
            $key = rand(0, sizeof($draw) - 1);
            $draw = $draw[$key];
            //2.先查库，如果有值直接返回“您已经完成抽签”
            $where = array('project' => $project, 'deleted' => '0');
            $m = M('tp_project_assigne');
            $data = $m->where($where)->select();
            foreach ($data as $d) {
                $arr[] = $d['draw'];
                $user[] = $d['name'];
            }
            if (in_array($name, $user)) {//判断此人是否已经抽过
                $this->error("您已经完成此项目的抽签！");
            } else {
                if (in_array("$draw", $arr)) {
                    $this->error("你抽中的是" . $draw . ',签码重复请重抽');
                } else {
                    $_GET = array();
                    $_GET['project'] = $project;
                    $_GET['name'] = $name;
                    $_GET['k'] = $key;
                    $_GET['draw'] = $draw;
                    $_GET['table'] = 'tp_project_assigne';
                    $this->insert();
                    if ($draw == 'JOKER') { //直接更新迭代负责人
                        $_GET = array();
                        $_GET['id'] = $project;
                        $_GET['draw'] = $name;
                        $_GET['table'] = 'tp_project_pending';
                        $this->update();
                    } else {
                        $data = $m->where($where)->order('k desc')->select();
                        if (sizeof($data) >= (sizeof(C(QA_TESTER)) - 1)) {
                            $_GET = array();
                            $_GET['id'] = $project;
                            $_GET['draw'] = $data[0]['name'];
                            $_GET['table'] = 'tp_project_pending';
                            $this->update();
                        }
                    }
                }
            }
        } else {
            $this->error('对不起，该功能只对车险测试人员开放！');
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
            //更改项目负责人
            $_GET = array();
            $_GET['id'] = $project;
            $_GET['draw'] = $data[0]['name'];
            $_GET['table'] = 'tp_project_pending';
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

    //更改分派任务状态
    function changeStatus($issues, $status)
    {
        $m = D('tp_project_pending');
        $where = array('issueid' => $issues);
        $data = $m->where($where)->find();
        $var['id'] = $data['id'];
        $var['issuestatus'] = $status;
        $var['status'] = '1';
        $arr = $m->save($var);
        return $arr;
    }

}