<?php

namespace app\common\model\mysql;

use think\Model;

class Category extends BaseModel
{
    /**
     * 根据分类名查询数据库
     * @param $name // 分类名
     * @return array|bool|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getCategoryByname($name)
    {
        if (empty($name)) {
            return false;
        }
        $where = [
            "name " => $name,
        ];
        return $this->where("status", "<>", config("status.mysql.table_delete"))
            ->where($where)
            ->find();
    }

    /**
     * 查询所有分类，默认获取所有属性
     * @param string $field // 查询条件
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNormalCategorys($field = "*")
    {
        // 获取状态码为正常的数据
        $where = [
            "status" => config("status.mysql.table_normal"),
        ];
        // 设置排序方法：优先按照 listorder 降序排序，然后再按照 id 来升序排序
        $order = [
            "listorder" => "desc",
            "id" => "by",
        ];
        return $this->where($where)
            ->field($field)
            ->order($order)
            ->select();
    }

    /**
     * 分页查询分类（默认每页十条数据）
     * @param $where    // 查询条件
     * @param int $num  // 每页显示数量，默认10
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function getLists($where, $num = 10)
    {
        // 设置排序方法：优先按照 listorder 降序排序，然后再按照 id 来升序排序
        $order = [
            "listorder" => "desc",
            "id" => "by",
        ];

        // <> 表示不等，
        // 第二个 where ：当传入 pid 查询子分类时使用
        // paginate() 框架自带方法，自动分页。传入每页显示多少数据的变量
        return $this->where("status", "<>", config("status.mysql.table_delete"))
            ->where($where)
            ->order($order)
            ->paginate($num);
    }


    /**
     * 查询父分类有多少个子分类
     * @param $condition    // 传入包含 pid 的数组
     * @return mixed
     */
    public function getChildCountInPids($condition)
    {
        // 组装 where , 将列表页面所需的数据的 id 传入 where 条件。
        $where[] = ["pid", "in", $condition['pid']];
        // 状态不能为删除状态
        $where[] = ["status", "<>", config("status.mysql.table_delete")];
        // 组装 sql
        return $this->where($where)
            ->field(["pid", "count(*) as count"])
            ->group("pid")
            ->select();
    }


    /**
     * 使用递归查询分类有多少父类
     * @param $id   // 要查询的分类 id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getTree($id)
    {
        // 设置静态变量，递归调用不会重置变量
        static $result = [];
        // 所需要的字段
        $field = [
            "id" => 'id',
            "name" => 'name',
            "pid" => 'pid'
        ];

        $info = $this->field($field)->find($id);

        $result[] = $info;
        // 子分类的 pid 必不为 0, pid 为 0 的必是最上层分类
        if ($info['pid'] > 0) {
            // 当判断不是最上级分类时进行递归操作
            $this->getTree($info['pid']);
            return $result;
        }
        return $result;
    }

    /**
     * 根据 pid 查询数据库
     * @param $pid  // 要查询的 pid
     * @param $field    // 要查询的字段
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNormalByPid($pid, $field)
    {
        // pid 为可选参数。可为 0
        $where = [
            "pid" => $pid,
            // 状态码必须为配置文件中配置的 正常 属性
            "status" => config("status.mysql.table_normal")
        ];

        $order = [
            "listorder" => "desc",
            "id" => "by",
        ];

        return $this->where($where)
            ->field($field)
            ->order($order)
            ->select();
    }





}