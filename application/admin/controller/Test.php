<?php
namespace app\admin\controller;
use think\Controller;

class Test extends Controller
{
		
	//初始化函数
	public function _initialize()
    {
      
    }
	
	/**
	*登录
	*
	*/ 
	public function test()
    {		
		return $this->fetch('test/test');
    }	
	public function kuayu()
    {		
		return $this->fetch('kuayu');
    }		
	public function index()
    {		
		return $this->fetch('index');
    }	
	
} 