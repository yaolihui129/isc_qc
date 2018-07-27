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

        $search = trim(I('search'));
        $_SESSION['search']['index'] = $search;
        $this->assign('search', $search);

        $where['SUMMARY|issuenum'] = array('like', '%' . $search . '%');
        $_SESSION['map']['index'] = $where;

        $url = C(JIRAPI) . "/Jirapi/issue/CX/story";

        $data = httpJsonPost($url, json_encode($where));
//        print_r($data);
        $data = json_decode(trim($data, "\xEF\xBB\xBF"), true);
//        $data=M('jiraissue')->where($where)->select();
        $this->assign('data', $data);

        $project = $this->projectDict();
        $this->assign('project', $project);

        $this->display();
    }


}