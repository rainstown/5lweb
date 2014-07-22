<?php echo $this->fetch('member.header.html'); ?>
<script type="text/javascript">
$(document).ready(function(){
	$('#my_auction_from_date').datepicker({dateFormat: 'yy-mm-dd'});
    $('#my_auction_to_date').datepicker({dateFormat: 'yy-mm-dd'});
});
</script>
<div class="content">
	<div class="totline"></div>
	<div class="botline"></div>
	<?php echo $this->fetch('member.menu.html'); ?>
	<div id="right">
		<?php echo $this->fetch('member.submenu.html'); ?>
		<div class="wrap">
			<div class="public_select table">
				<table id="my_auction" server="<?php echo $this->_var['site_url']; ?>/index.php?app=my_auction&amp;act=ajax_col">
					<tr class="line_bold">
						<th colspan="10">
							<div class="select_div">
								<form id="my_auction_form" method="get">
									标题:
									<input type="text" class="width3" name="keyword" value="<?php echo htmlspecialchars($_GET['keyword']); ?>" />
									<?php if ($this->_var['filtered']): ?>
									<a class="detlink" style="float: right"
										href="<?php echo url('app=my_auction&act=apply_list'); ?>">取消检索</a>
									<?php endif; ?>
									<input type="hidden" name="app" value="my_auction" />
									<input type="hidden" name="act" value="apply_list" />
									状态:
									<select name="status">
										<option value="">请选择</option>
										<?php echo $this->html_options(array('options'=>$this->_var['status_options'],'selected'=>$_GET['status'])); ?>
									</select>
									<select name="search_field">
										<?php echo $this->html_options(array('options'=>$this->_var['search_fields'],'selected'=>$_GET['search_field'])); ?>
									</select>:
									<input type="text" class="text_normal width2" name="date_from" value="<?php echo $_GET['date_from']; ?>" id="my_auction_from_date" /> - 
									<input type="text" class="text_normal width2" name="date_to" value="<?php echo $_GET['date_to']; ?>" id="my_auction_to_date" />
									<input type="submit" class="btn" value="搜索" />
								</form>
							</div>
						</th>
					</tr>
					<?php if ($this->_var['auction_list']): ?>
					<tr class="gray" ectype="table_header">
						<th width="165" column="name" title="排序" class="cursor_pointer">
							<span ectype="order_by">标题</span>
						</th>
						<th width="70" column="start_time" title="排序"
							class="cursor_pointer"><span ectype="order_by">开始时间</span></th>
						<th width="70" column="end_time"
							title="排序" class="cursor_pointer"><span ectype="order_by">结束时间</span>
						</th>
						<th width="25" column="status" title="排序" class="cursor_pointer">
							<span ectype="order_by">状态</span>
						</th>
						<th width="55"><span>卖家保证金</span></th>
						<th width="55"><span>上架费</span></th>
						<th width="55"><span>交易费</span></th>
						<th width="55"><span>买家保证金</span></th>
						<th width="55"><span>拍卖品数量</span></th>
						<th>操作</th>
					</tr>
					<?php endif; ?>
					<?php $_from = $this->_var['auction_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'auction');$this->_foreach['_auction_f'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['_auction_f']['total'] > 0):
    foreach ($_from AS $this->_var['auction']):
        $this->_foreach['_auction_f']['iteration']++;
?>
					<tr
						class="line<?php if (($this->_foreach['_auction_f']['iteration'] == $this->_foreach['_auction_f']['total'])): ?> last_line<?php endif; ?>"
						ectype="table_item" idvalue="<?php echo $this->_var['auction']['auction_id']; ?>">
						
						<td width="165">
							<p class="ware_text">
								<span class="color2"><?php echo htmlspecialchars($this->_var['auction']['name']); ?></span>
							</p>
						</td>
						<td width="65"><span class="color2"><?php echo htmlspecialchars($this->_var['auction']['start_time']); ?></span></td>
						<td width="50" class="align2">
							<span class="color2"><?php echo htmlspecialchars($this->_var['auction']['end_time']); ?></span>
						</td>
						
						<td width="50" class="align2">
							<span class="color2"><?php echo $this->_var['auction']['status_name']; ?></span>
						</td>
						<td width="50" class="align2">
							<span class="color2"><?php echo $this->_var['auction']['keep_money']; ?></span>
						</td>
						<td width="50" class="align2">
							<span class="color2"><?php echo $this->_var['auction']['put_money']; ?></span>
						</td>
						<td width="50" class="align2">
							<span class="color2"><?php echo $this->_var['auction']['trade_money']; ?></span>
						</td>
						<td width="50" class="align2">
							<span class="color2"><?php echo $this->_var['auction']['keep_percent']; ?></span>
						</td>
						<td width="50" class="align2">
							<span class="color2"><?php echo $this->_var['auction']['goods_num']; ?></span>
						</td>
						<td width="" class="align2">
							<?php echo $this->_var['auction']['user_status_name']; ?>
							<?php if ($this->_var['auction']['can_apply']): ?>
							<a href="<?php echo url('app=my_auction&act=apply&auction_id=' . $this->_var['auction']['auction_id']. '&id=' . $this->_var['auction']['id']. ''); ?>">报名</a>
							<?php endif; ?>
							<?php if ($this->_var['auction']['can_goods']): ?>
							<a href="<?php echo url('app=my_auction&act=add_goods&id=' . $this->_var['auction']['auction_id']. ''); ?>">添加拍卖品</a>
							<?php endif; ?>
							<a href="<?php echo url('app=my_auction&act=my_goods&id=' . $this->_var['auction']['auction_id']. ''); ?>">已添加</a>
						</td>
					</tr>
					<?php endforeach; else: ?>
					<tr>
						<td class="align2 member_no_records padding6" colspan="10"><?php echo $this->_var['lang'][$_GET['act']]; ?>没有符合条件的记录</td>
					</tr>
					<?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
					<?php if ($this->_var['auction_list']): ?>
					<tr class="line_bold line_bold_bottom">
						<td colspan="11"></td>
					</tr>
					<tr>
						<th colspan="10">
							<p class="position2"><?php echo $this->fetch('member.page.bottom.html'); ?></p>
						</th>
					</tr>
					<?php endif; ?>
				</table>
			</div>
			<div class="wrap_bottom"></div>
		</div>
		<div class="clear"></div>
		<div class="adorn_right1"></div>
		<div class="adorn_right2"></div>
		<div class="adorn_right3"></div>
		<div class="adorn_right4"></div>
	</div>
	<div class="clear"></div>
</div>


<iframe name="iframe_post" id="iframe_post" width="0" height="0"></iframe>
<?php echo $this->fetch('footer.html'); ?> 
