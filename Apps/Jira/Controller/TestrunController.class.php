<?php

namespace Jira\Controller;
class TestrunController extends WebInfoController
{
    public function index()
    {
        $cyc = I('cyc');
        //1.获取测试周期详情
        $url = C(JIRAPI) . "/Jirapi/cycle/" . $cyc;
        $cycle = httpGet($url);
        $cycle = json_decode(trim($cycle, "\xEF\xBB\xBF"), true);
        $this->assign('cycle', $cycle);

        //2.获取测试关联的测试用例
        $url = C(JIRAPI) . "/Jirapi/testrun?cycle=" . $cyc;
        $case = httpGet($url);
        $case = json_decode(trim($case, "\xEF\xBB\xBF"), true);
        $this->assign('case', $case);

        $this->display();
    }

}