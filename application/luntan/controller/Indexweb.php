<?php
namespace app\luntan\controller;
use think\Controller;

/**
 *
 * @author ZhuYong
 *
 *
 */
class Indexweb extends Controller
{

    //初始化函数
    public function _initialize()
    {

    }
    /**
     *登录
     *
     */
    public function index()
    {

        self::getVisitor();
        $limit = 10 ;
        $offset = 0;
        $result = array();
        $typeInfo = array();
        $lunTanContentModel = new \app\admin\model\LuntanContent();
        $lunTanTypeModel = new \app\admin\model\LuntanType();
        $luntanLebalModel = new \app\admin\model\LuntanLebal();
        $where = array('is_show'=>1,'is_del'=>0);
        $search = $_POST['search'] ?? null;

        $typeInfo = $lunTanTypeModel->getTpyes();
        $lunTanContentRows = $lunTanContentModel->where($where);
        if(empty($_POST['offset']) == false){
            $offset = $_POST['offset'];
        }
        if(empty($_POST['limit']) == false){
            $limit = $_POST['limit'];
        }

        $order = array('id' => 'desc');
        $hotOrder = array('browse' => 'desc');

        if(!empty($search)){
            $lunTanContentRow = $lunTanContentRows->order($order)->where($lunTanContentModel->fuzzy_field,'like','%'.$search.'%')->limit($offset,$limit)->select();
        }else{
            $lunTanContentRow = $lunTanContentRows->order($order)->limit($offset,$limit)->select();
        }

        //获取热门文章

        $info = array();
        if($lunTanContentRow){
            foreach($lunTanContentRow as $contentRow){
                $contentRow = $contentRow->toArray();
                if($contentRow['type_id']){
                    $rows = $lunTanTypeModel->get(array('id'=>$contentRow['type_id']));
                    $row = $rows->toArray();
                    $result = $contentRow;
                    $result['typeTitle'] = $rows['content'];

                }
                if($contentRow['img_id']){
                    $imageModel = new \app\admin\model\ImageRecord();
                    $imgRow = $imageModel->get(array('id'=>$contentRow['img_id']));
                    if($imgRow){
                        $imgRow =  $imgRow->toArray();
                        $result['path'] = $imgRow['path'];
                        $info[] = $result;
                    }
                }
            }
        }

        $hotContent = $lunTanContentRows-> order($hotOrder)->limit(5)->select();

        $hotInfo = array();
        if($hotContent){
            foreach($hotContent as $hotRow){
                $hotRow = $hotRow->toArray();
                if(!empty($hotRow['type_id'])){
                    $hotTypeRows = $lunTanTypeModel->get(array('id'=>$hotRow['type_id']));

                    $hotTypeRows = $hotTypeRows->toArray();
                    $hotResult = $hotRow;
                    $hotResult['typeTitle'] = $hotTypeRows['content'];
                    $hotInfo[] = $hotResult;
                }

            }
        }
        $luntanLebalModel = new \app\admin\model\LuntanLebal();
        $luntanLebalRows = $luntanLebalModel->where(array('is_show'=>1))->select();
        $lebalArray = array();
        if($luntanLebalRows){
            foreach ($luntanLebalRows as $LebalRows){
                $LebalRow = $LebalRows->toArray();
                $lebalArrays['name'] = $LebalRows['content'];
                $lebalArrays['num'] = $lunTanContentModel->where($lunTanContentModel->label_query,'like','%'.$LebalRows['id'].'%')->where(array('is_del'=>0,'is_show'=>1))->count();
                $lebalArray[] =  $lebalArrays;
            }
        }
        $this->assign('lebalRow',$lebalArray);
        $this->assign('contentRow',$info);
        $this->assign('typeRow',$typeInfo);
        $this->assign('hotInfo',$hotInfo);

        return $this->fetch('index');
    }

    /**
     *
     * @return mixed
     */


    public function arctic()
    {
        self::getVisitor();
        $arcticRow = array();
        $returnArray = array();
        $labelType = array();
        if(!empty($_GET)){
            if(empty($_GET['id'])==false){
                $id = $_GET['id'];
            }else{
                $returnArray = array(
                    'code' => 100002,
                    'msg'  =>  '参数错误',
                    'data' => array()
                );
            }
        }else{
            $returnArray = array(
                'code' => 100001,
                'msg'  =>  '没有参数',
                'data' => array()
            );
        }

        $ImageRecordModel = new \app\admin\model\ImageRecord();
        $lunTanContentModel = new \app\admin\model\LuntanContent();
        $lunTanTypeModel = new \app\admin\model\LuntanType();
        $labelModel = new \app\admin\model\LuntanLebal();

        $where = array('is_show'=>1,'is_del'=>0);
        //获取分类：
        $contentRow = $lunTanContentModel->get(array('id'=>$id,'is_show'=>1,'is_del'=>0))->toArray();


        if($contentRow['type_id']){
            $rows = $lunTanTypeModel->get(array('id'=>$contentRow['type_id']));
            $row = $rows->toArray();
            $arcticRow['typeContent'] = $row['content'];
            $arcticRow['content'] = $contentRow['content'];
            $arcticRow['abstract'] = $contentRow['abstract'];
            $arcticRow['title'] = $contentRow['title'];
            $arcticRow['source'] = $contentRow['source'];
            $arcticRow['browse'] = $contentRow['browse'];
            $arcticRow['keyword'] = $contentRow['keyword'];
            $arcticRow['creTime'] = $contentRow['cre_time'];
            $arcticRow['author'] = $contentRow['author'];
        }
        if($contentRow['label_id']){
            $labelRows = explode(',', $contentRow['label_id']);
            foreach ($labelRows as $key => $value ){
                $labelRow = $labelModel->get(array('id'=>$value));
                $labelContent = $labelRow->toArray();
                $labelType[] = $labelContent['content'];
            }
        }
        if($contentRow['img_id']){
            $ImageRecordRows = $ImageRecordModel->get(array('id'=>$contentRow['img_id']));
            $ImageRecordRow = $ImageRecordRows->toArray();
            $arcticRow["img_oss"] = $ImageRecordRow['path'];
        }
        $arcticRow['labelType'] =$labelType;
        $typeTitle = $lunTanTypeModel->where($where)->field('content')->select();
        $typeInfo = array();
        foreach ($typeTitle as $typeRow){
            $typeRow = $typeRow->toArray();
            $typeInfo[] =$typeRow['content'];
        }

        $this->assign('contentRow',$arcticRow);
        $this->assign('typeRow',$typeInfo);
// 		$this->assign('hotInfo',$hotInfo);
        return $this->fetch('arctic');
    }

