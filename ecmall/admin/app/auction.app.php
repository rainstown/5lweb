<?php
/**
 * add Auction and manage them
 * @author shearf
 *
 */
import('auction');
import('enum_status');

class AuctionApp extends BackendApp
{
	private $_auction_mod;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->_auction_mod = & m('auction');
	}
	/**
	 * 后台拍卖管理
	 */
	public function index()
	{
		$search_options = array(
			'start_time' => Lang::get('start_time'),
			'end_time' => Lang::get('end_time'),
			'sign_start' => Lang::get('sign_start_time'),
			'sign_end' => Lang::get('sign_end_time'),
			'create_time' => Lang::get('create_time'),
		);
		array_key_exists($_GET['field'], $search_options) && $field = $_GET['field'];
		
		$conditions = $this->_get_query_conditions(array(
			array(
				'field' => 'status',
				'equal' => '=',
			),
			array(
				'field' => $field,
				'equal' => '>=',
				'name' => 'search_time_from',
			),
			array(
				'field' => $field,
				'equal' => '<=',
				'name' => 'search_time_to',
			),
			array(
				'field' => 'name',
				'equal'	=> 'LIKE',
			),
			array(
				'field' => 'create_time',
				'equal' => '=>',
				'name'	=> 'create_time_from'
			),
			array(
				'field' => 'create_time',
				'equal' => '=<',
				'name'	=> 'create_time_to',
			),
		));
		
		$page = $this->_get_page(10);
		//更新排序
		if (isset($_GET['sort']) && isset($_GET['order'])) {
			$sort  = strtolower(trim($_GET['sort']));
            $order = strtolower(trim($_GET['order']));
            if (!in_array($order,array('asc','desc')))
            {
            	$sort  = 'create_time';
            	$order = 'desc';
            }
		}
		else {
			$sort = 'create_time';
			$order = 'desc';
		}
		
		$auctions = $this->_auction_mod->find(array(
			'join' => 'creator',
			'conditions' => ' type="' . Auction::TYPE_OFFICAL . '"' . $conditions,
			'limit'	=> $page['limit'],
			'order'	=> "$sort $order",
			'count'	=> true
		));
		
		$mod_auction_user = &m('auction_user');
		$auction_user = $mod_auction_user->find(
			array(
				'conditions' => 'status = "' . EnumStatus::NOT_APPROVE . '"',		
			)
		);
		//获得已经报名的用户拍卖会
		$user_count = array();
		foreach ($auction_user as $a_u) {
			if (isset($user_count[$a_u['auction_id']])) {
				$user_count[$a_u['auction_id']]++;
			}
			else {
				$user_count[$a_u['auction_id']] = 1;
			}
		}
		
		$page['item_count'] = $this->_auction_mod->getCount();
		
		$this->_format_page($page);
		
		$this->assign('filtered', $conditions ? 1 : 0 );
		
		$this->assign('search_options', $search_options);
		$this->assign('status_options', Auction::getOfficalStatusOptions());
		$this->assign('selected_status', EnumStatus::VALID);
		
		$this->assign('page_info', $page);
		$now = time();
		foreach ($auctions as $index => $auction) {
			$auctions[$index]['status_name'] = EnumStatus::getStatusName($auction['status']);
			$auctions[$index]['create_user_name'] = $auction->creator->user_name;
			if (strtotime($auction['sign_start']) <= $now && Auction::getEndTimeStamp($auction['sign_end']) > $now 
				&& $user_count[$auction['auction_id']] > 0) 
			{
				$auctions[$index]['has_user'] = true;
			}
			else {
				$auctions[$index]['has_user'] = false;
			}
		}
		$this->assign('auctions', $auctions);
		
		$this->import_resource(array('script' => 'inline_edit.js,jquery.ui/jquery.ui.js,jquery.ui/i18n/' . i18n_code() . '.js',
			'style'=> 'jquery.ui/themes/ui-lightness/jquery.ui.css'));
		
		$this->display('auction.index.html');
	}
	/**
	 * 编辑
	 */
	public function edit()
	{
		$auction_id = $_GET['id'] ? intval($_GET['id']) : 0;
		if (!IS_POST) {
			$auction = $this->_auction_mod->get_info($auction_id);
			$auction['status'] = $auction_id > 0 ? $auction.status : EnumStatus::VALID;
			
			$this->assign('auction', $auction);
			$this->assign('status_options', Auction::getOfficalStatusOptions());
			
			/* 导入jQuery的表单验证插件 */
			//import datepicker
			$this->import_resource(array('script' => 'jquery.plugins/jquery.validate.js,jquery.ui/jquery.ui.js,jquery.ui/i18n/' . i18n_code() . '.js',
					'style'=> 'jquery.ui/themes/ui-lightness/jquery.ui.css'));
			$this->display('auction.form.html');
		}
		else {
			$auction_name = trim($_POST['name']);
			$desc = trim($_POST['description']);
			$start_time = trim($_POST['start_time']);
			$end_time = trim($_POST['end_time']);
			$sign_end = trim($_POST['sign_end']);
			$sign_start = trim($_POST['sign_start']);
			$status = $_POST['status'];
			$keep_money = intval($_POST['keep_money']);
			$put_money = intval($_POST['put_money']);
			$trade_money = intval($_POST['trade_money']);
			$keep_percent = intval($_POST['keep_percent']);
			//数据验证
			$start_timestamp = strtotime($start_time);
			$end_timestamp = Auction::getEndTimeStamp($end_time);
			$sign_start_timestamp = strtotime($sign_start);
			$sign_end_timestamp = Auction::getEndTimeStamp($sign_end);
			
			if ($sign_start_timestamp - $sign_end_timestamp > 0) {
				$this->show_warning(Lang::get('auction_sign_end_earlier_than_sign_start'));
				return ;
			}
			
			if ($start_timestamp - $end_timestamp >=0 ) {
				$this->show_warning(Lang::get('auction_start_time_later_than_end_time'));
				return ;
			}
			
			if ($sign_end_timestamp - $start_timestamp > 0) {
				$this->show_warning(Lang::get('auction_start_time_earlier_than_sign_end'));
				return ;
			}
			
			if ($sign_start_timestamp - time() <= 0) { //临时注释，方便测试验收;
				$this->show_warning(Lang::get('auction_sign_start_time_earlier_than_now'));
				return ;
			} 
			
			if ($keep_money < $put_money) {
				$this->show_warning(Lang::get('auction_keep_money_must_larger_than_put_money'));
				return ;
			}
			$data = array(
				'auction_id' => $auction_id,
				'name' => $auction_name,
				'status' => $status,
				'type' => Auction::TYPE_OFFICAL,
				'start_time' => $start_time,
				'sign_end' => $sign_end,
				'sign_start' => $sign_start,
				'end_time' => $end_time,
				'put_money' => $put_money,
				'keep_money' => $keep_money,
				'keep_percent' => $keep_percent,
				'trade_money' => $trade_money,
				'description' => $desc,
				'create_time' => get_system_time(),
				'create_user' => $this->visitor->get('user_id'), 
			);
			
			if ($auction_id > 0) {
				$this->_auction_mod->edit($auction_id, $data);
			}
			else {
				$this->_auction_mod->add($data);
			}
			
			$msg = $auction_id > 0 ? 'edit_ok' : 'add_ok';
			$this->show_message($msg,
				'back_list',    'index.php?app=auction',
				'continue_add', 'index.php?app=auction&amp;act=edit'
			);
		}
	}
	/**
	 * 查看专场拍卖
	 */
	public function view()
	{
		$auction_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		
		$auction = $this->_auction_mod->get_info($auction_id);
		
		if ($auction) {
			
			$auction['status_name'] = Auction::getStatusName($auction['status']);
			$this->assign('auction', $auction);
			$this->display('auction.view.html');
		}
		else {
			$this->show_warning(Lang::get('auction_not_exists'));
			return ;
		}
	}
	/**
	 * 个人拍卖管理
	 */
	public function personal()
	{
		$search_field = $_GET['search_field'];
		$conditions = $this->_get_query_conditions(array(
			array(
				'field' => 'status',
				'equal' => '='
			),
			array(
				'field' => $search_field,
				'equal' => '>=',
				'name' => 'search_time_from',
			),
			array(
				'field' => $search_field,
				'equal' => '<=',
				'name' => 'search_time_to',
			),
			array(
				'field' => 'name',
				'equal' => 'LIKE',
			)
		));
		$search_options = array(
			'start_time' => Lang::get('start_time'),
			'end_time' => Lang::get('end_time'),
			'create_time' => Lang::get('create_time'),
		);
		
		//更新排序
		if (isset($_GET['sort']) && isset($_GET['order'])) {
			$sort  = strtolower(trim($_GET['sort']));
			$order = strtolower(trim($_GET['order']));
			if (!in_array($order,array('asc','desc')))
			{
				$sort  = 'create_time';
				$order = 'desc';
			}
		}
		else {
			$sort = 'create_time';
			$order = 'desc';
		}
		
		$page = $this->_get_page();
		$auctions = $this->_auction_mod->find(array(
			'join' => 'creator',
			'conditions' => ' type="' . Auction::TYPE_PERSONAL . '" ' . $conditions,
			'limit'	=> $page['limit'],
			'order'	=> "$sort $order",
			'count'	=> true
		));
		$page['item_count'] = $this->_auction_mod->getCount();
		
		$this->_format_page($page);
		
		foreach ($auctions as $index => $auction) {
			$auctions[$index]['status_name'] = EnumStatus::getStatusName($auction['status']);
			$auctions[$index]['can_approve'] = $auction['status'] != EnumStatus::PASS;
		}
		
		$this->import_resource(array('script' => 'jquery.plugins/jquery.validate.js,jquery.ui/jquery.ui.js,jquery.ui/i18n/' . i18n_code() . '.js',
			'style'=> 'jquery.ui/themes/ui-lightness/jquery.ui.css'));
		
		$this->assign('filtered', $conditions == '' ? false : true);
		$this->assign('status_options', EnumStatus::getPAuctionStatus());
		$this->assign('search_options', $search_options);
		$this->assign('page_info', $page);
		$this->assign('auctions', $auctions);
		$this->display('auction.personal.html');
		
	}
	/**
	 * 审核
	 */
	public function approve()
	{
		$auction_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		
		if (!IS_POST) {
			$auction = $this->_auction_mod->get_info($auction_id);
			if ($auction) {
				$status_options = array(
					EnumStatus::PASS => EnumStatus::getStatusName(EnumStatus::PASS),
					EnumStatus::DENY => EnumStatus::getStatusName(EnumStatus::DENY),
				);
				
				
				$this->import_resource(array('script' => 'jquery.plugins/jquery.validate.js,jquery.ui/jquery.ui.js,jquery.ui/i18n/' . i18n_code() . '.js',
					'style'=> 'jquery.ui/themes/ui-lightness/jquery.ui.css'));
				
				$this->assign('status_options', $status_options);
				$this->assign('auction', $auction);
				$this->display('auction.approve.html');
			}
			else {
				$this->show_warning(Lang::get('auction_not_exists'));
				return ;
			}
		}
		else {
			
			$status = $_POST['status'];
			$put_money = intval($_POST['put_money']);
			$keep_money = intval($_POST['keep_money']);
			$trade_money = intval($_POST['trade_money']);
			
			if ($keep_money < $put_money) {
				$this->show_warning(Lang::get('auction_keep_money_must_larger_than_put_money'));
				return ;
			}
			
			$data = array(
				'status' => $_POST['status'],
				'keep_money' => $keep_money,
				'put_money' => $put_money,
				'trade_money' => $trade_money,
			);
			
			if ($status == EnumStatus::DENY) {
				/**
				 * @todo send msg to tell creator why denied 
				 */
			}
			else
				//if ($status == EnumStatus::PASS)
			{
				$arr_date = $this->_auction_mod->get_info($auction_id);

				$this->payCautionMoney($arr_date['create_user'], $auction_id, $keep_money);
			}
                        
			$this->_auction_mod->edit($auction_id, $data);
			//审核通过加入到拍卖会用户中
			if ($status == EnumStatus::PASS) {
				$arr_data = $this->_auction_mod->get_info($auction_id);
				$_auction_user = &m('auction_user');
				$data = array(
					'auction_id' => $auction_id,
					'user_id' => $arr_data['create_user'],
					'status' => EnumStatus::PASS,
					'create_time' => get_system_time(),
					'approve_time' => get_system_time(),
				);
				$_auction_user->add($data);
			}
                        
			$this->show_message('approve_ok', 'back_list', 'index.php?app=auction&act=personal');
		}
	}
	/**
	 * 报名用户列表
	 */
	public function apply_list()
	{
		$auction_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		$auction = $this->_auction_mod->get_info($auction_id);
		
		$db = &db();
		$users = $db->getAll('SELECT au.*, m.user_name FROM ' . DB_PREFIX . 'auction_user AS au 
				INNER JOIN ' . DB_PREFIX . 'member AS m ON au.user_id = m.user_id
				WHERE au.auction_id = ' . $auction_id . ' AND au.status = "' . EnumStatus::NOT_APPROVE . '"
		');
		$this->assign('users', $users);
		$this->assign('auction_name', $auction['name']);
		$this->display('auction.apply_list.html');
	}
	/**
	 * 审核报名用户
	 */
	public function apply()
	{
		$apply = isset($_GET['apply']) ? $_GET['apply'] : '';
		$status = '';
		if ($apply == 'Y') {
			$status = EnumStatus::PASS;
		}
		else if ($apply == 'N') {
			$status = EnumStatus::DENY;
		}
		
		if ($status) {
                    //锁定保证金;
                   
			$mod_auction_user = &m('auction_user');
			$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
			$auction_id = isset($_GET['auction_id']) ? intval($_GET['auction_id']) : 0;
                       
                        $array_user = $mod_auction_user->get($id);
                        if (empty($array_user) || $array_user['status'] != 'N')
                        {//拒绝不存在或者已经操作过
                            $this->show_warning(Lang::get('error_operate'));
                            return;
                        }
                        if ($status == EnumStatus::PASS)
                        {
                            $this->payCautionMoney($array_user['user_id'], $auction_id);
                        }
			$data = array(
				'status' => $status,
				'approve_time' => get_system_time(),
			);
			$mod_auction_user->edit($id, $data);
			$this->show_message('approve_ok', 'back_list', 'index.php?app=auction&act=apply_list&id=' . $auction_id);
		}
		else {
			$this->show_warning(Lang::get('error_operate'));
			return ;
		}
 	}
        
        /**
         * 审核通过，获取锁定保证金;
         */
         public function payCautionMoney($bank_id, $auction_id, $keep_money = '')
         {
             $mod_auction = &m('auction');
             $array_data = $mod_auction->get($auction_id);
             $auction_name = $array_data['name'];
             if (empty($array_data))
             {
             	$this->show_warning(Lang::get('error_operate'));
		 		exit;
             }
             if ($keep_money)
             {
                 $array_data['keep_money'] = $keep_money;
             }
             if ($array_data['keep_money'] > 0) //有设置保证金
             {
                 $mod_bank = &m('bank');
                 $arr_bank_info = $mod_bank->get($bank_id);
                 if (empty($arr_bank_info) 
                 ||$arr_bank_info['money'] < $array_data['keep_money'])
                {
                	$this->show_warning(Lang::get('error_operate'));
                    exit;
                }
                //资金转移到保证金账户
                $money = $array_data['keep_money'];
                $mod_bank->payAccountToCautionMoney($bank_id, $money );
                
                $mod_user = &m('member');
                $arr_user = $mod_user->get($bank_id);
                $buyer_name = $arr_user['user_name'];
                /* 记录订单操作日志 */
                $seller_id = '';
                $seller_name = '';
                $pay_name = $auction_name.'保证金';
                $pay_log = & m('pay');
                $pay_log->add(array(
                    'order_id'  => $auction_id,
                    'type'      => PAY_TYPE_CAUTION,
                    'buyer_id'  => $bank_id,
                    'buyer_name' => $buyer_name,
                    'seller_id'   => $seller_id,
                    'seller_name'   => $seller_name,
                    'money'     =>  $money,
                    'status'     =>  PAY_ACCEPTED,
                    'pay_name'      => $pay_name,
                
                    
                    'create_time'=> gmtime(),
                    'create_user'=> $_SESSION['admin_info']['user_id']
                ));
             }
         }
         
         /**
          * 退还拍卖会的保证金
          * @param type $user_id
          * @param type $auction_id
          * @return boolean
          */
         public function  refundAuctionMoney($user_id, $auction_id)
         {
             $mod_auction = &m('auction');
             $array_data = $mod_auction->get($auction_id);
             $auction_name = $array_data['name'];
             if (empty($array_data))
             {
             	$this->show_warning(Lang::get('error_operate'));
		 		exit;
             }
             //获取已扣的保证金记录
             $pay = & m('pay');
             $arr_data = $pay->getAuctionInfo($user_id, $auction_id);
            
             if ($arr_data['status'] == PAY_ACCEPTED) //已付保证金
             {
                $money = $arr_data['money'];
                $parent_id = $arr_data['pay_id'];
                
                 /* 记录订单操作日志 */
                $seller_id = '';
                $seller_name = '';
                $pay_name = '退还'.$auction_name.'保证金';
                $pay_log = & m('pay');
                
                $pay_log->add(array(
                    'order_id'  => $auction_id,
                    'parent_id' => $parent_id,
                    'type'      => PAY_TYPE_CAUTION,
                    'buyer_id'  => '0',
                    'buyer_name' => '',
                    'seller_id'   => $bank_id,
                    'seller_name'   => '',
                    'money'     =>  $money,
                    'status'     =>  PAY_FINISHED,
                    'pay_name'      => $pay_name,
                    'create_time'=> gmtime(),
                    'create_user'=> $_SESSION['admin_info']['user_id']
                ));
                
                //更新扣保证金记录为已完成状态
                $pay = m('pay');
                $pay->edit($parent_id, array('status' => PAY_FINISHED, 'update_time' => gmtime(), 'update_user'=>$_SESSION['admin_info']['user_id']));
                //把钱从保证金账户返回真实账户
                $bank = m('bank');
                $bank->payCautionMoneyToAccount($bank_id, $money);
                return true;
             }
             else {
                 $this->show_warning(Lang::get('error_operate'));
                  exit;
             }
             
             
         }
    /**
     * @deprecated
     * 拍卖会结束处理退款
     */
	public function back_money()
	{
		$condition = '
			end_time < "' . get_system_date() . '" AND back_money = 0 AND type != "' . Auction::TYPE_MARKET . '"
		';
		$page = $this->_get_page();
		$auctions = $this->_auction_mod->find(array(
			'join' => 'creator',
			'conditions' => $condition,
			'limit'	=> $page['limit'],
			'order'	=> "back_money ASC, end_time DESC",
			'count'	=> true
		));
		$page['item_count'] = $this->_auction_mod->getCount();
		
		foreach ($auctions as $i => $auction) {
			$auctions[$i]['auction_type'] = Auction::getTypeName($auction['type']);
		}
		
		$this->_format_page($page);
		$this->assign('page_info', $page);
		$this->assign('auctions', $auctions);
		
		$this->display('auction.back_money.html');
	}
	/**
	 * 退保证金
	 */
	public function refund()
	{
		$user_id = intval($_GET['id']);
		$auction_id = intval($_GET['auction_id']);
		/**
		 * @todo 退保证金
		 */
                $this->refundAuctionMoney($user_id, $auction_id);
                //设置保证金已退还的操作;
		$_auction_user = &m('auction_user');
		$_auction_user->backMoney($user_id, $auction_id);
		
		$this->show_message('refund_ok');
	}
	
	public function market()
	{
		$auctions = $this->_auction_mod->find(array(
			'conditions' => 'type = "' . Auction::TYPE_MARKET . '"',
		));
		
		$auction_id = key($auctions);
		$_auction_user = &m('auction_user');
		$arr_user = $_auction_user->getAuctionUser($auction_id);
		$auctions[$auction_id]['apply_user_num'] = 0;
		$auctions[$auction_id]['auction_user_num'] = 0;
		$auctions[$auction_id]['need_back_money'] = false;
		foreach ($arr_user as $user) {
			if ($user['status'] == EnumStatus::NOT_APPROVE) {
				$auctions[$auction_id]['has_user_apply'] = true;
				$auctions[$auction_id]['apply_user_num']++;
			}
			else if ($user['status'] == EnumStatus::PASS) {
				$auctions[$auction_id]['auction_user_num']++;
				
				if (empty($user['back_money'])) {
					$auctions[$auction_id]['need_back_money'] = true;
				}
			}
		}
		
		$page = $this->_get_page();
		$page['item_count'] = 1;
		
		$this->_format_page($page);
		$this->assign('page_info', $page);
		
		$this->assign('auctions', $auctions);
		
		$this->display('auction.market.html');
	}
	/**
	 * user list action
	 */
	public function user()
	{
		$auction_id = intval($_GET['id']);
		
		$page = $this->_get_page(3);
		$db = db();
		$select = 'u.user_name, au.*';
		$select_count = 'COUNT(au.user_id)';
		$tables = ' ' . DB_PREFIX . 'auction_user AS au INNER JOIN ' . DB_PREFIX . 'member AS u ON u.user_id = au.user_id';
		$where = ' au.status ="' . EnumStatus::PASS . '" AND au.auction_id = ' . $auction_id;
		$sql = 'SELECT ' . $select . ' FROM ' . $tables . ' WHERE ' . $where . ' LIMIT ' . $page['limit'];
		$sql_count = 'SELECT ' . $select_count . ' FROM ' . $tables . ' WHERE ' . $where;
		$user_list = $db->getAll($sql);
		$count = $db->getOne($sql_count);
		$page['item_count'] = $count;
		
		$auction = $this->_auction_mod->get_info($auction_id);
		$this->assign('auction_name', $auction['name']);
		
		$this->_format_page($page);
		$this->assign('page_info', $page);
		$this->assign('user_list', $user_list);
		$this->display('auction.user.html');
	}
}