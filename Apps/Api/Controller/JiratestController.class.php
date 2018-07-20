<?php
/*
 * Api：设备管理
 *
 */

namespace Api\Controller;
class JiratestController extends BasicController
{

    public function index()
    {
        $username = I('username', 'ylh');
        $password = I('password', '123456');
        $requirementIssueKey = 'CX-24';
        $url = C(JIRAURL) . '/rest/synapse/latest/public/requirement/' . $requirementIssueKey . '/linkedTestCases';
        $arr = json_decode(httpAuthGet($url, $username, $password), true);
        $arr = arrDate($arr);
        $this->ajaxReturn($arr);
    }

    public function login()
    {
        $username = I('username', 'ylh');
        $password = I('password', '123456');
        $postData = array(
            'username' => $username,
            'password' => $password
        );
        $postJson = json_encode($postData);
        $url = C(JIRAURL) . '/rest/auth/1/session';
        $arr = json_decode(httpAuthPost($url, $postJson), true);
        $arr = arrDate($arr);
        $this->ajaxReturn($arr);
    }


}