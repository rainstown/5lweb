<?php

/* 收藏 */
class CollectModel extends BaseModel
{
    var $table  = 'collect';
    var $alias  = 'collect';
    var $prikey = '';
    var $_name  = 'collect';
   
    public function getInfo($user_id, $item_id, $type)
    {
        $info = $this->db->getOne("SELECT * FROM {$this->table} WHERE user_id='{$user_id}' AND type='{$type}' AND item_id='{$item_id}'");
        return $info;
    }
    

    
}

?>
