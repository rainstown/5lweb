<?php
/**
 * 
 * @author shearf
 *
 */
class AuctionModel extends BaseModel
{
	public $table = 'auction';
	public $prikey = 'auction_id';
	
	public  $_name = 'auction';
	
	public $_autov = array(
		'name' => array(
			'required' => true,
			'min' => 1,
			'max' => 100,
			'filter' => 'trim'
		),	
		'start_time' => array(
			'required' => true,
			'filter' => 'trim'
		),
		'type' => array(
			'required' => true,
			'filter' => 'trim',
		),
		'status' => array(
			'required' => true,
			'filter' => 'trim'
		),
		'end_time' => array(
			'required' => true,
			'filter' => 'trim'
		),
		
		'put_money' => array(
			'filter' => 'intval'
		),
		'keep_money' => array(
			'filter' => 'intval',
		),
		'trade_money' => array(
			'filter' => 'intval'
		),
	);
	
	public $_relation = array(
		'creator' => array(
			'model' => 'member',
			'type' => BELONGS_TO,
			'foreign_key' => 'user_id',
			'refer_key'	=> 'create_user',
			'reverse'	=> 'has_auction',
		),
	);
	/**
	 * 更新拍卖会的商品数
	 * @param unknown $auction_id
	 */
	public function updateGoodsNum($auction_id)
	{
		$db = &db();
		$sql = 'UPDATE ' . $this->table . ' SET goods_num = 
			(SELECT COUNT(goods_id) FROM ' . DB_PREFIX . 'auction_goods 
				WHERE auction_id = ' . $auction_id . ' AND status = "' . EnumStatus::VALID . '"
			) WHERE auction_id = ' . $auction_id;
		$db->query($sql);
	}
        
         /**
         * 通过订单ID， 获取拍卖会信息;
         */
        public function getAuctionInfoByOrderId($order_id, $user_id)
        {
            $sql = 'SELECT a.*, r. FROM ' .$this->table  
                    . ' a INNER JOIN ' . DB_PREFIX . 'auction_records r ON a.auction_id = r.auction_id '
                    . ' WHERE r.order_id = ' . intval($order_id) ;
            return $this->getRow($sql);
        }
	
}