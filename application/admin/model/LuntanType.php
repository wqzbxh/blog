<?php 
namespace app\admin\model;
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
     * 获取博客分类
     */
    public function getTpyes(){
        $where = array('is_show'=>1,'is_del'=>0);
        $typeTitle = self::where($where)->select();
        $typeInfo = array();
        foreach ($typeTitle as $typeRow){
            $typeRow = $typeRow->toArray();
            $typeInfo[$typeRow['id']] = $typeRow['content'];
        }
        return $typeInfo;
    }


    public function getTpye($num){
        $typeTitle = self::where(array('id'=>$num))->select();
        $typeContent= array();
        foreach ($typeTitle as $typeRow){
            $typeContent = $typeRow['content'];
        }
        return $typeContent;
    }
    
}