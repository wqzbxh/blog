<?php 
namespace app\admin\model;
use think\Model;

/**
 * 用户模型
 * @author zhuyong
 * @date 2017-08-31 14:11
 * @version 1.0
 */

class Index extends Model
{
    // 表名	
    
    //所有键值
    private $table_key = array(
    );    
    
    //模糊查询字段
    public $fuzzy_query = 'content';
    
    const ERROR_CODE = array(
        1 => 'SUCCESS',
        
        100001=>'文章类型不能为空！',
        100002=>'文章列表为空！',
        
        800001 => '文件上传失败！',
        800002 => '文件格式不正确！',
    );

}