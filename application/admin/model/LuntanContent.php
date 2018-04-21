<?php 
namespace app\admin\model;
use think\Model;

/**
 * 用户模型
 * @author zhuyong
 * @date 2017-08-31 14:11
 * @version 1.0
 */
 
  //博客APP接口错误状态码
    const ERROR_CODE = array(
        1 => 'SUCCESS',
        100001 => '参数错误',
        100002 => '没有参数',
        100003 => '没有缩略图片参数',
    );
class LuntanContent extends Model
{
    // 表名	
    
    //所有键值
    private $table_key = array(
    );    
    
    //模糊查询字段
    public $fuzzy_query = 'content';
    
    
    
    /**
     * checkLogin 检测当前用户是否登录
     * @param void
     * $return bool 是否登录
     */

	   
    /**
     * 实例化redis
     *
     */
    public function iniRedis()
    {
        $redis = array();
        if(true){
            $redis = new \Redis();
            $redis->pconnect('127.0.0.1', '6379');
            $redis->auth('wqzbxh@2018$');
            $redis->select(0);
        }else{
            return 'false';
        }
        
        return $redis;
    }
    
}