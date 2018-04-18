<?php
namespace app\luntan\controller;
use think\Controller;

class BlogArctic extends Controller
{
	
	//初始化函数
	public function _initialize()
    {
        parent::_initialize();
    }

    /*
     * 添加博文
     */
    public function addBlogAction()
    {
        $returnArray = array();
        if(!empty($_POST)){
            if(empty($_POST['title'])==false){
                $data['title'] = $_POST['title'];
            }else{
                $returnArray = array(
                    'code' => 0 ,
                    'info' => '标题类型不能为空'
                );
            }
            if(empty($_POST['title_simple'])==false){
                $data['title_simple'] = $_POST['title_simple'];
            }else{
                $returnArray = array(
                    'code' => 0 ,
                    'info' => '简约标题不能为空'
                );
            }
             
            if(isset($_POST['sort'])){
                $data['sortid'] = $_POST['sort'];
            }else{
                $returnArray = array(
                    'code' => 0 ,
                    'info' => '类型不能为空'
                );
            }
            if(empty($_POST['abstractContent']) == false){
                $data['abstract'] = $_POST['abstractContent'];
            }else{
                $returnArray = array(
                    'code' => 0 ,
                    'info' => '摘要不能为空',
                );
            }
            if(empty($_POST['author'])==false){
                $data['author'] = $_POST['author'];
            }
            if(isset($_POST['is_comment'])){
                $data['is_comment'] = $_POST['is_comment'];
            }
            if(empty($_POST['source'])==false){
                $data['source'] = $_POST['source'];
            }
            if(empty($_POST['allLabelTag'])==false){
                $data['label_id'] = $_POST['allLabelTag'];
            }
            if(empty($_POST['editorValue'])==false){
                $data['content'] = $_POST['editorValue'];
            }else{
                $returnArray = array(
                    'code' => 0 ,
                    'info' => '内容不能为空'
                );
            }
            if(empty($_POST['selectType'])==false){
                $data['type_id'] = $_POST['selectType'];
            }else{
                $returnArray = array(
                    'code' => 0 ,
                    'info' => '类型不能为空'
                );
            }
             
            if(empty($_FILES['img']) == false){
                $imageModel = new \app\luntan\model\ImageRecord();
                $imgRecod = $imageModel ->uploadImageAction($_FILES['img']);
                if($imgRecod['info']){
                    $data['img_id'] = $imgRecod['info'];
                }
            }else{
                $returnArray = array(
                    'code' => 100003 ,
                    'data' => '没有缩略图片参数'
                );
            }
            $data['cre_time'] =  date('Y-m-d H:i:s');
            if(empty($returnArray)){
                $LuntanContentModel = new \app\luntan\model\LuntanContent();
                $lunTanContentRow = $LuntanContentModel->create($data);
                if($lunTanContentRow){
                    $lunTanContentRow = $lunTanContentRow->toArray();
                    $returnArray = array(
                        'code' => 1,
                        'info' => '添加成功'
                    );
                    $this->redirect("index/articleList");
                }
    
            }else{
				var_dump($returnArray);
                $returnArray = array(
                    'code' => 0,
                    'info' => '创建失败'
                );
            }
        }
        return json_encode($returnArray);
    }
    
    
    /*
     * 文章异步添加标签
     * @labelTag 标签名字
     */
    public function labelTagAction()
    {
        $returnArray = array();
        $labelTagArray = array();
        if($_POST['labelTag']){
            $data['content'] = $_POST['labelTag'];
            $data['cre_time'] = date('Y-m-d  H:i:s');
            $lebalModel = new \app\luntan\model\LuntanLebal();
            $existRow = $lebalModel->get(array('content'=>$_POST['labelTag']));
            if($existRow ){
                $lebalRow = $existRow ->toArray();
                $returnArray = array(
                    'code'=>1,
                    'msg'=>'SUCCESS',
                    'data'=>array(
                        'id'=> $lebalRow['id'],
                        'labelTag' => $lebalRow['content']
                    )
                );
            }else{
                $resultRow = $lebalModel->create($data);
                if($resultRow){
                    $lebalRow = $resultRow ->toArray();
                    $returnArray = array(
                        'code'=>1,
                        'is_new'=> 'YES',
                        'msg'=>'SUCCESS',
                        'data'=>array(
                            'id'=>$lebalRow['id'],
                            'labelTag'=>$lebalRow['content']
                        )
                    );
                }
            }
        }
        return $returnArray;
    }
    
