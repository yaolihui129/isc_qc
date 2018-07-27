<?php
return array(
    //'配置项'=>'配置值'
    'TMPL_PARSE_STRING' => array(
        '__CSS__' => __ROOT__ . '/Public/css',
        '__JS__' => __ROOT__ . '/Public/js',
        '__UPLOAD__' => __ROOT__ . '/Upload'
    ),
    'DEFAULT_THEME' => 'Amaze',//开启模板
    'PRODUCT' => 'Jira',//定义产品编号
    'JIRAPI' => 'http://127.0.0.1/isc_qc',
//    'JIRAPI'=>'http://qc.zhidaoauto.com/index.php',
    'JIRAURL' => 'http://jira.zhidaohulian.com',
    'QA_TESTER' => array('ylh', 'fj', 'mhh', 'wcz', 'lixiaomei'),
    'DB_HOST' => '127.0.0.1',
    'DB_NAME' => 'jira',
    'DB_USER' => 'root',
    'DB_PWD' => 'root',
    'DB_PORT' => '3306',
    'DB_PREFIX' => '',
    //默认错误跳转对应的模板文件
    'TMPL_ACTION_ERROR' => 'Public:error',
    //默认成功跳转对应的模板文件
    'TMPL_ACTION_SUCCESS' => 'Public:success',
    'DRAW' => array('2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A', 'JOKER'),
);