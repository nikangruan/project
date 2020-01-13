<?php
namespace app\article\model;
use think\Model;
use think\Db;

class FastEnter extends Model
{
	
	protected $table = 'fastenter';
	public function getIsCheckAttr($value)
    {
    	$ischeck = [1=> '是',0=> '否'];
    	return $ischeck[$value];
    }
}