<?php
namespace app\article\controller;
use think\Controller;
use think\Db;
use app\article\model\Article as ArticleModel;
/**
 * 文章管理
 */
class Article extends Controller
{
	
	public function lst()
	{
		if (request()->isPost()) {
    		$data = input('post.');
    		$page = $data['page'];
    		$limit = $data['limit'];
    		$offset = ($page-1)*$limit;
    		$w = ' 1 ';
            
    		$m = new ArticleModel();
    		$info = $m->where($w)->limit($offset,$limit)->order('id desc')->select();
            foreach ($info as $key => $value) {
                if (!empty($value['thumb'])) {
                    $info[$key]['thumb'] = explode(',',$value['thumb']);
                }
            }
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
            
			$validate =  \think\Loader::validate('Article');
			if (!$validate->scene('add')->check($data)) {
				return ['code'=>0,'msg'=>$validate->getError()];
			}
            if (isset($data['contents'])) {
               
                if ($data['contents'] == 'undefined') {
                    $data['contents'] = '';
                }
            }
            if (!empty($data['thumb'])) {
                $data['thumb'] = implode(',', $data['thumb']);
            }
            // return $data;
			$info = Db::name('articles')->where(['title'=>$data['title']])->find();
			if (!empty($info)) {
				return ['code'=>0,'msg'=>'该文章辩题已存在，请修改标题~~'];
			}

			
			if (Db::name('articles')->insert($data)) {
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
			
			$validate = \think\Loader::validate('Article');
			if (!$validate->scene('edit')->check($data)) {
                return ['code'=>0,'msg'=>$validate->getError()];
            }
            if (isset($data['contents'])) {
               
                if ($data['contents'] == 'undefined') {
                    $data['contents'] = '';
                }
            } else {
                $data['contents'] = '';
            }
            if (!empty($data['thumb'])) {
                $data['thumb'] = implode(',', $data['thumb']);
            }
            $arr = [
            	'title'=>$data['title'],
            	'keywords'=> $data['keywords'],
            	'author'=>$data['author'],
            	'contents'=> $data['contents'],
            	'description'=>$data['description'],
            	'type'=> $data['type'],
            	'url'=>$data['url'],
                'thumb'=>$data['thumb']
            ];
            $info = Db::name('articles')->where($arr)->find();
            if (!empty($info)) {
                return ['code'=>0,'msg'=>'当前数据信息没有做任何的修改'];
            }
          
            if (Db::name('articles')->where(['id'=>$data['id']])->update($arr) === false) {
                return ['code'=>0,'msg'=>'编辑失败'];
            }

		}
		$id = input('get.id');
        if (empty($id)) {
            echo '<script>alert("非法操作");</script>';
        }
        $info = Db::name('articles')->where(['id'=>$id])->find();
        if (!empty($info['thumb'])) {
            $pic = explode(',', $info['thumb']);
            $this->assign('pic',$pic);
        }
        $this->assign('info',$info);
		return view();
	}
	public function del()
	{
		if (request()->isPost()) {
			$data = input('post.');
			// return $data;
			if (empty($data['id'])) {
                return ['code'=>0,'msg'=>'非法参数'];
            }
            
            $m = new ArticleModel();
            foreach ($data['id'] as $key => $id) {

                $info = $m->get($id);
                if ($info->delete() === false) {
                    return ['code'=>0,'msg'=>'删除失败'];
                }
            }
		}
	}
	
	public function isrelased()
	{
		if (request()->isPost()) {
    		$data = input('post.');
    		if (empty($data['id']) && empty($data['value'])) {
    			return ['code'=>0,'msg'=>'非法参数'];
    		}
    		$time = date('Y-m-d');
    		if ($data['value'] == '是') {
    			if(Db::name('articles')->where(['id'=>$data['id']])->update(['is_release'=>0,'time'=>$time]) === false) {
    				return ['code'=>0,'msg'=>'操作失败'];
    			}
    		} else {
    			if(Db::name('articles')->where(['id'=>$data['id']])->update(['is_release'=>1,'time'=>$time]) === false) {
    				return ['code'=>0,'msg'=>'操作失败'];
    			}
    		}
    	}
	}

    public function uploadpic(){
        if($this->request->isPost()){
            $dir =  ROOT_PATH.'public' .DS.'upload'.DS.'article'.DS;
            $res['code']=1;
            $res['msg'] = '上传成功！';
            $file = $this->request->file('file');
            $info = $file->move($dir);
            if($info){
                $res['name'] = $info->getFilename();
                $res['filepath'] = $dir.$info->getSaveName();
                //打开
                $image = \think\Image::open($res['filepath']);
                
                $image->thumb(410,404,1)->save($res['filepath']);
                
                $res['path'] = 'upload/article/'.$info->getSaveName();
                
            }else{
                $res['code'] = 0;
                $res['msg'] = '上传失败！'.$file->getError();
            }
            return $res;
        }
    } 

    public function clearpicture(){
        if(request()->isPost()){
            
            $data = input('post.');
            if (empty($data['id'])){
                return ['code'=>0,'msg'=>'非法参数'];
            }

            $result = Db::table('articles')->where('id', $data['id'])->find();
            if (empty($result['thumb'])) {
                return ['code'=>'非法参数'];
            } else {
                $pic = explode(',', $result['thumb']);
                
                $key = array_search($data['name'], $pic);
                if ($key !== false) {
                  array_splice($pic, $key, 1);
                }
                $thumb = join(',',$pic);
                $res = Db::name('articles')->where(['id'=>$data['id']])->update(['thumb'=>$thumb]);
                if ($res !== false) {
                    $path = ROOT_PATH.'public/'.$data['name'];
                    unlink($path);
                    return ['code'=>1,'msg'=>'删除成功'];
                } else {
                    return ['code'=>0,'msg'=>'删除失败'];
                }
                
            }
           
        }
    }
}
