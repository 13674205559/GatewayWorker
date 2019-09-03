<?php
namespace app\index\controller;

use think\Controller;

class Index extends Controller
{
    public function index()
    {
    	
       $fromId = input('param.formid');
       $toId = input('param.toid');
       $this->assign('fromId',$fromId);
       $this->assign('toId',$toId);

       return $this->fetch();
    }



    public function info(){
    	return $this->fetch('index/info');
    }


    
     public function member(){
    	return $this->fetch('member/member');
    }
}
