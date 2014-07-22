<script type="text/javascript">
//<!CDATA[
$(function(){
	$('#auction_start_time, #auction_end_time').datepicker({
		dateFormat : 'yy-mm-dd'
	});
    $('#my_auction_form').validate({
    	/*
        errorPlacement: function(error, element){
            var _message_box = $(element).parent().find('.field_message');
            _message_box.find('.field_notice').hide();
            _message_box.append(error);
        },
        success       : function(label){
            label.addClass('validate_right').text('OK!');
        },*/
        
        
        errorLabelContainer: $('#warning'),
        invalidHandler: function(form, validator) {
           var errors = validator.numberOfInvalids();
           if(errors)
           {
               $('#warning').show();
           }
           else
           {
               $('#warning').hide();
           }
        },
        onkeyup : false,
        rules : {
            name : {
                required : true
            },
            start_time : {
                required : true,
                date   : true
            },
            end_time   : {
                required : true,
                date: true
            },
            keep_percent : {
                required : true,
                digits : true,
                min : 1,
                max : 100
            }
        },
        messages : {
            name : {
                required : '标题必填. '
            },
            start_time : {
            	required : '开始时间必填',
            	date : '开始时间日期格式不正确，应该为yy-mm-dd'
            },
            end_time : {
            	required : '结束时间必填',
            	date : '结束时间日期格式不正确，应该为yy-mm-dd'
            },
            keep_percent : {
            	required : '买家保证金必填',
            	min : '买家保证金必须大于或等于1',
            	max : '买家保证金form_max_100',
            	digits : '买家保证金必须为整数'
            }
        }
    });
});

//]]>
</script>
<ul class="tab">
    <li class="active">
    	<?php if ($_GET['id']): ?>
    	编辑个人拍卖
    	<?php else: ?>
    	新增个人拍卖会
    	<?php endif; ?>
    </li>
</ul>
<div class="eject_con">
<div class="add">
    <div id="warning"></div>
    <form method="post" action="index.php?app=my_auction&act=edit&id=<?php echo $this->_var['auction']['auction_id']; ?>" id="my_auction_form" target="iframe_post">
    <ul>
        <li>
            <h3>标题: </h3>
            <p>
            	<input type="text" class="text width_normal" name="name" value="<?php echo htmlspecialchars($this->_var['auction']['name']); ?>" style="width: 300px;"/>
            	<label class="field_message"><span class="field_notice"></span></label>
            </p>
        </li>
        <li>
            <h3>开始时间: </h3>
            <p>
            	<input type="text" class="text width_normal" name="start_time" value="<?php echo htmlspecialchars($this->_var['auction']['start_time']); ?>" id="auction_start_time" />
            	<label class="field_message"><span class="field_notice">不能早于当前日期</span></label>
            </p>
        </li>
        <li>
            <h3>结束时间: </h3>
            <p>
            	<input type="text" class="text width_normal" name="end_time" value="<?php echo htmlspecialchars($this->_var['auction']['end_time']); ?>" id="auction_end_time" />
            	<label class="field_message"><span class="field_notice"></span></label>
            </p>
        </li>
        <li>
            <h3>买家保证金(%):</h3>
            <p>
            	<input type="text" class="text width_normal"  name="keep_percent" value="<?php echo $this->_var['auction']['keep_percent']; ?>"/>
            	<label class="field_message"><span class="field_notice">占拍卖商品金额的百分比</span></label>
            </p>
        </li>
        <li>
            <h3>描述:</h3>
            <p>
            	<textarea name="description" row="36" col="10"><?php echo $this->_var['auction']['description']; ?></textarea>
            	
            </p>
        </li>
    </ul>
    <div class="submit"><input type="submit" class="btn" value="<?php if ($this->_var['auction']['auction_id']): ?>编辑个人拍卖<?php else: ?>新增个人拍卖会<?php endif; ?>" /></div>
    </form>
</div>
</div>
