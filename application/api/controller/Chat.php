<?php 

namespace app\api\controller;
use think\Controller;
use think\Db;
use think\Request;
class Chat extends Controller
{	
	//接收ajax传过来的值 准备入库操作
    public function save_message(){
    
    	if (Request::instance()->isAjax()){
			$data =  input('post.');
			
			$data['fromname'] = $this->getName($data['fromid']);
			$data['toname'] = $this->getName($data['toid']);
			$data['content'] = $data['msg'];
			unset($data['type']);
			unset($data['msg']);
			unset($data['online']);
			Db::name('communication')->insert($data);
    	}
	}


    //传id获取用户名
    public function getName($uid){

    	$result = Db::name('user')->where('id',$uid)->field('nickname')->find();

    	return $result['nickname'];
    }

    //传id获取用户名头像
    public function getImage(){

    	$fromid = input('post.fromid');
    	$toid = input('post.toid');
    	$fromInfo = Db::name('user')->where('id',$fromid)->field('headimgurl')->find();

    	$toInfo = Db::name('user')->where('id',$toid)->field('headimgurl')->find();

    	return ['fromimage'=>$fromInfo['headimgurl'],'toimage'=>$toInfo['headimgurl']];
    }

    //传id获取用户名
    public function get_name(){

    	$fromid = input('post.fromid');
    	$toid = input('post.toid');
    	$fromInfo = Db::name('user')->where('id',$fromid)->field('nickname')->find();

    	$toInfo = Db::name('user')->where('id',$toid)->field('nickname')->find();

    	return ['fromnickname'=>$fromInfo['nickname'],'tonickname'=>$toInfo['nickname']];
    }
   
   //查询聊天内容
    public function get_message(){

    	$fromid = input('post.fromid');
    	$toid = input('post.toid');

    	$count = Db::name('communication')->where('(fromid=:fromid and toid=:toid) || (fromid=:toid1 and toid=:fromid1)',['fromid'=>$fromid,'toid'=>$toid,'fromid1'=>$fromid,'toid1'=>$toid])->order('id')->count();

    	if($count <= 10){
    		$result = Db::name('communication')->where('(fromid=:fromid and toid=:toid) || (fromid=:toid1 and toid=:fromid1)',['fromid'=>$fromid,'toid'=>$toid,'fromid1'=>$fromid,'toid1'=>$toid])->order('id')->select();
    	}else{
    		$result = Db::name('communication')->where('(fromid=:fromid and toid=:toid) || (fromid=:toid1 and toid=:fromid1)',['fromid'=>$fromid,'toid'=>$toid,'fromid1'=>$fromid,'toid1'=>$toid])->order('id')->limit($count-10,10)->select();
    	}
    	return $result;

    }
    //上传图片
    public function uploadimg(){

    	$file = $_FILES['file'];
    	$fromId = input('post.fromid');
    	$toId = input('post.toid');
    	$online = input('post.online');
    	//获取后缀名
    	$suffix = strtolower(strrchr($file['name'],'.'));

    	$type = ['.jpg','jpeg','.gif','.png'];

    	if(!in_array($suffix,$type)){
    		return ['status'=>'image type error'];
    	}
    	//生成随机文件名
    	$filename = uniqid('chat_img_',false);
    	//路径
    	$uploadpath = ROOT_PATH.'public\uploads\\';
    	
    	//拼接完成文件名
    	$file_up = $uploadpath.$filename.$suffix;

    	$result = move_uploaded_file($file['tmp_name'],$file_up);

    	if($result){
    		$name = $filename.$suffix;
    		$data = [
    			'content'=>$name,
    			'fromname'=>$this->getName($fromId),
    			'toname' =>$this->getName($toId),
    			'fromid'=>$fromId,
    			'toid'=>$toId,
    			'time'=>time(),
    			'isread'=>$online,
    			'type'=>2
    		];

    		$message_id = Db::name('communication')->insert($data);

    		if($message_id){
    			return ['status'=>'ok','name'=>$name];
    		}else{
    			return ['status'=>'false'];

    		}

    	}else{

    	}

    }

}

