<?php
/*
 * @description:
 * @Date: 2022-09-26 20:27:01
 * @LastEditTime: 2022-09-26 20:27:53
 */

namespace app\model;

use think\Model;

class LinkFolderModel extends Model
{
    protected $pk = 'id';
    protected $name = 'link_folder';

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