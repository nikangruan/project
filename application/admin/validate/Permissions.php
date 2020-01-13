<?php
namespace app\admin\validate;
use think\Validate;
/**
 * 管理员验证
 */
class Permissions extends Validate
{
	protected $rule =   [
        'per_name'  => 'require|max:100',
        
    ];
    
    protected $message  =   [
        'per_name.require' => '分类名称必须',
        'per_name.max'     => '分类名称最多不能超过100个字符',
    ];
    
    protected $scene = [
        'edit'  =>  ['per_name'],
        'add'  =>  ['per_name'],
    ];
	
}