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
   

}

