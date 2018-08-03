<?php

namespace Jira\Controller;
class ReportController extends WebInfoController
{
    public function index()
    {
        $mail = array('ylh@zhidaoauto.com');
        $cc = array('ylh@zhidaoauto.com', 'cf@zhidaoauto.com');
        $subject = '测试环境测试报告';
        $body = '<h1>测试环境测试报告</h1>';
        $str = array(arrayToStr($mail), arrayToStr($cc), $subject, $body);
        $this->assign('str', $str);

        $this->assign('mail', $mail);

        $source = "CX-34";
        $hello = explode('-', $source);
        dump($hello);

        $this->display();
    }


}