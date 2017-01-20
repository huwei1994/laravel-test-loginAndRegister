<?php
namespace App\Model\User;
//use Illuminate\Database\Eloquent\Model;
use DB;

/**
 * Created by PhpStorm.
 * User: huwei
 * Date: 2017/1/19
 * Time: 20:33
 */
class UserModel
{
    protected $fileds = [
        'id'=>'id',
        'name'=>'name',
        'email'=>'email',
        'password'=>'password',
        'token'=>'token',
        'token_at'=>'token_at',
        'created_at'=>'created_at',
        'modified_at'=>'modified_at',
        'deleted_at'=>'deleted_at',
    ];

    /**
     * 检查所传字段是否正确
     */
    public function check($options)
    {
        $_option = [];
        foreach($options as $k=>$option)
        {
            if(!empty($option) && in_array($k,$this->fileds))
            {
                $_option[$k] = $option;
            }
        }
        return $_option;
    }

    /**
     * 注册用户
     */
    public function insert($option)
    {
        $data = $this->check($option);
        if(count($data) !== count($option))
        {
            return false;
        }

        $db = DB::table('user');
        $id = $db->insertGetId($data);
        $where = [
            ['id','=',$id]
        ];
        $res = $this->get($where);
        if($res)
        {
            return $res;
        }
        return false;
    }
    
    /**
     * 根据登录信息查找一条记录
     */
    public function get($wheres)
    {
        $db = DB::table('user');

        foreach($wheres as $k=>$where)
        {
            if(count($where) == 3)
            {
                $db->where($where[0],$where[1],$where[2]);
            }
        }
        return $db->get();
    }

    /**
     * 修改指定记录字段
     */
    public function update($option,$wheres)
    {
        $data = $this->check($option);
        if(count($data) !== count($option))
        {
            return false;
        }

        $db = DB::table('user');
        foreach($wheres as $k=>$where)
        {
            if(count($where) == 3)
            {
                $db->where($where[0],$where[1],$where[2]);
            }
        }
        $db->update($data);
        $result = $this->get($where);
        return $result;
    }
}