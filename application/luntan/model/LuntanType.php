<?php 
namespace app\luntan\model;
use think\Model;

/**
 * 用户模型
 * @author zhuyong
 * @date 2017-08-31 14:11
 * @version 1.0
 */

class LuntanType extends Model
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

    
}