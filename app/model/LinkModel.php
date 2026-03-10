<?php
/*
 * @description:
 * @Date: 2022-09-26 20:27:01
 * @LastEditTime: 2022-09-26 20:27:53
 */

namespace app\model;

use think\Model;

class LinkModel extends Model
{
    protected $name = "link";
    protected $pk = "user_id";
    protected $autoWriteTimestamp = "datetime";
    protected $updateTime = "update_time";
    protected $jsonAssoc = true;
    protected $json = ['link'];
    protected $WebApp = [];
    protected $card = [];

    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $list = LinkStoreModel::where("app", 1)->select()->toArray();
        $tmp = [];
        foreach ($list as $k => $v) {
            $tmp[$v['id']] = $v;
        }
        $this->WebApp = $tmp;
    }

    function getLinkAttr($value): array
    {
        foreach ($value as $k => &$v) {
            if (isset($v['app']) && $v['app'] == 1) {
                if (isset($v['origin_id']) && $v['origin_id'] > 0 && $v['type'] === 'icon') {
                    if (isset($this->WebApp[(int)$v['origin_id']])) {
                        $v['custom'] = $this->WebApp[(int)$v['origin_id']]['custom'];
                        $v['url'] = $this->WebApp[(int)$v['origin_id']]['url'];
                        $v['src'] = $this->WebApp[(int)$v['origin_id']]['src'];
                        $v['name'] = $this->WebApp[(int)$v['origin_id']]['name'];
                        $v['bgColor'] = $this->WebApp[(int)$v['origin_id']]['bgColor'];
                    }
                }
            }
        }
        return (array)$value;
    }
}
