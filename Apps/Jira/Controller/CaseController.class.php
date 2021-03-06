<?php

namespace Jira\Controller;
class CaseController extends WebInfoController
{
    public function index()
    {
        $_SESSION['type']['plan'] = I('type');
        if ($_SESSION['type']['plan'] == 'done') {
            $where['RESOLUTIONDATE'] = array('exp', 'is not null');
        } else {
            $where['RESOLUTIONDATE'] = array('exp', 'is null');
        }

        if (I('project')) {
            $_SESSION['project'] = I('project');
        }

        $where['PROJECT'] = intval($_SESSION['project']);
        $where['issuetype'] = '10102';

        $search = trim(I('search'));
        $_SESSION['search']['index'] = $search;
        $this->assign('search', $search);

        $where['SUMMARY|issuenum'] = array('like', '%' . $search . '%');
        $_SESSION['map']['plan'] = $where;
        $url = C(JIRAPI) . "/Jirapi/issue";
        $data = httpJsonPost($url, json_encode($where));
        $data = json_decode(trim($data, "\xEF\xBB\xBF"), true);
        $this->assign('data', $data);

        $project = $this->projectDict();
        $this->assign('project', $project);

        $this->display();
    }

}