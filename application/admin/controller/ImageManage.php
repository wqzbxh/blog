<?php
namespace app\admin\controller;
use think\Controller;

class ImageManage extends Controller
{
		
	//初始化函数
	public function _initialize()
    {
      
    }
	
	/**
	*显示正在上传的图片
	*
	*/ 
	public function upImageTemp()
    {		
        $returnArray = array();
		$imageModel  = new \app\admin\model\ImageRecord();
		$indexModel  = new \app\admin\model\Index();
		$imageResult = $imageModel->upImageTemp();
	    if($imageResult['code'] == 1){
	        $returnArray = array(
	            'code' => 1,
	            'msg' => $indexModel::ERROR_CODE[1],
	            'data' => $imageResult['data'],
	        );
	    }elseif ($imageResult['code'] == 800001){
	        $returnArray = array(
	            'code' => 800001,
	            'msg' => $indexModel::ERROR_CODE[800001],
	            'data' => array(),
	        );
	    }elseif  ($imageResult['code'] == 800002){
	        $returnArray = array(
	            'code' => 800002,
	            'msg' => $indexModel::ERROR_CODE[800002],
	            'data' => array(),
	        );
	    }
	    
	   return json_encode($returnArray);
	   
    }	

	
} 