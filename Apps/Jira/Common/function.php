<?php
//获取自定义字段值
function getCustomFieldValue($ISSUE, $CUSTOMFIELD)
{
    $where = array('ISSUE' => $ISSUE, 'CUSTOMFIELD' => $CUSTOMFIELD);
    $date = M('customfieldvalue')->where($where)->find();
    if ($date) {
        return $date['stringvalue'];
    } else {
        return '';
    }
}

//获取Jira用户名
function getJiraName($name)
{
    if ($name) {
        $m = D('tp_jira_user');
        $where = array('username' => $name, 'deleted' => '0');
        $data = $m->where($where)->cache('cache_' . $name)->find();
        if ($data) {
            return $data['name'];
        } else {
            //查不到的从远端拉取信息到tp_jira_user
            $url = C(JIRAPI) . "/Jirapi/user?method=find&USER_KEY=" . $name;
            $data = httpGet($url);
            $data = json_decode(trim($data, "\xEF\xBB\xBF"), true);
            $var['username'] = $name;
            $var['name'] = $data['display_name'];
            if (!$m->create($var)) {
                $this->error($m->getError());
            }
            $m->add($var);
            return $data['display_name'];
        }
    }
}

function getELUser($id)
{
    $arr = M('eo_user')->find($id);
    return $arr['usernickname'];
}


//数组转字符串
function arrayToStr($array)
{
    $strMail = '';
    $length = sizeof($array);
    if ($length) {
        if ($length == 1) {
            $strMail = $array[0];
        } else {
            foreach ($array as $m) {
                $strMail .= $m . ";";
            }
        }

    }
    return $strMail;
    }

//获取迭代抽签信息
function drawInfo($project)
{
    $where = array("project" => $project, 'deleted' => '0');
    $data = M('tp_project_assigne')->where($where)->field('name,draw,renounce')->select();
    $str = '';
    if ($data) {
        foreach ($data as $da) {
            $str .= '<div class="col-xs-6 col-md-2">';
            $str .= '<div class="thumbnail">';
            $str .= '<div class="caption">';
            if ($da['renounce']) {
                $str .= '<h3 class="text-center">' . getJiraName($da['name']) . ' <small>放弃</small></h3>';
            } else {
                $str .= '<h3 class="text-center">' . getJiraName($da['name']) . '</h3>';
            }
            $str .= '</div>';
            $str .= '<img src="https://xiuliguanggao.com/Public/images/puk/h_' . $da['draw'] . '.jpg" width="80px">';
            $str .= '</div>';
            $str .= '</div>';
        }
    }
    return $str;
}

//获取issue详情
function getIssueInfo($id)
{
    $url = C(JIRAPI) . "/Jirapi/issue/" . $id;
    $data = httpGet($url);
    $data = json_decode(trim($data, "\xEF\xBB\xBF"), true);
    $str = $_SESSION['pkey'] . '-' . $data['issuenum'] . '&nbsp;' . $data['summary'];
    $str .= '<span class="badge pull-right">' . getPriority($data['priority']) . '</span>';
    return $str;
}

//获取优先级别
function getPriority($id)
{
    $str = '';
    if ($id) {
        $m = D('priority');
        $data = $m->find($id);
        if (!$data) {
            //请求接口拉取数据
            $url = C(JIRAPI) . "/Jirapi/priority/" . $id;
            $data = httpGet($url);
            $data = json_decode(trim($data, "\xEF\xBB\xBF"), true);
            $var['ID'] = $id;
            $var['pname'] = $data['pname'];
            if (!$m->create($var)) {
                $this->error($m->getError());
            }
            $m->add($var);
        }
        $str = $data['pname'];
    }
    return $str;
}

//获取issue类型
function getIssueType($id)
{
    $str = '';
    if ($id) {
        $m = D('issuetype');
        $data = $m->find($id);
        if (!$data) {
            //请求接口拉取数据
            $url = C(JIRAPI) . "/Jirapi/issuetype/" . $id;
            $data = httpGet($url);
            $data = json_decode(trim($data, "\xEF\xBB\xBF"), true);
            $var['ID'] = $id;
            $var['pname'] = $data['pname'];
            if (!$m->create($var)) {
                $this->error($m->getError());
            }
            $m->add($var);
        }
        $str = $data['pname'];
    }
    return $str;
}

