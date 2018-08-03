<?php

namespace Jira\Controller;
class TestcycleController extends WebInfoController
{
    public function index()
    {


        $arr = I('');
        $url = C(JIRAPI) . "/Jirapi/issue/" . $arr['id'];
        $data = httpGet($url);
        $data = json_decode(trim($data, "\xEF\xBB\xBF"), true);
        $this->assign('data', $data);


        $this->display();
    }

}