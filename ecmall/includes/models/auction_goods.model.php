<?php
import('enum_status');
import('auction');
class Auction_goodsModel extends BaseModel
{
	var $table  = 'auction_goods';
	var $prikey = 'id';
	var $_name  = 'auction_goods';
	/**
	 * 判断是否已经该商品是否已经存在于拍卖会
	 * @param unknown $goods_id
	 * @param unknown $auction_id
	 * @param unknown $store_id
	 * @return boolean
	 */
	public function checkGoodsExists($goods_id, $auction_id)
	{
		$db = & db();
		
		$sql = 'SELECT g2.goods_id FROM ' . DB_PREFIX . 'auction_goods AS ag
					INNER JOIN ' . DB_PREFIX . 'goods AS g2 ON g2.goods_id = ag.goods_id
					INNER JOIN ' . DB_PREFIX . 'auction AS a ON a.auction_id = ag.auction_id
						WHERE a.end_time >= "' . get_system_date() . '"
						AND (a.status = "' . EnumStatus::VALID . '" OR a.status = "' . EnumStatus::PASS . '")
						AND ag.status = "' . EnumStatus::VALID . '"
						AND g2.goods_id = ' . $goods_id . ' AND ag.auction_id != ' . $auction_id;
		return $db->getOne($sql) > 0;
		
	}
	/**
	 * 获得当前用户制定拍卖会上发布的商品数量
	 * @param integer $auction_id
	 * @param integer $user_id
	 */
	public function getMyGoodsNum($auction_id, $store_id)
	{
		$db = &db();
		$sql = 'SELECT COUNT(g.goods_id) FROM ' . DB_PREFIX . 'goods AS g 
					INNER JOIN ' . $this->table .' AS ag ON ag.goods_id = g.goods_id
					WHERE g.store_id = ' . $store_id . ' 
						AND ag.status = "' . EnumStatus::VALID . '"
						AND ag.auction_id = ' . $auction_id . '
		';
		return $db->getOne($sql);
	}
	/**
	 * 获得拍卖品详情
	 * @param integer $goods_id
	 * @param integer $auction_id
	 */
	public function getGoodsDetail($goods_id, $auction_id)
	{
		$db = db();
		$sql = 'SELECT ag.*,g.brand, g.tags, g.year, g.size, g.quality, g.weight,g.goods_name FROM ' . $this->table . ' AS ag 
					INNER JOIN ' . DB_PREFIX . 'auction AS a ON a.auction_id = ag.auction_id
					INNER JOIN ' . DB_PREFIX . 'goods AS g ON ag.goods_id = g.goods_id
				WHERE a.auction_id = ' . intval($auction_id) . ' AND ag.goods_id = ' . intval($goods_id) .'
					AND (a.status ="' . EnumStatus::VALID . '" or a.status = "' . EnumStatus::PASS . '")
					AND ag.status ="' . EnumStatus::VALID . '"
		';
		return $db->getRow($sql);
	}
	/**
	 * 获得商品的状态
	 * @param array $goods
	 * @param integer $user_id
	 */
	public function getStatus($goods, $user_id)
	{
		$now = time();
		$start_time_stamp = strtotime($goods['start_time']);
		$end_time_stamp = Auction::getEndTimeStamp($goods['end_time']);
		$can_pay = false;
		$show_start_time = true;
		$show_end_time = true;
		$time_status = '';
		if ($start_time_stamp > $now) {
			$time_status = '尚未开始';
		}
		 
		if ($end_time_stamp < $now) {
			$time_status = '已经结束';
		}
		 
		if ($start_time_stamp <= $now && $end_time_stamp >= $now) {
			$time_status = '进行中';
			$show_start_time = false;
			if ($user_id != $goods['create_user']) {
				$can_pay = true;
			}
		}
		
		return array('time_status' => $time_status, 'show_start_time' => $show_start_time, 
			'show_end_time' => $show_end_time, 'can_pay' => $can_pay, 'status_name' => EnumStatus::getStatusName($goods['status']));
	}
	/**
	 * 更改拍卖品的价格
	 * @param integer $price
	 * @param integer $auction_id
	 * @param integer $goods_id
	 */
	public function updatePrice($price, $auction_id, $goods_id)
	{
		$db = db();
		$sql = 'UPDATE ' . $this->table . ' SET curr_price = ' . $price . ', update_time = "' . get_system_date() . '" 
			WHERE auction_id = ' . intval($auction_id) . ' AND goods_id = ' . intval($goods_id);
		$db->query($sql);
	}
	/**
	 * 更新拍卖次数
	 * @param integer $auction_id
	 * @param integer $goods_id
	 */
	public function updatePayNum($auction_id, $goods_id)
	{
		$auction_id = intval($auction_id);
		$goods_id = intval($goods_id);
		$db = db();
		$sql = 'UPDATE ' . $this->table . ' SET pay_num = 
			(SELECT COUNT(records_id) FROM ' . DB_PREFIX . 'auction_records 
				WHERE auction_id = ' . $auction_id . ' AND goods_id = ' . $goods_id . '
			)
			WHERE auction_id = ' . $auction_id . ' AND goods_id = ' . $goods_id;
		$db->query($sql);
		
		//更新拍卖会的出价总数
		$sql = 'UPDATE ' . DB_PREFIX . 'auction SET pay_num = (
			SELECT SUM(pay_num) FROM ' . DB_PREFIX . 'auction_goods WHERE auction_id = ' . $auction_id . '
		) WHERE auction_id = ' . $auction_id;
		$db->query($sql);
	}
	/**
	 * 更新拍卖品的所属对象
	 * @param integer $owner
	 * @param integer $goods_id
	 * @param integer $auction_id
	 */
	public function updateOwner($owner, $goods_id, $auction_id)
	{
		$db = db();
		$sql = 'UPDATE ' . $this->table . ' SET owner = ' . intval($owner) . '
			WHERE goods_id = ' . intval($goods_id) . ' AND auction_id = ' . intval($auction_id);
		return $db->query($sql);
	}
	
        /**
         * 根据产品ID 和拍卖长ID 获取信息
         * @param type $goods_id
         * @param type $auction_id
         * @return type
         */
        public function getInfo($goods_id, $auction_id)
	{
            $sql = 'SELECT * FROM ' . DB_PREFIX . 'auction_goods AS ag 
                INNER JOIN ' . DB_PREFIX . 'goods g ON ag.goods_id = g.goods_id
                AND ag.goods_id = ' . $goods_id . ' AND ag.auction_id = ' . $auction_id;
            return $this->getRow($sql) ;
	}
	
}