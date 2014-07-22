<?php
class Buyer_auctionApp extends MemberbaseApp
{
	private $_mod_auction;
	private $_mod_auction_records;
	private $_mod_auction_goods;
	
	private $_user_id;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->_mod_auction = &m ('auction');
		$this->_mod_auction_goods = &m ('auction_goods');
		$this->_mod_auction_records = &m ('auction_records');
		
		$this->_user_id = intval($this->visitor->get('user_id'));
	}
	
	public function pay_list()
	{
		//$auction_id = isset($_GET['auction_id']) ? intval($_GET['auction_id']) : 0;
		//$goods_id = isset($_GET['goods_id']) ? intval($_GET['goods_id']) : 0;
		/* 当前页面信息 */
		$this->_curlocal(LANG::get('member_center'), 'index.php?app=member',
			LANG::get('buyer_auction'), 'index.php?app=buyer_auction&act=pay_list',
			LANG::get('pay_list'));
		
		
		$this->_curitem('buyer_auction');
		$this->_curmenu('pay_list');
		$this->_config_seo('title', Lang::get('buyer_auction') . ' - ' . Lang::get('pay_list'));
		
		$db = db();
		$select = 'ag.*, g.goods_name, ar.price, ar.user_id, ar.records_id';
		$select_count = '';
		$group_by = 'g.goods_id';
		$tables =  DB_PREFIX . 'auction_goods AS ag
			INNER JOIN ' . DB_PREFIX . 'goods AS g ON g.goods_id = ag.goods_id
			INNER JOIN ' . DB_PREFIX . 'auction_records AS ar ON ar.goods_id = g.goods_id AND ar.auction_id = ag.auction_id
		';
		$condition = ' ar.user_id = ' . $this->_user_id . '
			AND ag.end_time >= "' . get_system_date() . '"
			AND ag.status ="' . EnumStatus::VALID . '"
		';
		$order = 'ar.create_time DESC';
		
		$page = $this->_get_page(1);
		
		$sql_count = 'SELECT COUNT(DISTINCT g.goods_id)' . ' FROM ' . $tables . ' WHERE ' . $condition;
		$count = $db->getOne($sql_count);
		$sql = 'SELECT ' . $select . ' FROM ' . $tables . ' WHERE ' . $condition . ' GROUP BY g.goods_id ' . ' ORDER BY ' .$order . ' LIMIT ' . $page['limit'];
		//获得拍卖品列表
		$goods_list = $db->getAll($sql);
		$page['item_count'] = $count;
		$this->_format_page($page);
		$this->assign('page_info', $page);
		$this->assign('goods_list', $goods_list);
		$this->display('buyer_auction.pay_list.html');
	}
	/**
	 * 我拍到商品
	 */
	public function my_auctions()
	{
		/* 当前页面信息 */
		$this->_curlocal(LANG::get('member_center'), 'index.php?app=member',
			LANG::get('buyer_auction'), 'index.php?app=buyer_auction&act=my_goods',
			LANG::get('my_auctions'));
		
		
		$this->_curitem('buyer_auction');
		$this->_curmenu('my_auctions');
		
		$this->_config_seo('title', Lang::get('buyer_auction') . ' - ' . Lang::get('my_goods'));
		
		$db = db();
		$page = $this->_get_page();
		$condition = 'ag.owner = ' . $this->_user_id . ' AND ar.status="' . EnumStatus::VALID . '" AND ag.end_time < "' . get_system_date() . '"';
		$select = ' g.goods_name, ar.price, ar.keep, ar.pay_status, ar.order_id, ar.records_id, ar.create_time';
		$select_count = 'COUNT(DISTINCT g.goods_id)';
		$select_from =  DB_PREFIX . 'goods AS g
			INNER JOIN ' . DB_PREFIX . 'auction_goods AS ag ON ag.goods_id = g.goods_id
			INNER JOIN ' . DB_PREFIX . 'auction_records AS ar ON ar.goods_id = g.goods_id AND ar.auction_id = ag.auction_id
		';
		
		$sql = 'SELECT ' . $select . ' FROM ' . $select_from . ' 
			WHERE ' . $condition . ' 
			GROUP BY ' . ' g.goods_id
			ORDER BY ar.create_time DESC
			LIMIT ' . $page['limit'] . '
		';
		$sql_count = 'SELECT ' . $select_count . ' FROM ' . $select_from . ' WHERE ' . $condition;
		$goods_list = $db->getAll($sql);
		
		foreach ($goods_list as $i => $goods) {
			$goods_list[$i]['pay_status_name'] = $this->_getPayStatusName($goods['pay_status']);
		}
		
		$count = $db->getOne($sql_count);
		$page['item_count'] = $count;
		$this->_format_page($page);
		$this->assign('page_info', $page);
		$this->assign('goods_list', $goods_list);
		$this->display('buyer_auction.my_auctions.html');
	}
	/**
	 * (non-PHPdoc)
	 * @see MemberbaseApp::_get_member_submenu()
	 */
	public function _get_member_submenu()
	{
		return array(
			array('name' => 'pay_list', 'url' => 'index.php?app=buyer_auction&act=pay_list'),
			array('name' => 'my_auctions', 'url' => 'index.php?app=buyer_auction&act=my_auctions')
		);
	}
	/**
	 * 获得当前拍卖的状态
	 * @param integer $status
	 */
	private function _getPayStatusName($status)
	{
		$name = '未生成订单';
		if ($status == 0) {
			
		}
		else if ($status == 11) {
			$name = '订单已经生成，未付款';
		}
		else if ($status == 20) {
			$name = '卖家未发货';
		}
		else if ($status == 40) {
			$name = '交易完成';
		}
		return $name;
	}
}
?>