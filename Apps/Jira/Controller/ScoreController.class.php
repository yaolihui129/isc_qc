<?php

namespace Jira\Controller;
class ScoreController extends WebInfoController
{
    public function index()
    {
        $saiJi = array();
        $where = array('deleted' => '0');
        $data = M('tp_my_score')->where($where)->order('ctime desc')->select();
        foreach ($data as $da) {
            if (!in_array($da['quarter'], $saiJi)) {
                $saiJi[] = $da['quarter'];
            }
        }
        $this->assign("saiJi", $saiJi);

        $quarter = I('quarter', $saiJi[0]);
        $this->assign("quarter", $quarter);

        $where = array('quarter' => $quarter, 'deleted' => '0');
        $user = array_diff(C(QA_TESTER), array('ylh'));

        $where['user'] = array('in', $user);
        $data = M('tp_score_list')->where($where)->order('score desc')->select();
        $this->assign("data", $data);

        $this->display();
    }

    public function mine()
    {
        $user = $_SESSION['user'];
        $this->assign("myScore", sumScore($user, C(KH_QUARTER)));

        $where = array('user' => $user, 'quarter' => C(KH_QUARTER), 'deleted' => '0');
        $data = M('tp_my_score')->where($where)->order('ctime desc')->limit(10)->select();
        $this->assign("data", $data);

        $this->display();
    }

    //规则
    public function rules()
    {
        $where = array('deleted' => '0');
        $data = M('tp_score_rules')->where($where)->order('cate desc')->select();
        $this->assign("data", $data);
        $type = array(
            array('key' => 1, 'value' => '加分项'),
            array('key' => 2, 'value' => '减分项'),
        );
        $type = $this->select($type, 'type');
        $this->assign("type", $type);

        $project = array(
            array('key' => 1, 'value' => '关联迭代'),
            array('key' => 0, 'value' => '不关联'),
        );

        $project = $this->select($project, 'project');
        $this->assign("project", $project);

        $this->display();
    }

    //更多
    public function gengduo()
    {
        $user = I('user');
        $quarter = I('quarter', C(KH_QUARTER));
        $this->assign("user", $user);
        $where = array('user' => $user, 'quarter' => $quarter, 'deleted' => '0');
        $data = M('tp_my_score')->where($where)->order('ctime desc')->select();
        $this->assign("data", $data);

        $score = sumScore($user, $quarter);
        $this->assign("score", $score);

        $this->display();
    }

    //申诉
    public function appeal()
    {
        $arr = M('tp_my_score')->find(I('id'));
        if ($arr['dissent']) {
            $this->assign("arr", $arr);
            $shix = $arr['ctime'] + 7 * 24 * 3600;
            $this->assign("shix", date('Y-m-d H:i:s', $shix));
            $this->display();
        } else {
            dump('考核不允许申诉');
        }


    }

}