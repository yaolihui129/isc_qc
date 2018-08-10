<?php

namespace Jira\Controller;
class PlanController extends WebInfoController
{
    public function index()
    {
        $_SESSION['type']['plan'] = I('type');
        if ($_SESSION['type']['plan'] == 'done') {
            $where['issuestatus'] = array('in', '3,10002,10011');
        } else {
            $where['issuestatus'] = array('not in', '3,10002,10011');
        }

        if (I('project')) {
            $_SESSION['project'] = I('project');
            $pro = M('project')->find($_SESSION['project']);
            $_SESSION['pkey'] = $pro['pkey'];
            $_SESSION['pname'] = $pro['pname'];
        }

        $where['PROJECT'] = intval($_SESSION['project']);
//        $pro = M('project')->find($_SESSION['project']);
//        $_SESSION['pkey']=$pro['pkey'];


        $where['issuetype'] = '10102';
        $search = trim(I('search'));
        $_SESSION['search']['index'] = $search;
        $this->assign('search', $search);
        $where['SUMMARY|issuenum'] = array('like', '%' . $search . '%');

        $url = C(JIRAPI) . "/Jirapi/issue";
        $data = httpJsonPost($url, json_encode($where));
        $data = json_decode(trim($data, "\xEF\xBB\xBF"), true);
        $this->assign('data', $data);

        $project = $this->projectDict();
        $this->assign('project', $project);

        $this->display();
    }

}