    /*
     * 删除文章
     */
    public function batchDelAction(){
        $returnArray = array();
        $idArray = array();
        $luntanContentModel = new \app\luntan\model\LuntanContent();
        
        if(!empty($_POST['checked'])){
            $idArray = $_POST['checked'];
            $id = '';
            foreach ($idArray as $value){
                if($id){
                    $id = $id.','.$value;
                }else{
                    $id =$value;
                }
            }
            if($id){
                $resuleRow = $luntanContentModel->where(array("id"=>array("in", $id)))->update(['is_del' =>1]);
            }
            if($resuleRow){
                $returnArray = array(
                    'code'=>1,
                    'msg'=>'SUCCESS',
                    'data'=>array()
                );
            }else{
                $returnArray = array(
                    'code'=>3,
                    'msg'=>'ERROR',
                    'data'=>array()
                    );
                };
        }
        return $returnArray;
    }
    
    public function bloglist(){
        $returnArray = array();
        $lebalInfo = array();
        $info = array();
        $indexModel = new \app\luntan\model\Index();
        $lunTanTypeModel = new \app\luntan\model\LuntanType();
       	$typeInfo = $lunTanTypeModel->getTpyes();
        $limit = isset($_POST['limit']) ? $_POST['limit'] : 10;
        $offset = isset($_POST['offset']) ? $_POST['offset'] : 0;
        if(!empty($_GET['typeid'])){
            $typeTitle = $lunTanTypeModel->getTpye($_GET['typeid']);
            $where = array('is_del'=>0,'is_show'=>1,'type_id'=>$_GET['typeid']);
            $luntanContentModel = new \app\luntan\model\LuntanContent();
            $blogRows = $luntanContentModel->where($where)->limit($offset,$limit)->field('id,abstract,img_id,title_simple,title,label_id,cre_time,browse')->select();
            if($blogRows){
                foreach ($blogRows as $blogRow){
                    $blogRow = $blogRow->toArray();
                    if($blogRow['img_id']){
                        $imageModel = new \app\luntan\model\ImageRecord();
                        $imgRow = $imageModel->field('path')->where(array('id'=>$blogRow['img_id']))->select();
                        if($imgRow){
                            $imgRow =  $imgRow[0]->toArray();
                            $blogRow['path'] = $imgRow['path'];
                            $blogRow['typeTitle'] = $typeTitle;
                            
                        }
                    }
                    if($blogRow['label_id']){ 
                        $lebalModel = new \app\luntan\model\LuntanLebal();
                        $labelRows = explode(',', $blogRow['label_id']);
                        foreach ($labelRows as $key => $value ){
                            $labelRow = $lebalModel->get(array('id'=>$value));
                            $labelContent = $labelRow->toArray();
                            $lebalInfo[] = $labelContent['content'];
                         }
                    }
                    
                    $blogRow['lebalInfo'] = $lebalInfo;
                    $info[] = $blogRow;
                }
            }
            if($info){
                $returnArray = array(
                    'code' => 1,
                    'mgs' => $indexModel::ERROR_CODE[1],
                    'data' => $info
                );
            }else{
                $returnArray = array(
                    'code' => 100002,
                    'mgs' => $indexModel::ERROR_CODE[100002],
                    'data' => array()
                );
            }
        }else{
            $returnArray = array(
             'code' => 100001,
             'mgs' => $indexModel::ERROR_CODE[100001],
             'data' => array()
            );
        }

        $this->assign('contentRow',$returnArray);
		$this->assign('typeRow',$typeInfo);
        return $this->fetch('blog_list');
    } 
} 