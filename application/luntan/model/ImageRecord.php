<?php 
namespace app\luntan\model;
use think\Model;

/**
 * 用户模型
 * @author Wqzbxh
 * @date 2017-08-31 14:11
 * @version 1.0
 */
class ImageRecord extends Model
{

    // 表名
    private $table_name = 'image_record';
    
    
    // 外部编号头
    private $key = 'IMG';
    
    //获取外部编号  头字母 + 时间戳 + 8位随机数
    public function getEntityId() {
        return $this->key . time() . rand(10000000,99999999);
    }
    
    /*
     * 添加图片记录到数据库
     */
    
    public function addImageRecord($data)
    {
        if(empty($data) == false && is_array($data)){
            return self::create($data);
        }else{
            return false;
        }
    }
    
    /**
     * 上传文件方法
     * 
     *
     */
    public function uploadImageAction($file)
    {
        $returnArray = array();
        if(empty($file) && is_array($file) && $file['size'] < 0){
            $returnArray = array(
                'code' => 0,
                'info' => '没有图片被上传！',
            );
        }else{
            if($file['size'] < 2097152){
                if($file['type'] == 'image/bmp' || $file['type'] == 'image/gif' || $file['type'] == 'image/jpeg' || $file['type'] == 'image/png' || $file['type'] == 'application/pdf'){
                    $object = self::getEntityId();
                    $ossModel = self::ossClient();
                    $ossResult = $ossModel->uploadFile('wqzbxhoss', $object, $file["tmp_name"]);
                    if(empty($ossResult['oss-request-url'])){
                        var_dump($ossResult);exit;
                        $returnArray = array(
                            'code' => 0,
                            'info' => '图片上传失败！',
                        );
                    }else{ 
                    
                        $data = array(
                            'entity_id' => $object,
                            'tmp_name' => $file["tmp_name"],
                            'real_name' => $file['name'],
                            'size' => $file['size'],
                            'path' =>  $ossResult['oss-request-url'],
                            'create_time' => date('Y-m-d H:i:s')
                        );
                       
                        $result = self::addImageRecord($data);
                    
                        if($result){
                            $returnArray['info'] = $result['id'];
                            $returnArray['path'] = $ossResult['oss-request-url'];
                        }
                    }
                }else{
                    $returnArray = array(
                        'code' => 0,
                        'info' => '图片类型错误！',
                    );
                }
            }else{
                $returnArray = array(
                    'code' => 0,
                    'info' => '图片超过2MB！'
                );
            }
    
            if(empty($returnArray) == false){
                $returnArray['code'] = 1;
            }else{
                $returnArray = array(
                    'code' => 0,
                    'info' => '没有图片被上传！',
                );
            }
        }
    
        return $returnArray;
    }
    
    
    public function ossClient()
    {
        Vendor('OSS.autoload');
        $accessKeyId = "LTAIYg0S1PVN0uOa";
        $accessKeySecret = "QSqntGS8HJBZD9A5eyam7NzGfL7EJh";
        $endpoint = "oss-cn-beijing.aliyuncs.com";
    
        try {
            $ossClient = new \OSS\OssClient($accessKeyId, $accessKeySecret, $endpoint);
            return $ossClient;
        } catch (OssException $e) {
            return $e->getMessage();
        }
    }
    
}