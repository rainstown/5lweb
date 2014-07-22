<?php

/* 支付 pay */
class PayModel extends BaseModel
{
    var $table  = 'pay';
    var $alias  = 'pay_alias';
    var $prikey = 'pay_id';
    var $_name  = 'pay';
    var $_relation  = array(
       
        // 一个支付有多个订单日志
        'has_paylog' => array(
            'model'         => 'paylog',
            'type'          => HAS_MANY,
            'foreign_key'   => 'pay_id',
            'dependent'     => true
        ),
        // 一个支付属于一个订单
        'belongs_to_order'  => array(
            'type'          => BELONGS_TO,
            'reverse'       => 'has_pay',
            'model'         => 'order',
        ),
        //一个支付属于一个人
        'belongs_to_user'  => array(
            'type'          => BELONGS_TO,
            'reverse'       => 'has_pay',
            'model'         => 'member',
        ),
    );

    function pay_order()
    {
        
    }

    /**
     * 根据订单ID，修改为取消.
     */
    public function updateToCancelByOrderId($order_id, $remark, $op_user)
    {
        $status = PAY_CANCELED;
        $this->_updateStatusByOrderId($order_id, $remark, $op_user, $status); 
    }
    
     /**
     * 根据订单ID，修改为取消.
     */
   public function updateToFinishByOrderId($order_id, $remark, $op_user)
    {
        $status = PAY_FINISHED;
        $this->_updateStatusByOrderId($order_id, $remark, $op_user, $status); 
    }
    
    /**
     * 更新状态
     * @param type $order_id
     * @param type $remark
     * @param type $op_user
     * @param type $status
     */
    private function _updateStatusByOrderId($order_id, $remark, $op_user, $status) 
    {
        $aData = array('status' => $status , 'update_time' => gmtime(), 'update_user' => $op_user , 'remark' => $remark );
        $this->edit('order_id = '.$order_id .' AND type="' .PAY_TYPE_GOOD .'"', $aData);
    }
    
    /**
     * 获取支付记录;
     * @param type $order_id
     * @return type
     */
    public function getInfoByOrderId($order_id)
    {
        $sql = 'SELECT * FROM ' . DB_PREFIX  .$this->_name .' WHERE order_id = ' . $order_id .' AND parent_id = 0 ';
        return $this->getRow($sql);
    }
    
    /*
     * 获取拍卖的支付记录
     */
    public function getAuctionInfo($auction_id, $user_id)
    {
         $sql = 'SELECT * FROM ' . DB_PREFIX  .$this->_name .' WHERE order_id = ' . $auction_id
                 .' AND buyer_id = '. $user_id .' AND parent_id = 0 AND type = "'.PAY_TYPE_CAUTION.'" ';
        return $this->getRow($sql);
    }
   
}

?>