    /**
     *位置显示
     *
     */
    public function mylocaltion(){
        return $this->fetch('mylocaltion');
    }

    /**
     *测试
     *
    // 	*/
// 	public function test($i){
// 		header("Content-Type:text/html;charset=UTF-8");
// 		date_default_timezone_set("PRC");
// 		$showapi_appid = '60425';  //替换此值,在官网的"我的应用"中找到相关值
// 		$showapi_secret = '666af33288834051894bfd30cdf8c2c5';  //替换此值,在官网的"我的应用"中找到相关值
// 		$data = array();
// 		$luntanJokeModel = new \app\luntan\model\LuntanJoke();
// 			$paramArr = array(
// 			'showapi_appid'=> $showapi_appid,
// 				'page'=> $i,
// 				'maxResult'=> "50"
// 			//添加其他参数
// 			);

// 			//创建参数(包括签名的处理)
// 			function createParam ($paramArr,$showapi_secret) {

// 			$paraStr = "";
// 			$signStr = "";
// 			ksort($paramArr);
// 			foreach ($paramArr as $key => $val) {
// 				if ($key != '' && $val != '') {
// 				$signStr .= $key.$val;
// 				$paraStr .= $key.'='.urlencode($val).'&';
// 					}
// 			}
// 			$signStr .= $showapi_secret;//排好序的参数加上secret,进行md5
// 			$sign = strtolower(md5($signStr));
// 			$paraStr .= 'showapi_sign='.$sign;//将md5后的值作为参数,便于服务器的效验
// 			return $paraStr;
// 			}

// 			$param = createParam($paramArr,$showapi_secret);
// 			$url = 'http://route.showapi.com/341-1?'.$param;

// 			$result = file_get_contents($url);
// 			$result = (array)((array)((array)json_decode($result))['showapi_res_body'])['contentlist'];
// 			foreach($result as $row){
// 				$row = (array)$row;
// 				if(is_array($row)){
// 					$data = array(
// 						'title' => $row['title'],
// 						'content' => $row['text'],
// 						'create_time' => $row['ct'],
// 					);
// 					$outcome = $luntanJokeModel->create($data);
// 					$outcome = $outcome->toArray();
// 				}
// 				if(empty($outcome)){
// 					return;
// 				}
// 			}

// 	}

    public function testaction(){

        $lunTanContentModel = new \app\admin\model\LuntanContent();
        $redis = $lunTanContentModel->iniRedis();
        $info = array(
            'code' => 1,
            'msg' => 'SUCCESS',
            'data' => array(
                'name' => 'haiyang',
                'age' => '22',
                'sex' => '男',
                'phone' => '13512169551',
            )
        );

        $result = json_encode($info);

        $a = $redis->set('myname',$result);

    }
    public function getVisitor(){
        $ip = $_SERVER["REMOTE_ADDR"];
        $agent=$_SERVER["HTTP_USER_AGENT"];
        $operateData = file_get_contents('http://ip.taobao.com/service/getIpInfo.php?ip='.$ip);
        $operate = json_decode($operateData,true);
        $oper = $operate['data'];
        $page = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
        $lunTanContentModel = new \app\admin\model\LuntanContent();
        $redis = $lunTanContentModel->pvRedis();
        $datetime =  date('Y-m-d H:i:s');
        if($redis){
            $ipResult = $redis->rpush('IP',$ip.'WQZB'.$datetime.'WQZB'.$page.'WQZB'.$oper['country'].'WQZB'.$oper['city'].'WQZB'.$oper['isp'].'WQZB'.$agent);
        }
    }

    public function setVisitor()
    {
        $lunTanContentModel = new \app\admin\model\LuntanContent();
        $VisitorModel = new \app\admin\model\LuntanVisitor();
        $redis = $lunTanContentModel->pvRedis();
        // $labelRows = explode(',', $contentRow['label_id']);
        if($redis){
            for($i = 0; $i < 500;  $i++  ){
                $reultRows = $redis->lpop('IP');
                $result = explode('WQZB',$reultRows);
                if(!empty($result[0])){
                    $data['host'] = $result[0] ?? '未知';
                    $data['create_at'] = $result[1] ?? '2018-05-12 17:08:28';
                    $data['page'] = $result[2] ?? '未知';
                    $data['country'] = $result[3] ?? '未知';
                    $data['city'] = $result[4] ?? '未知';
                    $data['isp'] = $result[5] ?? '未知';
                    $data['browser'] = $result[6] ?? '未知';
                    $VisitorModel->create($data);
                }else{
                    return ;
                }
            }
        }
    }
} 