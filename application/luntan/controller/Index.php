<?php
namespace app\luntan\controller;
use think\Controller;

class Index extends Controller
{
	 
	 
	//初始化函数
	public function _initialize()
    {
        parent::_initialize();
    }
	
	// 外部编号头
    private $key = 'IMG';
    
	/*
	类型列表
	*/
	public function blogType(){
		$blogType = array();
		$blogTypeModel = new \app\luntan\model\LuntanType();
		$blogTypeRows = $blogTypeModel->all(array('is_del'=>0,'is_show'=>1));
		if($blogTypeRows){
			foreach($blogTypeRows as $blogTypeRow){
                $blogType[$blogTypeRow['id']] = $blogTypeRow['content'];
            }
		}
		$this->assign('blogType',$blogType);
	}
	
	
    public function index()
    {
		
		$this->assign('user',session('help_user_info'));
		return $this->fetch('index');
    }
	
	public function articleList(){
		self::blogType();
		$returnArray = array();
		$lunTanContentModel = new \app\luntan\model\LuntanContent();
		$blogTypeModel = new \app\luntan\model\LuntanType();
		$luntanContentRows = $lunTanContentModel->where(array('is_del'=>0))->select();
		$info = array();
		if(!empty($luntanContentRows)){
			foreach($luntanContentRows as $row){
				if($row['type_id']){
					$typeRow = $blogTypeModel->get(array('id'=>$row['type_id']));
					$typeRow = $typeRow->toArray();
				}
				$info[] = array(
					'id' => $row['id'],
					'title' => $row['title'],
					'title_simple' => $row['title_simple'],
					'source' => $row['source'],
					'creTime' => $row['cre_time'],
					'browse' => $row['browse'],
					'isShow' => $row['is_show'],
					'typeContent' => $typeRow['content'],
				);
			}
			if($info){
				$returnArray = array(
					'code' => 1 ,
					'info' => '获取列表'
				);
			}else{
				$returnArray = array(
					'code' => 0,
					'info' => '获取失败'
				);
			}
		}
		$this->assign('contentList',$info);
		return $this->fetch('article_list');
	}
	
	public function article_add(){
		self::blogType();
		return $this->fetch('article_add');
	}	
	/**
	*删除微博操作
	*@id 微博的id
	**/
	public function delAction(){
		$returnArray = array();
			if(!empty($_POST['id'])){
				$lunTanContentModel = new \app\luntan\model\LuntanContent();
				$result = $lunTanContentModel->update(array('is_del'=>1),array('id'=>$_POST['id']));
				if($result){
					$result = $result->toArray();
					$returnArray = array(
						'code' => 1,
						'info' => '删除成功!'
					);
				}else{
					$returnArray = array(
						'code' => 0,
						'info' => '没有找到该信息'
					);
				}
			}else{
				$returnArray = array(
					'code' => 0,
					'info' => 'Post参数无效'
				);
			}
		 return $returnArray;
	}
	
	
	
	/**
	*下架微博操作
	*@id 微博的id
	**/
	public function editAction(){
		$returnArray = array();
			if(!empty($_POST['id'])){
				$lunTanContentModel = new \app\luntan\model\LuntanContent();
				if(!empty($_POST['isShow'])){
					if($_POST['isShow'] == 1){
						$result = $lunTanContentModel->update(array('is_show'=>0),array('id'=>$_POST['id']));
					}
					if($_POST['isShow'] == 2){
						$result = $lunTanContentModel->update(array('is_show'=>1),array('id'=>$_POST['id']));	
					}
				}else{
					$returnArray = array(
						'code' => 1,
						'info' => '必须带着微博的状态进来!!'
					);
				}
				if($result){
					$result = $result->toArray();
					$returnArray = array(
						'code' => 1,
						'info' => '修改成功!'
					);
				}else{
					$returnArray = array(
						'code' => 0,
						'info' => '没有找到该信息'
					);
				}
			}else{
				$returnArray = array(
					'code' => 0,
					'info' => 'Post参数无效'
				);
			}
		 return $returnArray;
	}
	
	
	/**
	*批量删除微博操作
	*@id 微博的id
	**/
	public function batchDelAction(){
		$returnArray = array();
		$lunTanContentModel = new \app\luntan\model\LuntanContent();
		var_dump($_POST['checked']);
		if($_POST['checked']){
				$result = $lunTanContentModel->update(array('is_del'=>1),array('id'=>$_POST['checked']));
				$returnArray = array(
						'code' => 1,
						'info' => '修改成功!'
				);
		}else{
			$returnArray = array(
				'code' => 0,
				'info' => '参数错误'
			);
		}
	}
	public function test(){
		$key = "IMG";
		var_dump($key.time(). rand(10000000,99999999));
	}
}