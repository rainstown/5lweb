<?php

/* 平台的虚拟账户 */
class AccountModel extends BaseModel
{
    var $table  = 'account';
    var $alias  = 'account_alias';
    var $prikey = 'account_id';
    var $_name  = 'account';
   
    /**
     * 增加金额,
     */
    function addMoney($money, $pay_name, $pay_id,$op_user)
    {
        // 可能存在并发风险
        $sql = 'SELECT money FROM ' . DB_PREFIX  .'account a1 order by account_id DESC limit 1  ';
        $old_money = round($this->db->getOne($sql),2);
        $money = $old_money + $money;
        $this->add(array(
                'old_money' => $old_money,
                'money' => $money,
                'pay_id' =>  $pay_id,
                'pay_name' => $pay_name,
                'update_time' => gmtime(),
                'update_user' => $op_user
                ));
    }
    

    
}

?>
