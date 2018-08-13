<?php

namespace Jira\Controller;
class TestcycleController extends WebInfoController
{
    public function index()
    {
        $tp = I('tp');
        //1.获取测试计划详情
        $url = C(JIRAPI) . "/Jirapi/issue/" . $tp;
        $plan = httpGet($url);
        $plan = json_decode(trim($plan, "\xEF\xBB\xBF"), true);
        $_SESSION['testPlan'] = $plan;
        $this->assign('plan', $plan);
        //2.获取测试关联的测试用例
        $url = C(JIRAPI) . "/Jirapi/plancase/" . $tp . "/plan";
        $case = httpGet($url);
        $case = json_decode(trim($case, "\xEF\xBB\xBF"), true);
        $this->assign('case', $case);
        if ($case) {
            $count = sizeof($case);
            foreach ($case as $ca) {
                $case_id_arr[] = $ca['tc_id'];
            }
            //3.获取测试功能点（测试范围）
            $where['ID'] = array('in', $case_id_arr);
            $where['PRIORITY'] = '1';
            $url = C(JIRAPI) . "/Jirapi/issue";
            $func = httpJsonPost($url, json_encode($where));
            $func = json_decode(trim($func, "\xEF\xBB\xBF"), true);

            $this->assign('func', $func);
            if ($func) {
                $c = sizeof($func);
            } else {
                $c = 0;
            }
            $this->assign('c', $c);
        } else {
            $count = 0;
            $this->assign('c', 0);
        }
        $this->assign('count', $count);


        //4.获取测试计划下的测试周期
        $url = C(JIRAPI) . "/Jirapi/cycle/" . $tp . "/plan";
        $data = httpGet($url);
        $data = json_decode(trim($data, "\xEF\xBB\xBF"), true);
        $this->assign('data', $data);


        //5.提测打回邮件
        $mail = $plan['assignee'] . '@zhidaoauto.com';
        $cc = '';
        $subject = $plan['summary'] . '，测试准入验收未通过，提测版本打回，有项目可能延期的风险';
        $body = $plan['summary'] . ',<br>
                在按照给出的冒烟测试标准进行测试准入验收时，发现如下冒烟用例未通过<br>
                （附上未通过的冒烟用例列表）<br>
                造成XX等功能无法正常进行测试，从而也无法按照计划进入测试环节，会有项目可能延期的风险<br>
                <br>
                请相关责任人务必回复邮件说明原因，并明确给出可以重新提测的时间节点。<br>';
        $str = array($mail, arrayToStr($cc), $subject, $body);
        $this->assign('str', $str);

        //6.延期预警邮件


        $this->display();
    }


    public function tcase()
    {
        if ($_SESSION['testPlan']) {
            $plan = $_SESSION['testPlan'];
        } else {
            $tp = I('tp');
            //1.获取测试计划详情
            $url = C(JIRAPI) . "/Jirapi/issue/" . $tp;
            $plan = httpGet($url);
            $plan = json_decode(trim($plan, "\xEF\xBB\xBF"), true);
            $_SESSION['testPlan'] = $plan;
        }
        $this->assign('plan', $plan);

        //2.获取测试关联的测试用例
        $url = C(JIRAPI) . "/Jirapi/plancase/" . $plan['id'] . "/plan";
        $case = httpGet($url);
        $case = json_decode(trim($case, "\xEF\xBB\xBF"), true);
        $this->assign('case', $case);
        if ($case) {
            $count = sizeof($case);
            foreach ($case as $ca) {
                $case_id_arr[] = $ca['tc_id'];
            }
            //3.获取测试功能点（测试范围）
            $where['ID'] = array('in', $case_id_arr);
            $where['PRIORITY'] = '1';
            $url = C(JIRAPI) . "/Jirapi/issue";
            $func = httpJsonPost($url, json_encode($where));
            $func = json_decode(trim($func, "\xEF\xBB\xBF"), true);

            $this->assign('func', $func);
            if ($func) {
                $c = sizeof($func);
            } else {
                $c = 0;
            }
            $this->assign('c', $c);
        } else {
            $count = 0;
            $this->assign('c', 0);
        }
        $this->assign('count', $count);

        $this->display();
    }

