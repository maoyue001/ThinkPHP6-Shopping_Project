<?php

namespace app\common\model\mysql;

use think\Model;

class Specs extends BaseModel
{
    /**
     * 获取规格数据
     * @param string $field
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNormalSpecs($field = "*")
    {
        $where = [
            "status" => config("status.mysql.table_normal")
        ];

        return $this->where($where)->field($field)->select();
    }


    /**
     * 分页查询 (默认每页显示 10 条)
     * @param int $num
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function getLists($num = 10)
    {
        // 排序方式
        $order = [
            "id" => "by",
        ];

        // <> 表示不等，
        // where ：数据状态码不能为删除状态。
        // paginate() 框架自带方法，自动分页。传入每页显示多少数据的变量
        return $this->where("status", "<>", config("status.mysql.table_delete"))
            ->order($order)
            ->paginate($num);
    }


    /**
     * 根据 name 字段查询数据
     * @param $name
     * @return array|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getSpecsByname($name)
    {
        $where = [
            "name" => $name,
        ];

        return $this->where("status", "<>", config("status.mysql.table_delete"))
            ->where($where)
            ->find();
    }

}