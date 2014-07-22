<?php echo $this->fetch('member.header.html'); ?>
<div class="content">
	<div class="totline"></div>
	<div class="botline"></div>
	<?php echo $this->fetch('member.menu.html'); ?>
	<div id="right">
		<?php echo $this->fetch('member.submenu.html'); ?>
		<div class="wrap">
			<div class="public_select table">
				<table id="my_auction" server="<?php echo $this->_var['site_url']; ?>/index.php?app=my_auction&amp;act=ajax_col">
					<?php if ($this->_var['auction_list']): ?>
					<tr class="gray" ectype="table_header">
						<th width="200">标题</th>
						<th width="80"><span>卖家保证金</span></th>
						<th width="80"><span>上架费</span></th>
						<th width="80"><span>交易费</span></th>
						<th width="80"><span>买家保证金</span></th>
						<th width="80"><span>拍卖品数量</span></th>
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
						
						<td width="200">
							<p class="ware_text">
								<span class="color2"><?php echo htmlspecialchars($this->_var['auction']['name']); ?></span>
							</p>
						</td>
						<td width="80" class="align2">
							<span class="color2"><?php echo $this->_var['auction']['keep_money']; ?></span>
						</td>
						<td width="80" class="align2">
							<span class="color2"><?php echo $this->_var['auction']['put_money']; ?></span>
						</td>
						<td width="80" class="align2">
							<span class="color2"><?php echo $this->_var['auction']['trade_money']; ?></span>
						</td>
						<td width="80" class="align2">
							<span class="color2"><?php echo $this->_var['auction']['keep_percent']; ?></span>
						</td>
						<td width="80" class="align2">
							<span class="color2"><?php echo $this->_var['auction']['goods_num']; ?></span>
						</td>
						<td width="" class="align2">
							<?php echo $this->_var['auction']['user_status_name']; ?>
							<?php if ($this->_var['auction']['can_goods']): ?>
							<a href="<?php echo url('app=my_auction&act=add_goods&id=' . $this->_var['auction']['auction_id']. ''); ?>">添加拍卖品</a>
							<a href="<?php echo url('app=my_auction&act=my_goods&id=' . $this->_var['auction']['auction_id']. ''); ?>">已添加</a>
							<?php endif; ?>
							<?php if ($this->_var['auction']['can_apply']): ?>
							<a href="<?php echo url('app=auction&act=apply&id=' . $this->_var['auction']['auction_id']. ''); ?>">报名</a>
							<?php endif; ?>
							<?php echo $this->_var['auction']['apply_status']; ?>
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
