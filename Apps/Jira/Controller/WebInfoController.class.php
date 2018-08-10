<?php
namespace Jira\Controller;
use Think\Controller;
class WebInfoController extends Controller
{
    public function _initialize()
    {
        if (ismobile()) {//设置默认默认主题为 Amaze
            C('DEFAULT_V_LAYER', 'Amaze');
        }
    }

    function _empty()
    {
        $this->display('index');
    }

    function isLogin()
    {
        if (!isset($_SESSION['isLogin']) || $_SESSION['user'] == '') {
            $this->redirect('Jira/Login/index');
        }
    }

    function projectDict()
    {
        $data = M('project')->cache('cache_project')->select();
        $project = array();
        foreach ($data as $k => $v) {

            $project[$k]['key'] = $v['id'];
            $project[$k]['value'] = $v['pname'] . '(' . $v['pkey'] . ')';
        }
        return $project;
    }


    function select($data, $name, $value)
    {
        $html = '<select name="' . $name . '" class="form-control">';
        foreach ($data as $v) {
            $selected = ($v['key'] == $value) ? "selected" : "";
            $html .= '<option ' . $selected . ' value="' . $v['key'] . '">' . $v['value'] . '</option>';
        }
        $html .= '<select>';
        return $html;
    }

    public function order()
    {
        $num = 0;
        foreach ($_POST['sn'] as $id => $sn) {
            $num += D(I('table'))->save(array("id" => $id, "sn" => $sn));
        }
        if ($num) {
            $this->success("排序成功!");
        } else {
            $this->error("排序失败...");
        }
    }

    public function insert()
    {
        $m = D(I('table'));
        if (IS_GET) {
            $_GET['adder'] = $_SESSION['user'];
            $_GET['moder'] = $_SESSION['user'];
            $_GET['ctime'] = time();
            if (!$m->create($_GET)) {
                $this->error($m->getError());
            }
            if ($m->add($_GET)) {
                if ($_GET['url']) {
                    $this->success("成功", U($_GET['url']));
                } else {
                    $this->success("成功");
                }
            } else {
                $this->error("失败");
            }
        } else {
            $_POST['adder'] = $_SESSION['user'];
            $_POST['moder'] = $_SESSION['user'];
            $_POST['ctime'] = time();
            if (!$m->create()) {
                $this->error($m->getError());
            }
            if ($m->add()) {
                if ($_POST['url']) {
                    $this->success("成功", U($_POST['url']));
                } else {
                    $this->success("成功");
                }
            } else {
                $this->error("失败");
            }
        }
    }

    //修改
    public function update()
    {
        if (IS_GET) {
            $_GET['moder'] = $_SESSION['user'];
            if (D(I('table'))->save($_GET)) {
                if ($_GET['url']) {
                    $this->success("成功", U($_GET['url']));
                } else {
                    $this->success("成功");
                }
            } else {
                $this->error("失败！");
            }
        } else {
            $_POST['moder'] = $_SESSION['user'];
            if (D(I('table'))->save($_POST)) {
                if ($_POST['url']) {
                    $this->success("成功", U($_POST['url']));
                } else {
                    $this->success("成功");
                }
            } else {
                $this->error("失败！");
            }
        }
    }

    //逻辑删除
    public function del()
    {
        $_POST[id] = I('id');
        $_POST['moder'] = $_SESSION['user'];
        $_POST['deleted'] = 1;
        if (D(I('table'))->save($_POST)) {
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }

    //物理删除
    public function realdel()
    {
        $count = D(I('table'))->delete(I('id'));
        if ($count > 0) {
            $this->success('成功');
        } else {
            $this->error('失败');
        }
    }

    function projectList($where = array())
    {
        $arr = array('eolinker示例', '微信', '测试管理', 'Jira');
        $where['projectName'] = array('not in', $arr);
        $data = M('eo_project')->where($where)->select();
        return $data;
    }

    function projectID()
    {
        $project = $this->projectList();
        $pro = '';
        foreach ($project as $p) {
            $pro[] = $p['projectid'];
        }
        return $pro;
    }

}