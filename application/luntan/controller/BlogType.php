<?php
namespace app\luntan\controller;
use think\Controller;

class BlogType extends Controller
{

    //初始化函数
    public function _initialize()
    {
        parent::_initialize();
    }


    public function addTypeAction()
    { 
        $returnArray = array();
        $LuntanTypeModel =new \app\luntan\model\LuntanType();
        if(!empty($_POST)){
            if(!empty($_POST['remarks'])){
                $data['remarks'] = $_POST['remarks'];
            }else{
                $returnArray = array(
                    'code' => 0,
                    'info' => '备注必须填写',
                );
            }
            if(empty($_POST['blog_type_name']) == false){
                $data['content'] = $_POST['blog_type_name'];
            }else{
                $returnArray = array(
                    'code' => 0,
                    'info' => '类型必须写'
                );
            }
            $data['create_time'] = date('Y-m-d H:i:s');
            if(empty($returnArray)){
                $result = $LuntanTypeModel->create($data);
                if($result){
                    $returnArray = array(
                        'code' => 1,
                        'info' => '添加类型成功！'
                    );
                }else{
                    $returnArray = array(
                        'code' => 0,
                        'info' => '添加类型失败……',
                    );
                }
            }
        }
        return $returnArray;
    }
    /*
     检验标题是否存在
     */
    public function checkedType(){
        $returnArray = array();
        if(empty($_POST) == false){
            if(empty($_POST['blog_type_name']) == false){
                $content = $_POST['blog_type_name'];
                $luntanTypeModel =new \app\luntan\model\LuntanType();
                $count = $luntanTypeModel->where(array('content'=>$content))->count();
                if($count == 1){
                    $returnArray = array(
                        'code' => 0,
                        'info' => '标题已存在'
                    );
                }else{
                    $returnArray = array(
                        'code' => 1,
                        'info' => '标题可以使用'
                    );
                }
            }else{
                $returnArray = array(
                    'code' => 0,
                    'info' => '不能为空'
                );
            }
        }else{
            $returnArray = array(
                'code' => 0,
                'info' => '参数错误'
            );
        }
        return $returnArray;
    }
    /* selectType':selectType,'sort':sort,'abstractContent':abstractContent,'author':author,'source':source,'keywords':keywords,'is_comment':is_comment,'title_simple':title_simple,'title':title	 */
   
    public function product_category_add()
    {
        return $this->fetch('product_category_add');
    }
    public function product_list()
    {
        return $this->fetch('product_list');
    }
    public function product_brand()
    {
        return $this->fetch('product_brand');
    }
	public function product_category()
	{
		return $this->fetch('product_category');
	}
}