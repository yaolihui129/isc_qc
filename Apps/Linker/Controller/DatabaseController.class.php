<?php

namespace Linker\Controller;

class DatabaseController extends BasicController
{
    public function index()
    {
        $search = I('search');
        $this->assign('search', $search);
        $where['projectName|projectVersion'] = array('like', '%' . $search . '%');
        $data = $this->projectList($where);
        $this->assign('data', $data);

        $this->display();
    }


}