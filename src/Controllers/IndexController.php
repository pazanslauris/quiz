<?php

namespace Quiz\Controllers;

class IndexController extends BaseController
{
    public function IndexAction()
    {
        return $this->render('index');
    }
}