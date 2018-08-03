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
        $data = $m->where($where)->find();
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
            $str .= '<h3 class="text-center">' . getJiraName($da['name']) . '</h3>';
            $str .= '</div>';
            $str .= '<img src="https://xiuliguanggao.com/Public/images/puk/h_' . $da['draw'] . '.jpg" width="80px">';
            $str .= '</div>';
            $str .= '</div>';
        }
    }
    return $str;
}

function httpGetJson($url, $jsons)
{
    $ch = curl_init(); //1.获取初始化URL
    //2.设置curl的参数
    $url = $url . "?data=" . $jsons . "";
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 500);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    $res = curl_exec($ch);//3.采集
    curl_close($ch);//4.关闭
    if (curl_errno($ch)) {
        $res = curl_errno($ch);
    }
    return $res;

}

