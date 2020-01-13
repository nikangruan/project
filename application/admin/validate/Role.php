<?php
namespace app\admin\validate;
use think\Validate;
/**
 * 管理员验证
 */
class Role extends Validate
{
	protected $rule =   [
        'role_name'  => 'require|max:100',
        
    ];
    
    protected $message  =   [
        'role_name.require' => '角色名称必须',
        'role_name.max'     => '角色名称最多不能超过100个字符',
    ];
    
    protected $scene = [
        'edit'  =>  ['role_name'],
        'add'  =>  ['role_name'],
    ];
	
}