<?php

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

function getJiraName($name)
{
    $url = C(JIRAPI) . '/Jirapi/user/' . $name;
    $data = httpGet($url);
    $data = json_decode(trim($data, "\xEF\xBB\xBF"), true);
//        $where=array('USER_KEY'=>$name);
//        $date=M('ao_2d3bea_user_index')->where($where)->find();
    if ($data) {
        return $data['display_name'];
    } else {
        return '';
    }
}

