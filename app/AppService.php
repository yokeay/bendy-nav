<?php
declare (strict_types=1);

namespace app;

use think\Service;

/**
 * 应用服务类
 */
class AppService extends Service
{
    public function register()
    {
        // 服务注册
        if (!file_exists(public_path() . '/installed.lock')) {//如果没有安装的就提示安装
            //如果是/installApp开头的路径一律放行
            header('Location:/install.php');
            exit();
        }
    }

    public function boot()
    {

    }
}
