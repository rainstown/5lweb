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
			<div class="public_select table">
				<table id="my_auction_goods" server="<?php echo $this->_var['site_url']; ?>/index.php?app=my_auction&amp;act=ajax_col">
					<tr class="line_bold">
						<th colspan="10">
							<div class="select_div">
								<form id="my_auction_form" method="get">
									商品名称:
									<input type="text" class="width3" name="keyword" value="<?php echo htmlspecialchars($_GET['keyword']); ?>" />
									<?php if ($this->_var['filtered']): ?>
									<a class="detlink" style="float: right"
										href="<?php echo url('app=my_auction&act=add_goods'); ?>">取消检索</a>
									<?php endif; ?>
									<input type="hidden" name="app" value="my_auction" />
									<input type="hidden" name="act" value="add_goods" />
									<select class="select1" name='sgcate_id'>
                                		<option value="0">本店分类</option>
                                		<?php echo $this->html_options(array('options'=>$this->_var['sgcategories'],'selected'=>$_GET['sgcate_id'])); ?>
                            		</select>
									<input type="submit" class="btn" value="搜索" />
								</form>
							</div>
						</th>
					</tr>
					<?php if ($this->_var['goods_list']): ?>
					<tr class="gray" ectype="table_header">
						<th width="55"></th>
						<th width="165" column="goods_name" title="排序"  class="cursor_pointer"><span ectype="order_by">商品名称</span></th>
                        <th width="70" column="cate_id" title="排序"  class="cursor_pointer"><span ectype="order_by">商品分类</span></th>
                        <th width="55" column="brand" title="排序"  class="cursor_pointer"><span ectype="order_by">品牌</span></th>
                        <th width="55" class="cursor_pointer" column="price" title="排序"><span ectype="order_by">价格</span></th>
                        <th width="55" class="cursor_pointer" column="stock" title="排序"><span ectype="order_by">库存</span></th>
                        <th>操作</th>
					</tr>
					<?php endif; ?>
					<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['_goods_f'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['_goods_f']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['_goods_f']['iteration']++;
?>
					<tr class="line<?php if (($this->_foreach['_goods_f']['iteration'] == $this->_foreach['_goods_f']['total'])): ?> last_line<?php endif; ?>" ectype="table_item" idvalue="<?php echo $this->_var['auction']['auction_id']; ?>">
						<td width="50" class="align2"><a href="<?php echo url('app=goods&id=' . $this->_var['goods']['goods_id']. ''); ?>" target="_blank"><img src="<?php echo $this->_var['site_url']; ?>/<?php echo $this->_var['goods']['default_image']; ?>" width="50" height="50"  /></a></td>
						<td width="165">
							<p class="ware_text">
								<span class="color2"><?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?></span>
							</p>
						</td>
						<td width="70"><?php echo $this->_var['goods']['cate_name']; ?></td>
                        <td width="55" class="aligin2"><?php echo $this->_var['goods']['brand']; ?></td>
                        <td width="55" class="align2"><?php echo $this->_var['goods']['price']; ?></td>
                        <td width="55" class="align2"><?php echo $this->_var['goods']['stock']; ?></td>
						<td width="" class="align2">
							<a href="javascript:void(0);" ectype="dialog" dialog_id="my_auction_edit_goods" dialog_title="添加拍卖品" dialog_width="600" uri="<?php echo url('app=my_auction&act=edit_goods&goods_id=' . $this->_var['goods']['goods_id']. ''); ?>&auction_id=<?php echo $_GET['id']; ?>" class="edit1 float_none">添加到拍卖会</a>
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