<?php

namespace Jira\Controller;
class IndexController extends WebInfoController
{
    public function index()
    {
        $username = I('username', 'ylh');
        $password = I('password', '123456');
        $requirementIssueKey = "CX";
//        $url=C(JIRAURL).'/rest/api/latest/issue/'.$requirementIssueKey.'/subtask';
        $url = C(JIRAURL) . '/rest/api/latest/project/' . $requirementIssueKey . '/issue';
        $json = httpAuthGet($url, $username, $password);
        $data = json_decode($json, true);
        $this->assign('data', $data);
        print_r($url);
        print_r($json);

        $this->display();
    }


}