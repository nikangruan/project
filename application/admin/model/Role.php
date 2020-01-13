<?php
namespace app\admin\model;
use think\Model;
use think\Db;
/**
 * 角色模型
 */
class Role extends Model
{
	
	protected $table = 'roles';
	
	public function getStatusAttr($value)
    {
        $status = [1=>'启用',0=>'禁用'];
        return $status[$value];
    }
    
}