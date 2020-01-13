<?php
namespace app\article\model;
use think\Model;
use think\Db;
/**
 * 
 */
class Article extends Model
{
	
	protected $table = 'articles';
	public function getTypeAttr($value)
    {
        $type = [1=>'新闻',2=>'公告',3=>'轮播图'];
        return $type[$value];
    }

    public function getIsReleaseAttr($value)
    {
        $isrelease = [1=>'是',0=>'否'];
        return $isrelease[$value];
    }
}