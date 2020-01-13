<?php
namespace app\article\validate;
use think\Validate;
/**
 * 管理员验证
 */
class Article extends Validate
{
	protected $rule =   [
        'title'  => 'require',
    ];
    
    protected $message  =   [
        'title.require' => '文章标题不能为空',
    ];
    
    protected $scene = [
        'edit'  =>  ['title'],
        'add'  =>  ['title'],
    ];
	
}