<?php
return array(
    //'配置项'=>'配置值'
    'PRODUCT' => 'Jirapi',
    'URL_ROUTER_ON' => true,
    'DB_HOST' => '127.0.0.1',
    'DB_NAME' => 'jira',
    'DB_USER' => 'root',
    'DB_PWD' => 'root',
    'DB_PORT' => '3306',
    'DB_PREFIX' => '',
    'URL_ROUTE_RULES' => array(
        'Action/:id' => 'Action/index',
        'component/:id' => 'Component/index',
        'issue/:issuenum/issuenum' => 'issue/issuenum',
        'issue/:id' => 'issue/index',
        'project/:id' => 'Project/index',
        'sprint/:id' => 'Sprint/index',
        'casestatus/:tc/test' => 'Testcasedetails/test',
        'casestatus/:id' => 'Testcasedetails/index',
        'cycle/:tp/plan' => 'Testcycle/plan',
        'cycle/:id' => 'Testcycle/index',
        'plancase/:tp/plan' => 'Testplanmember/plan',
        'plancase/:id' => 'Testplanmember/index',
        'testrunbug/:id' => 'Testrunbug/index',
        'testrun/:id' => 'Testrun/index',
        'testrunhistory/:id' => 'Testrunhistory/index',
        'testrunsetp/:run/run' => 'Runstep/run',
        'testrunsetp/:id' => 'Runstep/index',
        'testsuite/:id' => 'Testsuite/index',
        'testsuitelabel/:id' => 'Testsuitelabel/index',
        'testsuitemember/:id' => 'Testsuitemember/index',
        'user/:id' => 'User/index',
        'version/:id' => 'Version/index',
    ),


);