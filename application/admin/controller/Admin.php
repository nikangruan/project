<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use app\admin\model\Admin as AdminModel;
/**
 * 用户管理
 */
class Admin extends Controller
{
	//用户列表
    public function lst()
    {
    	if (request()->isPost()) {
    		$data = input('post.');
    		$page = $data['page'];
    		$limit = $data['limit'];
    		$offset = ($page-1)*$limit;
    		$w = ' 1 ';
            if (!empty($data['username'])) {
                $w .= ' and username = \''.$data['username'].'\'';
            }
    		$m = new AdminModel();
    		$info = $m->where($w)->limit($offset,$limit)->select();
    		$total = $m->where($w)->count();
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
    		if (!empty($data['password'])) {
    			$validate =  \think\Loader::validate('Admin');
    			if (!$validate->scene('add')->check($data)) {
    				return ['code'=>0,'msg'=>$validate->getError()];
    			}

    			$info = Db::name('admin_users')->where(['username'=>$data['username']])->find();
    			if (!empty($info)) {
    				return ['code'=>0,'msg'=>'该用户已存在！！'];
    			}
    			$data['password'] = md5($data['password']);
    			if (Db::name('admin_users')->insert($data)) {
    				return ['code'=>1,'msg'=>'添加成功'];
    			} else {
    				return ['code'=>0,'msg'=>'添加失败'];
    			}
    		}
    	}
    	return view();
    }

    public function switchStatus()
    {
    	if (request()->isPost()) {
    		$data = input('post.');
    		if (empty($data['id']) && empty($data['value'])) {
    			return ['code'=>0,'msg'=>'非法参数'];
    		}
    		if ($data['value'] == '启用') {
    			if(Db::name('admin_users')->where(['user_id'=>$data['id']])->update(['status'=>0]) === false) {
    				return ['code'=>0,'msg'=>'禁用失败'];
    			}
    		} else {
    			if(Db::name('admin_users')->where(['user_id'=>$data['id']])->update(['status'=>1]) === false) {
    				return ['code'=>0,'msg'=>'启用失败'];
    			}
    		}
    	}
    }

    public function edit()
    {
    	if (request()->isPost()) {
   			$data = input('post.');
   			$validate = \think\Loader::validate('Admin');
   			if (!$validate->scene('edit')->check($data)) {
   				return ['code'=>0,'msg'=>$validate->getError()];
   			}
   			// return $data;
   			$info = Db::name('admin_users')->where(['username'=>$data['username'],'phone'=>$data['phone'],'email'=>$data['email'],'is_admin'=>$data['is_admin']])->find();
   			if (!empty($info)) {
   				return ['code'=>0,'msg'=>'当前数据信息没有做任何的修改'];
   			}
   			if (Db::name('admin_users')->where(['user_id'=>$data['user_id']])->update(['username'=>$data['username'],'phone'=>$data['phone'],'email'=>$data['email'],'is_admin'=>$data['is_admin']]) === false) {
   				return ['code'=>0,'msg'=>'编辑失败'];
   			}
   			
   		}
    	$id = input('get.id');
   		if (empty($id)) {
   			echo '<script>alert("非法操作");</script>';
   		}
   		$info = Db::name('admin_users')->where(['user_id'=>$id])->find();
   		
   		$this->assign('info',$info);

    	return view();
    }

    //删除/批量删除
    public function del()
    {
    	if (request()->isPost()) {
    		$data = input('post.');

    		if (empty($data['id'])) {
    			return ['code'=>0,'msg'=>'非法参数'];
    		}
    		$m = new AdminModel();
    		foreach ($data['id'] as $key => $id) {

    			$info = $m->get($id);
    			if ($info->delete() === false) {
    				return ['code'=>0,'msg'=>'删除失败'];
    			}
    		}

    	}
    }
}
