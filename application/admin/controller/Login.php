<?php
namespace app\admin\controller;
use think\Controller;

class Login extends Controller
{
	
    
    protected $userId = '';
    
	/**
	*登录
	*
	*/ 
	public function _initialize()
    {
            $this->companyId =session('help_user_info')['id'];
    }
	
	public function login()
    {
        if(session('help_user_info')){
            $this->redirect('index/index');
        }else{
            return $this->fetch('login');
        }
    }	
	/**
	/**
	*登录
	*
	*/ 
	public function index()
    {
		return $this->fetch('index/index');
    }	
	
	/**
	*
	*
	*/
	public function loginremember(){
		$username=trim($_POST['username']);
		$password=md5(trim($_POST['password']));
		$ref_url=$_GET['req_url'];
		$remember=$_POST['remember'];//是否自动登录标示
		$err_msg='';
		if($username==''||$password==''){
		 $err_msg="用户名和密码都不能为空";
		}else{
		 $row=getUserInfo($username,$password);
		 if(empty($row)){
		  $err_msg="用户名和密码都不正确";
		 }else{
		  $_SESSION['user_info']=$row;
		  
		  if(!empty($remember)){//如果用户选择了，记录登录状态就把用户名和加了密的密码放到cookie里面
		   setcookie("username",$username,time()+3600*24*365);
		   setcookie("password",$password,time()+3600*24*365);
		  }
		  
		  
		  if(strpos($ref_url,"login.php")===false){
		   header("location:".$ref_url);
		  }else{
			header("location:main_user.php");
		  }
		 }
		}
	}
	
	/**
	*登录
	*
	*/ 
	public function loginAction()
    {
		$returnArray = array();
		if(empty($_POST)==false){
			if(empty($_POST['telephone'])==false){
				$telephone = $_POST['telephone'];
			}else{
				$returnArray = array(
					'code' => 0,
					'info' => '电话不能为空！',
				);
			}
			if(empty($_POST['password']) == false){
				$password = md5($_POST['password']);
			}else{
				$returnArray = array(
					'code' => 0,
					'info' => '密码不能为空！'
				);
			}
			
			if(empty($returnArray)){
				$userModel = new \app\admin\model\LuntanUser;
				$userRow = $userModel->get(array('telephone'=>$telephone,'password'=>$password));
				if($userRow){
				    $userRow =  $userRow->toArray();
					if($userRow['is_del'] == 0){
					    $thisTime = date('Y-m-s H:i:s');
					    $userModel->where(array("telephone"=>$telephone))->update(array('this_time' =>$thisTime));
					    session('help_user_info',$userRow);
						$returnArray = array(
							'code' => 1,
							'info' => '登录成功！'
						);
                    }else{
						$returnArray = array(
							'code' => 0,
							'info' => '账号已被停止'
						);
					}
				}else{
					$returnArray = array(
						'code' => 0,
						'info' => '密码错误！'
					);
				}
			}
		}
		return $returnArray;
    }
	
	
	
    public function register()
    {
		return $this->fetch('register');
    }
	
	/**
	*验证手机号是否存在
	*
	*/
	public function checkPhone(){
		$returnArray = array();
		if(!empty($_POST['telephone'])){
			$telephone = $_POST['telephone'];
			$userModel = new \app\admin\model\LuntanUser();
			$userCount = $userModel->where(array('telephone'=>$telephone))->count();
			if(!empty($userCount)){
				$returnArray = array(
					'code' => 1,
					'info' => '手机号已存在',
 				);
			}else{
				$returnArray = array(
					'code' => 0,
					'info' => '手机号可以使用',
 				);
			}
		}else{
			$returnArray = array(
				'code' => 0,
				'info' => '参数错误',
			);			
		}
	return $returnArray;
	}
	/**
	*用户注册
	*nickname,tencentqq,telephone,password,
	*/
	public function registerAction(){
		$returnArray = array();
		$userModel = new \app\admin\model\LuntanUser();
		if(empty($_POST)){
			$returnArray = array(
				'code' => 0,
				'info' => '参数错误'
			);
		}else{
			if(empty($_POST['nickname']) == false){
				$data['nickname'] = $_POST['nickname'];
			}else{
				$returnArray = array(
					'code' => 0,
					'info' => '昵称不能为空'
				);
			};
			if(empty($_POST['tencentqq']) == false){
				$data['qq'] = $_POST['tencentqq'];
			}else{
				$returnArray = array(
					'code' => 0,
					'info' => 'QQ不能为空'
				);
			};
			if(empty($_POST['telephone']) == false){
				$data['telephone'] = $_POST['telephone'];
			}else{
				$returnArray = array(
					'code' => 0,
					'info' => '手机号不能为空'
				);
			};
			if(empty($_POST['password']) == false){
				$data['password']  = md5($_POST['password']);
			}else{
				$returnArray = array(
					'code' => 0,
					'info' => '密码不能为空'
				);
			};
			$data['create_time'] = date('Y-m-d H:i:s');
			if(empty($returnArray)){
				$result = $userModel->create($data);
				if($result){
					$returnArray = array(
						'code' => 1,
						'info' => '注册成功！' 
					);
				}else{
					$returnArray = array(
						'code' => 0,
						'info' => '注册失败……' 
					)	;				
				}
			}
		}
		
		return $returnArray;
	}
	
	/*
	 * 登出
	 */
	public function quietAction()
	{


	    $userModel = new \app\admin\model\LuntanUser();
	    $thisTime = date('Y-m-s H:i:s');
	    $userModel->where(array("id"=>$this->companyId))->update(['this_time' =>$thisTime]);
	    session('help_user_info',null);
	    $this->redirect('login/login');
	}
	
	
	public function upimg()
	{
	    
// 	    var_dump($_FILES['img']);exit;
	    if($_FILES['img']){
	        
	        $imageModel = new \app\admin\model\ImageRecord();
	        $imageModel ->uploadImageAction($_FILES['img']);
	    }
	}
	
	/**
	 * 注册你麻辣比
	 */
	
	public function registerFalse()
	{
		echo '你以为真让你注册啊，太天真了！！！！';
	}
	
	
	/*
	 * 测试
	 */
	
	
	public function test()
	{
	    $userModel = new \app\admin\model\LuntanUser();
	    $thisTime = date('Y-m-s H:i:s');
		$result = $userModel->where(array("id"=>1))->update(array('sex' => 5));
		var_dump($result );
	}
} 