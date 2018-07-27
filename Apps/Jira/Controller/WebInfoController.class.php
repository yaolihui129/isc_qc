<?php
namespace Jira\Controller;
use Think\Controller;
class WebInfoController extends Controller
{
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
        $url = C(JIRAPI) . '/Jirapi/project';
        $data = httpGet($url);
        $data = json_decode(trim($data, "\xEF\xBB\xBF"), true);
//        $data=M('project')->select();
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

    function jira_api()
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
    }



}