<?php
return array(
    //'配置项'=>'配置值'
    'TMPL_PARSE_STRING' => array(
        '__CSS__' => __ROOT__ . '/Public/css',
        '__JS__' => __ROOT__ . '/Public/js',
        '__UPLOAD__' => __ROOT__ . '/Upload'
    ),
    'DEFAULT_THEME' => 'Amaze',//开启模板
    'PRODUCT' => 'JIRA',//定义产品编号
    'JIRAURL' => 'http://jira.zhidaohulian.com',
    'QA_TESTER' => array('yaolihui', 'fanqiao', 'menghuihui', 'wangchenzi', 'lixm'),
);