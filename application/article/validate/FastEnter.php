<?php
namespace app\article\validate;
use think\Validate;
/**
 * 快速入口验证规则
 */
class FastEnter extends Validate
{
	
	protected $rule =   [
        'module_name'  => 'require',
        'link_url'   => 'require',
        'pic_url'   => 'require',
       
    ];
    
    protected $message  =   [
        'module_name.require' => '系统名称必须',
        'link_url.require'     => '链接地址不能为空',
        'pic_url.require'     => '上传图标不能为空',
    ];
    
    protected $scene = [
        'edit'  =>  ['module_name','link_url'],
        'add'  =>  ['module_name','link_url','pic_url'],
    ];
}