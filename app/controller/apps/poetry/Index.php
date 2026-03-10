<?php

namespace app\controller\apps\poetry;

use app\model\CardModel;
use app\PluginsBase;

class Index extends PluginsBase
{

    function poetryList(): \think\response\Json
    {
        $admin = $this->getAdmin();
        $foodListArr = include "content.php";
        $list = CardModel::config("poetry", "poetryList");
        if (!$list) {
            $list = $foodListArr;
        }
        return $this->success("ok", $list);
    }

    function reset(): \think\response\Json
    {
        $admin = $this->getAdmin();
        CardModel::saveConfig('poetry', 'poetryList', []);
        return $this->success('ok');
    }

    function poetryOne(): \think\response\Json
    {
        $foodListArr = include 'content.php';
        $list = CardModel::config('poetry', 'poetryList');
        if (!$list) {
            $list = $foodListArr;
        }
        //随机取一个数组中的内容
        $one = $list[array_rand($list)];
        return $this->success('ok', $one);
    }

    function poetryListSave(): \think\response\Json
    {
        $admin = $this->getAdmin();
        $list = $this->request->post("list", []);
        //php取300条，如果少于300条全部取上
        $list = array_slice($list, 0, 300);
        CardModel::saveConfig("poetry", "poetryList", $list);
        if (count($list) > 300) {
            return $this->success('最多只能保存300条,超出部分将被忽略');
        }
        return $this->success('保存成功');
    }
}