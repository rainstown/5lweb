<?php
class Auction_recordsModel extends BaseModel
{
	public $table = 'auction_records';
	public $prikey = 'records_id';
	public $_name = 'auction_records';
	
	/**
	 * 获得用户最新的竞价
	 * @param integer $auction_id
	 * @param integer $goods_id
	 * @param integer $user_id
	 * @return Ambigous <string, boolean>
	 */
	public function getUserLastPay($auction_id, $goods_id, $user_id)
	{
		$db = db();
		$sql = 'SELECT * FROM ' . $this->table . '
			WHERE goods_id = ' . $goods_id . ' AND auction_id = ' . $auction_id . ' AND user_id = ' . $user_id . '
			ORDER BY create_time DESC
			LIMIT 1';
		return $db->getRow($sql);
	}

	/**
	 * 获得最新的竞价
	 * @param integer $auction_id
	 * @param integer $goods_id
	 * @return Ambigous <string, boolean>
	 */
	public function getLastPay($auction_id, $goods_id)
	{
		$db = db();
		$sql = 'SELECT price FROM ' . $this->table . '
			WHERE goods_id = ' . $goods_id . ' AND auction_id = ' . $auction_id . ' AND status = "' . EnumStatus::VALID . '"
			ORDER BY create_time DESC
			LIMIT 1';
		return $db->getOne($sql);
	}
	
	/**
	 * 价格失效
	 * @param integer $auction_id
	 * @param integer $goods_id
	 */
	public function disablePrice($auction_id, $goods_id)
	{
		$db = db();
		$sql = 'UPDATE ' . $this->table . ' SET status = "' . EnumStatus::INVALID . '"
			WHERE auction_id = ' . intval($auction_id) . ' AND goods_id = ' . intval($goods_id) . ' 
		';
		$db->query($sql);
	}
        
        /**
         * 根据获取我拍到的产品信息；
         * 生成订单中使用
         */
        public function getRecords($records_id)
        {
            $sql = 'SELECT store.store_id, store.store_name, goods.goods_id, goods.goods_name, goods.default_image, records.price
                    ,records.pay_status, records.status AS records_status , records.auction_id
                    FROM ' .$this->table .' records INNER JOIN   '. DB_PREFIX  .'goods goods ON records.goods_id = goods.goods_id 
                        INNER JOIN '. DB_PREFIX  .'store store ON store.store_id = goods.store_id 
                    WHERE records.records_id = ' .intval($records_id)  ;
             return $this->getRow($sql);
        }
        
        /**
         * 更新为已支付状态
         * @param type $order_id
         */
        public function updateToPay($order_id)
        {
            $this->edit($order_id, array('pay_status' => '20'));
        }
        
        /**
         * 获取用户竞拍到但是没有支付的的拍卖记录的保证金
         */
        public function getNotPayMoney($user_id)
        {
            $sql = 'SELECT SUM(keep) AS price FROM ' . $this->table . ' WHERE status = "V" and user_id = '. intval($user_id) .' AND pay_status = 0 ';
            return $this->getOne($sql);
        }
        
}