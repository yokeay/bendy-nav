<?php
namespace app\controller\apps\food;

use app\model\CardModel;
use app\PluginsBase;

class Index extends PluginsBase
{

    function foodList(): \think\response\Json
    {
        $foodListArr = [
            '麻婆豆腐', '水饺', '粤菜点心', '北京烤鸭', '锅包肉', '水煮鱼', '担担面', '龙虾', '火锅', '蒸鱼',
            '宫保鸡丁', '粤菜烧腊', '干煸四季豆', '芝麻鸡', '粉蒸肉', '烤羊肉串', '红烧肉', '酸辣汤', '馄饨', '扬州炒饭',
            '豆腐脑', '铁板牛肉', '红烧鱼头', '清蒸虾', '鱼香肉丝', '葱油饼', '鱼香茄子', '糖醋排骨', '酸辣粉', '干锅系列',
            '梅菜扣肉', '麻辣香锅', '黄焖鸡', '海鲜烩饭', '鲍鱼粥', '东坡肉', '叉烧饭', '糖醋鱼', '蚝烙', '蚵仔煎',
            '拉面', '炒年糕', '糖醋鲤鱼', '花甲粉丝', '云吞面', '卤肉饭', '榴莲酥', '珍珠奶茶', '凤爪', '蚵仔面线',
            '蚵仔煎', '蚵仔炒麵', '面线', '猪脚麵线', '肉羹', '炒米粉', '鹹豆漿', '肉圆', '碗粿', '米苔目',
            '蛤仔麵', '肥肠粉', '蚵仔麵線', '虾饺', '水煎包', '蚵仔炸', '粥', '炒鸡絲飯', '蛋饺', '肉燥飯',
            '蚵仔煎', '炒烏龍麵', '担仔麵', '蝦捲', '擔仔麵', '擔擔麵', '焦糖布丁', '珍珠球', '烏龍麵', '卤肉', '酸辣粉'
        ];

        $list = CardModel::config("food", "foodList");
        if (!$list) {
            $list = $foodListArr;
        }
        return $this->success("ok", $list);
    }

    function foodListSave(): \think\response\Json
    {
        $admin = $this->getAdmin();
        $list = $this->request->post("foods", []);
        CardModel::saveConfig("food", "foodList", $list);
        return $this->success('保存成功');
    }
}