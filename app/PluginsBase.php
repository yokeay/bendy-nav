<?php

namespace app;

use think\App;
use think\View;

class PluginsBase extends BaseController
{
    public  $view = null;
    function __construct(App $app)
    {
        parent::__construct($app);
        $this->_initialize();
    }

    function _initialize()
    {
        $this->view = new View($this->app);
    }

    function assign($key, $view)
    {
        $this->view->assign($key, $view);
    }

    function fetch($view, $opt = []): string
    {
        $view = plugins_path("view/" . $view);
        return $this->view->fetch($view, $opt);
    }

}