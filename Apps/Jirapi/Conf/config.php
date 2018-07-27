<?php
return array(
    //'配置项'=>'配置值'
    'PRODUCT' => 'Jirapi',//定义产品
//    'HOSTS'                   => 'http://jira.zhidaohulian.com',
    'URL_ROUTER_ON' => true,
    'URL_ROUTE_RULES' => array(
        'project/:id' => 'Project/index',
        'project' => 'Project/index',
        'issue/:projectKey/story' => 'Jiraissue/story',
        'issue/:projectKey/plan' => 'Jiraissue/plan',
        'user/:USER_KEY' => 'User/index',
        'user' => 'User/index',
    ),
    'QA_TESTER' => array('ylh', 'fj', 'mhh', 'wcz', 'lixiaomei'),
    'DB_HOST' => '127.0.0.1',
    'DB_NAME' => 'jira',
    'DB_USER' => 'root',
    'DB_PWD' => 'root',

//    'DB_HOST'             => '10.2.9.44',
//    'DB_NAME'             => 'jira',
//    'DB_USER'             => 'jira',
//    'DB_PWD'              => 'JExHT0HSgiB1qOkP',
    'DB_PORT' => '3306',
    'DB_PREFIX' => '',

);