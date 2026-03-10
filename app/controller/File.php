<?php

namespace app\controller;
ini_set('max_execution_time', 300);

use app\BaseController;
use app\model\FileModel;

class File extends BaseController
{
    private $files = [];

    public function list(): \think\response\Json
    {
        $this->getAdmin();
        $limit = $this->request->post('limit', 15);
        $name = $this->request->post('search.path', false);
        $user_id = $this->request->post("search.user_id",false);
        $sql = [];
        if ($name) {
            $sql[] = ['mime_type|path', 'like', '%' . $name . '%'];
        }
        if($user_id){
            $sql["user_id"] = $user_id;
        }
        $list = FileModel::with("user")->where($sql);
        $list = $list->order('id', 'desc')->paginate($limit);
        return $this->success('ok', $list);
    }

    function del(): \think\response\Json
    {
        is_demo_mode(true);
        $this->getAdmin();
        $ids = $this->request->post('ids', []);
        $res = FileModel::where('id', 'in', $ids)->select()->toArray();
        foreach ($res as $k => $v) {
            $p = joinPath(public_path(), $v['path']);
            if (file_exists($p)) {
                @unlink($p);
            }
            FileModel::where('id', $v['id'])->delete();
        }
        return $this->success('删除成功');
    }

    private function countFilesInDirectory($directory): int
    {
        $fileCount = 0;
        // 获取目录中的文件和子目录
        $files = scandir($directory);
        foreach ($files as $file) {
            // 排除"."和".."
            if ($file != '.' && $file != '..') {
                $filePath = $directory . '/' . $file;
                // 如果是目录，则递归调用函数
                if (is_dir($filePath)) {
                    $fileCount += $this->countFilesInDirectory($filePath);
                } else {
                    // 如果是文件，则增加文件数量
                    $this->files[] = joinPath("/", $filePath);
                    $fileCount++;
                }
            }
        }

        return $fileCount;
    }

    function scanLocal(): \think\response\Json
    {
        is_demo_mode(true);
        $this->getAdmin();
        if (!is_dir(public_path("images"))) {
            return $this->success('扫描完成');
        }
        $this->countFilesInDirectory("images");
        $list = FileModel::limit(5000)->select()->toArray();
        foreach ($list as $key => $v) {
            $index = array_search(joinPath('/', $v['path']), $this->files);
            if ($index >= 0) {
                unset($this->files[$index]);
            }
        }
        $all = [];
        if (count($this->files) > 0) {
            foreach ($this->files as $key => $v) {
                $p = joinPath(public_path(), $v);
                $info = [];
                $info['path'] = $v;
                $info['user_id'] = null;
                $info['create_time'] = date('Y-m-d H:i:s');
                $info['size'] = filesize($p) ?? 0;
                $info['hash'] = hash_file("md5", $p);
                $info['mime_type'] = mime_content_type($p) ?? 'null';
                $all[] = $info;
            }
            $file = new FileModel();
            $file->saveAll($all);
        }
        return $this->success("扫描完成");
    }
}