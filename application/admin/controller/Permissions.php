<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use app\admin\model\Permissions as PermissionsModel;
/**
 * 权限管理
 */
class Permissions extends Controller
{
	
	public function lst()
	{
		if (request()->isPost()) {
			$data = input('post.');
    		$page = $data['page'];
    		$limit = $data['limit'];
    		$offset = ($page-1)*$limit;
    		
    		$m = new PermissionsModel();
    		$info = $m->limit($offset,$limit)->select();
    		$total = $m->count();
    		$array = [];
    		$array['status'] = 0;
    		$array['message'] = '';
    		$array['data'] = $info;
    		$array['total'] = $total;
    		return json($array);
		}
		return view();
	}

	public function add()
	{
		if (request()->isPost()) {
			$data = input('post.');
			if (empty($data['per_name'])) {
				return ['code'=>0,'msg'=>'非法参数'];
			}

			$validate = \think\Loader::validate('Permissions');
			if (!$validate->scene('add')->check($data)) {
				return ['code'=>0,'msg'=>$validate->getError()];
			}
			$info = Db::name('permissions')->where(['per_name'=>$data['per_name']])->find();
			if (!empty($info)) {
				return ['code'=>0,'msg'=>'该分类名称已存在'];
			}
			if (Db::name('Permissions')->insert($data)) {
				return ['code'=>1,'msg'=>'添加成功'];
			} else {
				return ['code'=>0,'msg'=>'添加失败'];
			}
		}
		return view();
	}

	public function edit()
	{
		if (request()->isPost()) {
			$data = input('post.');
   			$validate = \think\Loader::validate('Permissions');
   			if (!$validate->scene('edit')->check($data)) {
   				return ['code'=>0,'msg'=>$validate->getError()];
   			}
   			// return $data;
   			$info = Db::name('permissions')->where(['per_name'=>$data['per_name']])->find();
   			if (!empty($info)) {
   				return ['code'=>0,'msg'=>'当前数据信息没有做任何的修改'];
   			}
   			if (Db::name('permissions')->where(['per_id'=>$data['per_id']])->update(['per_name'=>$data['per_name'],'per_desc'=>$data['per_desc']]) === false) {
   				return ['code'=>0,'msg'=>'编辑失败'];
   			}
		}
		$id = input('get.id');
   		if (empty($id)) {
   			echo '<script>alert("非法操作");</script>';
   		}
   		$info = Db::name('permissions')->where(['per_id'=>$id])->find();
   		
   		$this->assign('info',$info);
		return view();
	}

	public function del()
	{
		if (request()->isPost()) {
    		$data = input('post.');

    		if (empty($data['id'])) {
    			return ['code'=>0,'msg'=>'非法参数'];
    		}
    		$m = new PermissionsModel();
    		foreach ($data['id'] as $key => $id) {

    			$info = $m->get($id);
    			if ($info->delete() === false) {
    				return ['code'=>0,'msg'=>'删除失败'];
    			}
    		}

    	}
	}
}