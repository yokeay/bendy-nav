<?php
declare (strict_types=1);

namespace app\command;

use mysqli;
use think\console\Command;
use think\console\Input;

use think\console\Output;

class repair extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('repair')
            ->setDescription('修复数据库差异');
    }

    protected function execute(Input $input, Output $output)
    {
        self::repair();
        print_r("\033[1;31m数据库差异信息修复完毕\033[0m\n\r\033[1;42m请尝试刷新网站检查是否正常\033[0m\n");
    }

    public static function repair()
    {
        //默认的一些基础数据
        $sqlFile = joinPath(root_path(), 'install.sql');
        $sql_file_content = file_get_contents($sqlFile);
        // 解析SQL文件内容并执行
        $sql_statements = explode(';', trim($sql_file_content));
        try {
            $conn = new mysqli(env('database.hostname'), env('database.username'), env('database.password'), env('database.database'), (int)env('database.hostport', 3306));
        } catch (\Exception $exception) {
            print_r("数据库连接失败咯,请正确配置数据库\n");
            exit();
        }
        foreach ($sql_statements as $sql_statement) {
            if (!empty($sql_statement)) {
                try {
                    $conn->query($sql_statement);
                } catch (\Exception $exception) {
                    //不用管
                }
            }
        }
    }

}
