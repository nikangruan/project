<?php
namespace app\admin\model;
use think\Model;
use think\Db;
/**
 * 
 */
class Admin extends Model
{
	
	protected $table = 'admin_users';
	
	public function getStatusAttr($value)
    {
        $status = [1=>'启用',0=>'禁用'];
        return $status[$value];
    }

    public function getIsAdminAttr($value)
    {
    	$isadmin = [1=> '是',0=> '否'];
    	return $isadmin[$value];
    }
}