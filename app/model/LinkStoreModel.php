<?php

/*
 * @description:
 * @Date: 2022-09-26 20:27:01
 * @LastEditTime: 2022-09-26 20:27:53
 */

namespace app\model;

use think\Model;

class LinkStoreModel extends Model
{
    protected $name = "linkstore";
    protected $pk = "id";
    protected $jsonAssoc = true;
    protected $json = ['custom'];

    function userInfo(): \think\model\relation\HasOne
    {
        return $this->hasOne(UserModel::class, 'id', 'user_id')->field('id,nickname');
    }
    function setGroupIdsAttr($val): string
    {
        if (count($val) > 0) {
            return join(',', $val);
        }
        return '0';
    }

    function getGroupIdsAttr($val): array
    {
        if (strlen($val)) {
            return array_map('intval', explode(',', $val));
        }
        return [];
    }
}
