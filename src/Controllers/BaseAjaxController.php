<?php

namespace Quiz\Controllers;


class BaseAjaxController extends BaseController
{
    protected function callAction($action) {
        echo json_encode(static::$action(), JSON_UNESCAPED_UNICODE);
    }
}