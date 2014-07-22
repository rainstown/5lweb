<?php echo $this->fetch('header.html'); ?>
<script type="text/javascript">
	$(function() {
		$('#auction_form').validate({
			errorPlacement : function(error, element) {
				$(element).next('.field_notice').hide();
				$(element).after(error);
			},
			success : function(label) {
				label.addClass('right').text('OK!');
			},
			onkeyup : false,
			rules : {
				name : { required : true, byteRange : [ 1, 100, '<?php echo $this->_var['charset']; ?>' ],},
				start_time : {required: true, date: true},
				end_time : {required : true, date : true},
				sign_start : {required : true, date : true},
				sign_end : {required : true, date : true},
				put_money : {required : true, digits: true, min: 1},
				trade_money : {required : true, digits : true, min : 1},
				keep_money : {required : true, digits : true, min : 1},
				keep_percent : {required : true, digits : true, min : 1, max : 100}
			},
			messages : {
				name : {
					required : '拍卖专场标题不能为空',
					byteRange : '拍卖专场标题长度小于100字'
				},
				start_time : {
					required : '拍卖专场开始时间不能为空',
					date : '日期时间格式不正确'
				},
				end_time : {
					required : '拍卖专场的结束时间不能为空',
					date : '日期时间格式不正确',
				},
				sign_end : {
					required : '报名时间不能为空',
					date : '日期时间格式不正确'
				},
				sign_start : {
					required : '报名时间不能为空',
					date : '日期时间格式不正确'
				},
				put_money : {
					required : '商品上架费不能为空',
					digits : '必须为大于或等于1的整数',
					min : '不能小于1'
				},
				trade_money : {
					required : '商品交易费不能为空',
					digits : '必须为大于或等于1的整数',
					min : '不能小于1'
				},
				keep_money : {
					required : '必填',
					digits : '必须为大于或等于1的整数',
					min : '不能小于1'
				},
				keep_percent : {
					required : '必填',
					digits : '必须为大于或等于1的整数',
					max : '不能超过100',
					min : '不能小于1'
				}
			}
		});
		$('#start_time, #end_time, #sign_start, #sign_end').datepicker({
			dateFormat : 'yy-mm-dd'
		});
	});
</script>
<div id="rightTop">
	<p>拍卖</p>
	<ul class="subnav">
		<li><a class="btn1" href="index.php?app=auction">管理</a></li>
		<li>
			<?php if ($this->_var['auction']['auction_id']): ?>
			 <a class="btn1" href="index.php?app=auction&amp;act=edit">新增</a> 
			 <?php else: ?>
			<span>新增</span> 
			<?php endif; ?>
		</li>
	</ul>
</div>
<div class="info">
	<form method="post" action="<?php echo url('app=auction&act=edit&id=' . $this->_var['auction']['auction_id']. ''); ?>" id="auction_form">
		<input type="hidden" name="auction_id" value="<?php echo $this->_var['auction']['auction_id']; ?>" />
		<table class="infoTable">
			<tr>
				<th class="paddingT15">拍卖会标题:</th>
				<td class="paddingT15 wordSpacing5">
					<input class="infoTableInput" id="name" type="text" name="name" value="<?php echo htmlspecialchars($this->_var['auction']['name']); ?>" style="width: 350px;" /> 
					<label class="field_notice">拍卖会标题</label>
				</td>
			</tr>
			<tr>
				<th class="paddingT15">开始时间:</th>
				<td class="paddingT15 wordSpacing5">
					<input class="infoTableInput2" name="start_time" type="text" id="start_time" value="<?php echo $this->_var['auction']['start_time']; ?>" />
				</td>
			</tr>
			<tr>
				<th class="paddingT15">结束时间:</th>
				<td class="paddingT15 wordSpacing5">
					<input class="infoTableInput2" name="end_time" type="text" id="end_time" value="<?php echo $this->_var['auction']['end_time']; ?>" />
				</td>
			</tr>
			<tr>
				<th class="paddingT15">报名开始时间:</th>
				<td class="paddingT15 wordSpacing5">
					<input class="infoTableInput2" name="sign_start" type="text" id="sign_start" value="<?php echo htmlspecialchars($this->_var['auction']['sign_start']); ?>" class="pick_date" />
				</td>
			</tr>
			<tr>
				<th class="paddingT15">报名截止时间:</th>
				<td class="paddingT15 wordSpacing5">
					<input class="infoTableInput2" name="sign_end" type="text" id="sign_end" value="<?php echo htmlspecialchars($this->_var['auction']['sign_end']); ?>" class="pick_date" />
				</td>
			</tr>

			<tr>
				<th class="paddingT15">状态:</th>
				<td class="paddingT15 wordSpacing5">
					<select name="status">
						<?php echo $this->html_options(array('options'=>$this->_var['status_options'],'selected'=>$this->_var['auction']['status'])); ?>
					</select>
				</td>
			</tr>
			<tr>
				<th class="paddingT15">保证金:</th>
				<td class="paddingT15 wordSpacing5">
					<input class="infoTableInput2" name="keep_money" type="text" id="keep_money" value="<?php echo $this->_var['auction']['keep_money']; ?>" />
				</td>
			</tr>
			<tr>
				<th class="paddingT15">商品上架费用(元/每件):</th>
				<td class="paddingT15 wordSpacing5">
					<input class="infoTableInput2" name="put_money" type="text" id="put_money" value="<?php echo $this->_var['auction']['put_money']; ?>" />
				</td>
			</tr>
			<tr>
				<th class="paddingT15">交易费用(元/每件):</th>
				<td class="paddingT15 wordSpacing5">
					<input class="infoTableInput2" name="trade_money" type="text" id="trade_money" value="<?php echo $this->_var['auction']['trade_money']; ?>" />
				</td>
			</tr>
			<tr>
				<th class="paddingT15">买家保证金(%):</th>
				<td class="paddingT15 wordSpacing5">
					<input class="infoTableInput2" name="keep_percent" type="text" id="keep_percent" value="<?php echo $this->_var['auction']['keep_percent']; ?>">
				</td>
			</tr>
			<tr>
				<th class="paddingT15">描述:</th>
				<td class="paddingT15 wordSpacing5">
					<textarea class="" name="description" id="description"><?php echo $this->_var['auction']['description']; ?></textarea>
				</td>
			</tr>
			<tr>
				<th></th>
				<td class="ptb20">
					<input class="formbtn" type="submit" name="Submit" value="提交" /> 
					<input class="formbtn" type="reset" name="Reset" value="重置" />
				</td>
			</tr>
		</table>
	</form>
</div>
<?php echo $this->fetch('footer.html'); ?>