    public function bug()
    {
        if ($_SESSION['testPlan']) {
            $plan = $_SESSION['testPlan'];
        } else {
            $tp = I('tp');
            //1.获取测试计划详情
            $url = C(JIRAPI) . "/Jirapi/issue/" . $tp;
            $plan = httpGet($url);
            $plan = json_decode(trim($plan, "\xEF\xBB\xBF"), true);
            $_SESSION['testPlan'] = $plan;
        }
        $this->assign('plan', $plan);

        //5.获取本次迭代的BUG
        $key = getName('project', $plan['project'], 'pkey') . '-' . $plan['issuenum'];
        $url = C(JIRAURL) . "/rest/synapse/latest/public/testPlan/" . $key . "/defects";
        $bug = httpAuthGet($url, ylh, 123456);
        $bug = json_decode(trim($bug, "\xEF\xBB\xBF"), true);
        $this->assign('bug', $bug);

        $this->display();
    }


    public function yiliu()
    {

        if ($_SESSION['testPlan']) {
            $plan = $_SESSION['testPlan'];
        } else {
            $tp = I('tp');
            //1.获取测试计划详情
            $url = C(JIRAPI) . "/Jirapi/issue/" . $tp;
            $plan = httpGet($url);
            $plan = json_decode(trim($plan, "\xEF\xBB\xBF"), true);
            $_SESSION['testPlan'] = $plan;
        }
        $this->assign('plan', $plan);

        //5.获取本次迭代的BUG
        $key = getName('project', $plan['project'], 'pkey') . '-' . $plan['issuenum'];
        $url = C(JIRAURL) . "/rest/synapse/latest/public/testPlan/" . $key . "/defects";
        $bug = httpAuthGet($url, ylh, 123456);
        $bug = json_decode(trim($bug, "\xEF\xBB\xBF"), true);
        $this->assign('bug', $bug);
        if ($bug) {
            foreach ($bug as $b) {
                $bug_id[] = $b['id'];
            }
        }
        //6.获取遗留BUG
        $where = array();
        $where['ID'] = array('in', $bug_id);
        $where['issuestatus'] = array('not in', '10011');
        $url = C(JIRAPI) . "/Jirapi/issue";
        $yiliu = httpJsonPost($url, json_encode($where));
        $yiliu = json_decode(trim($yiliu, "\xEF\xBB\xBF"), true);
        $this->assign('yiliu', $yiliu);

        $this->display();
    }

    //提测打回
    public function repulse()
    {
        //5.提测打回邮件
        $mail = $_SESSION['testPlan']['assignee'] . '@zhidaoauto.com';
        $cc = '';
        $subject = $_SESSION['testPlan']['summary'] . '，测试准入验收未通过，提测版本打回，有项目可能延期的风险';
        $body = $_SESSION['testPlan']['summary'] . ',<br>
                在按照给出的冒烟测试标准进行测试准入验收时，发现如下冒烟用例未通过<br>
                （附上未通过的冒烟用例列表）<br>
                造成XX等功能无法正常进行测试，从而也无法按照计划进入测试环节，会有项目可能延期的风险<br>
                <br>
                请相关责任人务必回复邮件说明原因，并明确给出可以重新提测的时间节点。<br>';
        $str = array($mail, arrayToStr($cc), $subject, $body);
        $this->assign('str', $str);

        //6.延期预警邮件


        $this->display();
    }

    //延期预警
    public function warning()
    {
        //5.提测打回邮件
        $mail = $_SESSION['testPlan']['assignee'] . '@zhidaoauto.com';
        $cc = '';
        $subject = $_SESSION['testPlan']['summary'] . '，未达到最基本的上线标准，项目已经延期';
        $body = $_SESSION['testPlan']['summary'] . ',<br>
                原计划XX月XX日上线，截止到XX时间<br>'
            . '还有XX必测点没有时间验证<br>（附上必测点清单）<br>'
            . 'Jira中还有BUG没有关闭<br>（ 附上BUG列表）
                <br>
                完全达不到上线的最基本要求，项目已经延期<br><br>
                请产品经理或其他负责人紧急召开项目会议，商议下一步的解决方案';
        $str = array($mail, arrayToStr($cc), $subject, $body);
        $this->assign('str', $str);

        //6.延期预警邮件


        $this->display();

    }

}