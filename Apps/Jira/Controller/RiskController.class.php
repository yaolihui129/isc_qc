<?php

namespace Jira\Controller;
class RiskController extends CommonController
{
    public function index()
    {

        $pros = $this->project('', 20);
        $this->assign('pros', $pros);

        $_SESSION['proid'] = I('proid');
        $m = M("project");

        $arr = $m->find($_SESSION['proid']);
        $this->assign("arr", $arr);

        $m = D("tp_risk");
        $where = array("proid" => $_SESSION['proid']);
        $risks = $m->where($where)->select();
        $this->assign("risks", $risks);

        $count = $m->where($where)->count() + 1;
        $this->assign('c', $count);
        $this->assign("state", formselect("打开", "state", "rstate"));
        $this->assign("level", formselect("C", "level", "risklevel"));
        $this->assign("tamethod", PublicController::editor("amethod", "暂无方案"));


        $this->display();
    }

    public function mod()
    {
        $m = D("tp_risk");
        $where = array("proid" => $_SESSION['proid']);
        $data = $m->where($where)->select();

        $this->assign("data", $data);

        $risk = $m->find(I('id'));
        $this->assign("risk", $risk);
        $this->assign("level", formselect($risk['level'], "level", "risklevel"));
        $this->assign("state", formselect($risk['state'], "state", "rstate"));
        $this->assign("tamethod", PublicController::editor("amethod", $risk['amethod']));


        $this->display();
    }

}