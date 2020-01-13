<?php
namespace app\admin\validate;
use think\Validate;
/**
 * 管理员验证
 */
class Admin extends Validate
{
	protected $rule =   [
        'username'  => 'require|max:25',
        'password'   => 'require|length:6,16',
        'email' => 'email',   
        'phone'=> 'require', 
    ];
    
    protected $message  =   [
        'username.require' => '名称必须',
        'username.max'     => '名称最多不能超过25个字符',
        'password.require'   => '密码不能为空',
        'password.length'  => '密码必须6到16位',
        'email'        => '邮箱格式错误',  
        'phone'=> '电话不能为空',  
    ];
    
    protected $scene = [
        'edit'  =>  ['username','phone','email'],
        'add'  =>  ['username','password','email','phone'],
    ];
	
}