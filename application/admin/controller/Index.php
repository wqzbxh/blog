<?php
namespace app\admin\controller;
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
		$blogTypeModel = new \app\admin\model\LuntanType();
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
		$lunTanContentModel = new \app\admin\model\LuntanContent();
		$blogTypeModel = new \app\admin\model\LuntanType();
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
	

	/**
	*删除微博操作
	*@id 微博的id
	**/
	public function delAction(){
		$returnArray = array();
			if(!empty($_POST['id'])){
				$lunTanContentModel = new \app\admin\model\LuntanContent();
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
				$lunTanContentModel = new \app\admin\model\LuntanContent();
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
		$lunTanContentModel = new \app\admin\model\LuntanContent();
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
	
	
	/**
	 * 下载redis
	 */
	public function downRedis(){
           header("Content-type:text/html;charset=utf-8");   
        //$file_path="testMe.txt";  
//        //用以解决中文不能显示出来的问题
//        //$file_name=iconv("utf-8","gb2312",$file_name);
//        //$file_sub_path=$_SERVER['DOCUMENT_ROOT']."marcofly/phpstudy/down/down/";
//        //$file_path=$file_sub_path.$file_name;
//        //首先要判断给定的文件存在与否
//        $file_path = 'public' . DS . 'luntan' . DS . 'down' .DS . redis-3.2.9.tar.gz‘
//        if(!file_exists($file_path)){
//            echo "没有该文件文件";
//            return ;
//        }
//        $fp=fopen($file_path,"r");
//        $file_size=filesize($file_path);
//        //下载文件需要用到的头
//        Header("Content-type: application/octet-stream");
//        Header("Accept-Ranges: bytes");
//        Header("Accept-Length:".$file_size);
//        Header("Content-Disposition: attachment; filename=".$file_path);
        $buffer=1024;   
        $file_count=0;   
        //向浏览器返回数据   
//        while(!feof($fp) && $file_count<$file_size){
//            $file_con=fread($fp,$buffer);
//            $file_count+=$buffer;
//            echo $file_con;
//        }
//        fclose($fp);
	}
}