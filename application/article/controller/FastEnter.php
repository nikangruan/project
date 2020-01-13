<?php
namespace app\article\controller;
use think\Controller;
use think\Db;
use app\article\model\FastEnter as FastEnterModel;
/**
 * 快速入口设置
 */
class FastEnter extends Controller
{
	
	public function lst()
	{
		if (request()->isPost()) {
    		$data = input('post.');
    		$page = $data['page'];
    		$limit = $data['limit'];
    		$offset = ($page-1)*$limit;
    		$w = ' 1 ';
            
    		$m = new FastEnterModel();
    		$info = $m->where($w)->limit($offset,$limit)->order('id desc')->select();
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
			
			$validate = \think\Loader::validate('FastEnter');

			if (!$validate->scene('add')->check($data)) {
				return ['code'=>0,'msg'=>$validate->getError()];
			}
			$info = Db::name('fastenter')->where(['module_name'=>$data['module_name']])->find();

			if (!empty($info)) {
				return ['code'=>0,'msg'=>'该系统名称已存在'];
			}
				
			if (Db::name('fastenter')->insert($data) == false) {
				return ['code'=>0,'msg'=>'添加失败'];
			} else {
				return ['code'=>1,'msg'=>'添加成功'];
			}
		}
		return view();
	}

	public function edit()
	{
		if (request()->isPost()) {
			$data = input('post.');

			$validate = \think\Loader::validate('FastEnter');

			if (!$validate->scene('edit')->check($data)) {
				return ['code'=>0,'msg'=>$validate->getError()];
			}
			if (empty($data['pic_url'])) {
				
				$info = Db::name('fastenter')->where(['module_name'=>$data['module_name'],'link_url'=>$data['link_url']])->find();

				if (!empty($info)) {
					return ['code'=>0,'msg'=>'该数据信息没有做任何的修改~~'];
				}

				if (Db::name('fastenter')->where(['id'=>$data['id']])->update(['module_name'=>$data['module_name'],'link_url'=>$data['link_url']]) == false) {
					return ['code'=>0,'msg'=>'编辑失败'];
				} else {
					return ['code'=>1,'msg'=>'编辑成功'];
				}
			} else {

				$info = Db::name('fastenter')->where(['module_name'=>$data['module_name'],'link_url'=>$data['link_url'],'pic_url'=>$data['pic_url']])->find();
				if (!empty($info)) {
					return ['code'=>0,'msg'=>'该数据信息没有做任何的修改~~'];
				}

				if (Db::name('fastenter')->where(['id'=>$data['id']])->update(['module_name'=>$data['module_name'],'pic_url'=>$data['pic_url'],'link_url'=>$data['link_url']]) == false) {
					return ['code'=>0,'msg'=>'编辑失败'];
				} else {
					return ['code'=>1,'msg'=>'编辑成功'];
				}
			}
			
		}
		$id = input('get.id');
   		if (empty($id)) {
   			echo '<script>alert("非法操作");</script>';
   		}
   		$info = Db::name('fastenter')->where(['id'=>$id])->find();
   		
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
    		$m = new FastEnterModel();
    		foreach ($data['id'] as $key => $id) {

    			$info = $m->get($id);
    			if ($info->delete() === false) {
    				return ['code'=>0,'msg'=>'删除失败'];
    			}
    		}

    	}
	}

	public function insertImg()
	{
		if($this->request->isPost()){
	        $res['code']=1;
	        $dir = ROOT_PATH.'public'.DS.'upload'.DS.'fast'.DS;
	        $file =  $this->request->file('file');
	        $info = $file->move($dir);
	        if($info){
	            $res['name'] = $info->getFilename();
	            $res['filepath'] = $dir.$info->getSaveName();
	            //打开
	            $image = \think\Image::open($res['filepath']);

	            $image->thumb(200,200,1)->save($res['filepath']);
	            $res['path'] = '../../../upload/fast/'.$info->getSaveName();
	        }else{
	           $res['code'] = 0;
	           $res['msg'] = '上传失败！'.$file->getError();
	        }
	        return $res;
	    }
        
	}

	public function IsChecked()
	{
		if (request()->isPost()) {
    		$data = input('post.');
    		if (empty($data['id']) && empty($data['value'])) {
    			return ['code'=>0,'msg'=>'非法参数'];
    		}
    		if ($data['value'] == '是') {
    			if(Db::name('fastenter')->where(['id'=>$data['id']])->update(['is_check'=>0]) === false) {
    				return ['code'=>0,'msg'=>'操作失败'];
    			}
    		} else {
    			if(Db::name('fastenter')->where(['id'=>$data['id']])->update(['is_check'=>1]) === false) {
    				return ['code'=>0,'msg'=>'操作失败'];
    			}
    		}
    	}
	}
}