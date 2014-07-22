<?php

/* 虚拟账户银行 */
class BankModel extends BaseModel
{
    var $table  = 'bank';
    var $alias  = 'bank_alias';
    var $prikey = 'bank_id';
    var $_name  = 'bank';
    var $_relation  = array(
       
        // 一个支付有多个订单日志
        //一个支付属于一个人
        'belongs_to_user'  => array(
            'type'          => BELONGS_TO,
            'reverse'       => 'has_bank',
            'model'         => 'member',
        ),
    );

    
    /**
     * 从余额账户到保证金账户
     */
   public function payAccountToCautionMoney($bank_id, $money)
    {
        $this->edit($bank_id, "money = money - {$money}, caution_money = caution_money + {$money}");
       
    }
    
    /**
     * 从保证金账户到账户
     */
   public function payCautionMoneyToAccount($bank_id, $money)
    {
        $this->edit($bank_id, "money = money + {$money}, caution_money = caution_money - {$money}");
        
    }
    
    /**
     * 提现操作成功;
     */
    public function payCautionMoney($bank_id, $money)
    {
         $this->edit($bank_id, "caution_money = caution_money - {$money}");
    }
    
    /**
     * 保证金账户扣除金额，
     * 增加到卖家用户，同时更新记录
     * @param type $order_id
     * @param type $op_user
     * @param type $time
     */
    public function payCautionMoneyToUserByOrderId($order_id, $op_user, $time)
    {
        $sql = 'UPDATE ' . DB_PREFIX  .'pay p 
                INNER JOIN ' . DB_PREFIX  .'bank b ON p.buyer_id = b.bank_id 
                INNER JOIN ' . DB_PREFIX  .'bank s ON p.seller_id = s.bank_id
                SET p.status = ' . PAY_FINISHED . ', b.caution_money = b.caution_money - p.money , s.money = s.money + p.money ,
                p.update_time = "' . $time . '", p.update_user= ' . $op_user . '
                WHERE p.order_id = ' . $order_id . ' AND p.status = '. PAY_ACCEPTED 
                .' AND p.type != "' . PAY_TYPE_TAX.'"';
        $this->db->query($sql);
    }
    
     /**
     * 保证金账户扣除金额： 增加到平台账户，在 account.model.php 中操作
     * 同时更新记录支付状态
     * @param type $pay_id  订单ID
     * @param type $op_user 操作用户
     * @param type $time    操作时间
     */
    public function payCautionMoneyToPlanByPayId($pay_id, $op_user, $time)
    {
        $sql = 'UPDATE ' . DB_PREFIX  .'pay p 
                INNER JOIN ' . DB_PREFIX  .'bank b ON p.buyer_id = b.bank_id 
                SET p.status = ' . PAY_FINISHED . ', b.caution_money = b.caution_money - p.money ,
                p.update_time = "' . $time . '", p.update_user= ' . $op_user . '
                WHERE p.pay_id = ' . $pay_id . ' AND p.status = '. PAY_ACCEPTED;
        $this->db->query($sql);
    }
    
    /**
     * 根据订单扣除交易所得税给平台
     * @param type $order_id
     */
    function deductionTaxToPlat($order_id, $op_user)
    {
        $pay = & m('pay');
        $pay_info = $pay->getInfoByOrderId($order_id);
        $seller_id = $pay_info['seller_id'];
        $seller_name = $pay_info['seller_name'];
        $money = round($pay_info['money']/100, 2);
        $parent_id = $pay_info['pay_id'];
        $pay_name = $pay_info['pay_name'];
        
       //先扣除保证金，
       $this->payAccountToCautionMoney($seller_id, $money);
       //再添加交易税记录
       $pay_id = $pay->add(array(
                'order_id'  => $order_id,
                'parent_id' => $parent_id,
                'type'      => PAY_TYPE_TAX,
                'money'     => $money,
                'buyer_id'  => $seller_id,
                'buyer_name'  => $seller_name,
                'seller_id'   => 0,
                'seller_name'   => '平台中心',
                'pay_name'  => $pay_name.'_交易税',
                'status' => PAY_ACCEPTED,
                'create_time'=> gmtime(),
                'create_user'=> $op_user
            ));
       //平台增加金额;
       $account = & m('account');
       $account->addMoney($money, $pay_name, $pay_id, $op_user);
       //更新订单记录, 并扣除保证金中的前;
       $this->payCautionMoneyToPlanByPayId($pay_id, $op_user, gmtime());
       
    }
    
    /**
     * 扣除拍卖的交易费
     * @param type $order_id
     * @param type $money
     * @param type $op_user
     */
    function deductionAuctionTaxToPlat($order_id, $money, $op_user)
    {
        $pay = & m('pay');
        $pay_info = $pay->getInfoByOrderId($order_id);
        $seller_id = $pay_info['seller_id'];
        $seller_name = $pay_info['seller_name'];
        
        $parent_id = $pay_info['pay_id'];
        $pay_name = $pay_info['pay_name'];
        
       //先扣除保证金，
       $this->payAccountToCautionMoney($seller_id, $money);
       //再添加交易税记录
       $pay_id = $pay->add(array(
                'order_id'  => $order_id,
                'parent_id' => $parent_id,
                'type'      => PAY_TYPE_TAX,
                'money'     => $money,
                'buyer_id'  => $seller_id,
                'buyer_name'  => $seller_name,
                'seller_id'   => 0,
                'seller_name'   => '平台中心',
                'pay_name'  => $pay_name.'_交易税',
                'status' => PAY_ACCEPTED,
                'create_time'=> gmtime(),
                'create_user'=> $op_user
            ));
       //平台增加金额;
       $account = & m('account');
       $account->addMoney($money, $pay_name, $pay_id, $op_user);
       //更新订单记录, 并扣除保证金中的前;
       $this->payCautionMoneyToPlanByPayId($pay_id, $op_user, gmtime());
    }
    
    function addMoney($bank_id, $money)
    {
        $sql = 'INSERT INTO ' . DB_PREFIX  .'bank (bank_id, money,create_time) values ('. $bank_id .', '. $money .', '. gmtime() .') ON DUPLICATE KEY UPDATE money=money + ' .$money;
        $this->db->query($sql);
    }

    
}

?>