//获取issue状态
function getIssueStatus($id)
{
    $str = '';
    if ($id) {
        $m = D('issuestatus');
        $data = $m->find($id);
        if (!$data) {
            //请求接口拉取数据
            $url = C(JIRAPI) . "/Jirapi/issuestatus/" . $id;
            $data = httpGet($url);
            $data = json_decode(trim($data, "\xEF\xBB\xBF"), true);
            $var['ID'] = $id;
            $var['pname'] = $data['pname'];
            if (!$m->create($var)) {
                $this->error($m->getError());
            }
            $m->add($var);
        }
        $str = $data['pname'];
    }
    return $str;
}

//获取测试周期BUG
function getTestRunBug($run_id, $step_id = '')
{
    $url = C(JIRAPI) . "/Jirapi/testrunbug?run_id=" . $run_id . '&step_id=' . $step_id;
    $data = httpGet($url);
    $data = json_decode(trim($data, "\xEF\xBB\xBF"), true);
    return $data;
}

//获取测试步骤
function getTestRunStep($id)
{
    $str = '';
    $url = C(JIRAPI) . "/Jirapi/testrunsetp/" . $id . '/run';
    $data = httpGet($url);
    $data = json_decode(trim($data, "\xEF\xBB\xBF"), true);
    if ($data) {
        foreach ($data as $k => $da) {
            $str .= '<li class="list-group-item">';
            $str .= '<div class="row">';
            $str .= '<div class="col-md-1">' . $k . '</div>';
            $str .= '<div class="col-md-4">' . $da['step'] . '</div>';
            $str .= '<div class="col-md-4">' . $da['expected_result'] . '</div>';
            $str .= '<div class="col-md-1">' . $da['status'] . '</div>';
            $str .= '<div class="col-md-2">' . $da['actual_result'] . '</div>';
            $str .= '</div>';
            $str .= '</li>';
        }

    }
    return $str;

}

//获取测试用例的执行周期
function getCaseRun($tc, $cyc)
{
    $str = '';
    $where['TC_ID'] = $tc;
    $where['TEST_CYCLE_ID'] = array('in', $cyc);
    $url = C(JIRAPI) . "/Jirapi/testrun";
    $data = httpJsonPost($url, $where);
    $data = json_decode(trim($data, "\xEF\xBB\xBF"), true);
    if ($data) {
        foreach ($data as $k => $da) {
            $str .= '<li class="list-group-item">';
            $str .= '<div class="row">';
            $str .= '<div class="col-md-1">' . $k . '</div>';
            $str .= '<div class="col-md-4">' . $da['test_cycle_id'] . '</div>';
            $str .= '<div class="col-md-4">' . $da['executed_by'] . '</div>';
            $str .= '<div class="col-md-1">' . $da['status'] . '</div>';
            $str .= '<div class="col-md-2">' . $da['execution_on'] . '</div>';
            $str .= '</div>';
            $str .= '</li>';
        }

    }
    return $str;
}

//统计加班时长
function getJiabHour($user)
{
    $where = array('user' => $user, 'type' => '1');
    $Num = M('tp_overtime')->where($where)->sum('hourlong');
    if ($Num) {
        return $Num;
    } else {
        return 0;
    }
}

//统计调休时长
function getTiaoxHour($user)
{
    $where = array('user' => $user, 'type' => '2');
    $Num = M('tp_overtime')->where($where)->sum('hourlong');
    if ($Num) {
        return $Num;
    } else {
        return 0;
    }

}

//计算可调休时长
function getKeyHour($user)
{
    $where = array('user' => $user, 'type' => '2');
    $tiaox = M('tp_overtime')->where($where)->sum('hourlong');
    $where = array('user' => $user, 'type' => '1');
    $jiab = M('tp_overtime')->where($where)->sum('hourlong');
    $Num = $jiab - $tiaox;
    if ($Num) {
        return $Num;
    } else {
        return 0;
    }
}

