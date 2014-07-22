<?php echo $this->fetch('member.header.html'); ?>
<script type="text/javascript">
</script>
<div class="content">
	<div class="totline"></div>
	<div class="botline"></div>
	<?php echo $this->fetch('member.menu.html'); ?>
	<div id="right">
		<?php echo $this->fetch('member.submenu.html'); ?>
		<div class="wrap">
			<?php if ($this->_var['can_goods']): ?>
			<div title="添加拍卖品" class="eject_btn_two eject_pos1">
				<b class="ico1"><a href="<?php echo url('app=my_auction&act=add_goods&id=' . $_GET['id']. ''); ?>">添加拍卖品</a></b>
			</div>
			<?php endif; ?>
			<div class="public_select table">
				<table id="my_auction_my_goods" server="<?php echo $this->_var['site_url']; ?>/index.php?app=my_auction&amp;act=ajax_col">
					<tr class="line_bold">
						<th colspan="10">
							<div class="select_div">
								
							</div>
						</th>
					</tr>
					<?php if ($this->_var['goods_list']): ?>
					<tr class="gray" ectype="table_header">
						<th width="55"></th>
						<th width="165" column="goods_name" title="排序"  class="cursor_pointer"><span ectype="order_by">商品名称</span></th>
                        <th width="55">起拍价</th>
                        <th width="55">每次加价</th>
                        <th width="55">当前价格</th>
                        <th width="55">出价次数</th>
                        <th width="55">状态</th>
                        <th width="70" class="cursor_pointer" column="start_time" title="排序"><span ectype="order_by">开始时间</span></th>
                        <th width="70" class="cursor_pointer" column="end_time" title="排序"><span ectype="order_by">结束时间</span></th>
                        <th>操作</th>
					</tr>
					<?php endif; ?>
					<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['_goods_f'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['_goods_f']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['_goods_f']['iteration']++;
?>
					<tr class="line<?php if (($this->_foreach['_goods_f']['iteration'] == $this->_foreach['_goods_f']['total'])): ?> last_line<?php endif; ?>" ectype="table_item" idvalue="<?php echo $this->_var['goods']['goods_id']; ?>">
						<td width="50" class="align2"><a href="<?php echo url('app=goods&id=' . $this->_var['goods']['goods_id']. ''); ?>" target="_blank"><img src="<?php echo $this->_var['site_url']; ?>/<?php echo $this->_var['goods']['default_image']; ?>" width="50" height="50"  /></a></td>
						<td width="165">
							<p class="ware_text">
								<span class="color2"><?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?></span>
							</p>
						</td>
						<td width="55" class="aligin2"><?php echo $this->_var['goods']['min_price']; ?></td>
                        <td width="55" class="aligin2"><?php echo $this->_var['goods']['step_price']; ?></td>
                        <td width="55" class="align2"><?php echo $this->_var['goods']['curr_price']; ?></td>
                        <td width="55" class="align2"><?php echo $this->_var['goods']['pay_num']; ?></td>
                        <td width="55" class="align2"><?php echo $this->_var['goods']['status']; ?></td>
                        <td width="70" class="align2"><?php echo $this->_var['goods']['start_time']; ?></td>
                        <td width="70" class="align2"><?php echo $this->_var['goods']['end_time']; ?></td>
						<td width="" class="align2">
							<?php if ($this->_var['goods']['can_edit']): ?>
							<a href="javascript:void(0);" ectype="dialog" dialog_id="my_auction_edit_goods" dialog_title="编辑拍卖品" dialog_width="600" uri="<?php echo url('app=my_auction&act=edit_goods&goods_id=' . $this->_var['goods']['goods_id']. ''); ?>&auction_id=<?php echo $_GET['id']; ?>&id=<?php echo $this->_var['goods']['id']; ?>" class="edit1 float_none">编辑拍卖品</a>
							<?php endif; ?>
						</td>
					</tr>
					<?php endforeach; else: ?>
					<tr>
						<td class="align2 member_no_records padding6" colspan="10"><?php echo $this->_var['lang'][$_GET['act']]; ?>没有符合条件的记录</td>
					</tr>
					<?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
					<?php if ($this->_var['goods_list']): ?>
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