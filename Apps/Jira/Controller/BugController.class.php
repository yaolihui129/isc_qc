<?php

namespace Jira\Controller;
class BugController extends WebInfoController
{
    public function index()
    {
        $where['PROJECT'] = intval($_SESSION['project']);
        $where['issuetype'] = '10008';
        $where['issuestatus'] = '1';
        $datum = date("Y-m-d", time() - 24 * 3600);
        $datum = strtotime($datum);//将日期转化为时间戳
        $datum = date("Y-m-d H:i", $datum + 17.5 * 3600);
        $where['CREATED'] = array('lt', $datum);
        $url = C(JIRAPI) . "/Jirapi/issue";
        $data = httpJsonPost($url, json_encode($where));
        $data = json_decode(trim($data, "\xEF\xBB\xBF"), true);
        $this->assign('data', $data);

        $this->display();
    }


    public function fault()
    {
        $where['PROJECT'] = intval($_SESSION['project']);
        $where['issuetype'] = '10008';
        $where['PRIORITY'] = array('in', '1,2');
//        $where['CREATED'] = array('lt',$datum);
        $url = C(JIRAPI) . "/Jirapi/issue";
        $data = httpJsonPost($url, json_encode($where));
        $data = json_decode(trim($data, "\xEF\xBB\xBF"), true);
        $this->assign('data', $data);

        $this->display();
    }

    public function noclosed()
    {
        $where['PROJECT'] = intval($_SESSION['project']);
        $where['issuetype'] = '10008';
        $where['issuestatus'] = array('not in', '10011');
        $url = C(JIRAPI) . "/Jirapi/issue";
        $data = httpJsonPost($url, json_encode($where));
        $data = json_decode(trim($data, "\xEF\xBB\xBF"), true);
        $this->assign('data', $data);

        $this->display();
    }

    public function bug_list()
    {
        $data = getTestRunBug(I('run_id'), I('step_id'));
        $arr = array();
        foreach ($data as $da) {
            $arr[] = $da['bug_id'];
        }
        $where['ID'] = array('in', $arr);
        $url = C(JIRAPI) . "/Jirapi/issue";
        $data = httpJsonPost($url, json_encode($where));
        $data = json_decode(trim($data, "\xEF\xBB\xBF"), true);

        $this->assign('data', $data);
        $this->display();
    }

    public function mine()
    {
        $where['issuetype'] = '10008';
        $where['issuestatus'] = array('not in', '10011');
        $where['ASSIGNEE'] = $_SESSION['user'];
        $url = C(JIRAPI) . "/Jirapi/issue";
        $data = httpJsonPost($url, json_encode($where));
        $data = json_decode(trim($data, "\xEF\xBB\xBF"), true);
        $this->assign('data', $data);

        $this->display();
    }

}