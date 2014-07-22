<?php echo $this->fetch('header.html'); ?>
<script type="text/javascript">
$(function(){
    $('#in_funds_form').validate({
        errorPlacement: function(error, element){
            $(element).next('.field_notice').hide();
            $(element).after(error);
        },
        success       : function(label){
            label.addClass('right').text('OK!');
        },
         onkeyup    : false,
        rules : {
            user_name : {
                required : true,
                byteRange: [3,15,'<?php echo $this->_var['charset']; ?>'],
                remote   : {
                    url :'index.php?app=user&act=check_user',
                    type:'get',
                    data:{
                        user_name : function(){
                            return $('#user_name').val();
                        },
                        id : '<?php echo $this->_var['user']['user_id']; ?>'
                    }
                }
            },
            money : {
                number   : true
            }
        },
        messages : {
             user_name : {
                required : 'user_not_empty',
                byteRange: 'user_length_limit',
                remote   : '正确'
            },
            money  : {
                number   : 'number_only'
            }
        }
    });
});
</script>
<div id="rightTop">
    <p>管理</p>
    <ul class="subnav">
        <li><a class="btn1" href="index.php?app=funds">管理</a></li>
        <li><span>充值</span></li>

    </ul>
</div>

<div class="info">
    <form method="post" enctype="multipart/form-data" id="in_funds_form">
        <table class="infoTable">
            <tr>
            <th class="paddingT15"> 会员账户:</th>
            <td class="paddingT15 wordSpacing5">
              <input class="infoTableInput2" id="user_name" type="text" name="user_name" />
              <label class="field_notice">会员账户</label>
             </td>
          </tr>
          <tr>
            <th class="paddingT15"> 金额:</th>
            <td class="paddingT15 wordSpacing5">
              <input class="infoTableInput2" id="money" type="text" name="money" value="<?php echo htmlspecialchars($this->_var['user']['money']); ?>" />
              </td>
          </tr>
          <tr style="display: none;">
            <th class="paddingT15"> type:</th>
            <td class="paddingT15 wordSpacing5">
               <select class="querySelect" name="type"><?php echo $this->html_options(array('options'=>$this->_var['options_type'],'selected'=>$this->_var['type'])); ?>
                </select>
              </td>
          </tr>
          <tr>
            <th class="paddingT15"> 资金名称:</th>
            <td class="paddingT15 wordSpacing5">
                <input class="infoTableInput2" id="funds_name" type="text" name="funds_name" value="" />
              </td>
          </tr>
          <tr>
            <th class="paddingT15"> 银行开户行:</th>
            <td class="paddingT15 wordSpacing5">
               <input class="infoTableInput2" id="name" type="text" name="name" value="" />
              </td>
          </tr>
           <tr>
            <th class="paddingT15"> 账户名称:</th>
            <td class="paddingT15 wordSpacing5">
                <input class="infoTableInput2" id="account_name" type="text" name="account_name" />
                <label class="field_notice"></label>
              </td>
          </tr>
          <tr>
            <th class="paddingT15"> 银行账户:</th>
            <td class="paddingT15 wordSpacing5">
                <input class="infoTableInput2" id="account" type="text" name="account" />
              </td>
          </tr>
         
          <tr>
            <th class="paddingT15"> 备注:</th>
            <td class="paddingT15 wordSpacing5">
                <input class="infoTableInput2" id="remark" type="text" name="remark" />
                <label class="field_notice">备注</label>
              </td>
          </tr>
            
        <tr>
            <th></th>
            <td class="ptb20">
                <input class="formbtn" type="submit" name="Submit" value="提交" />
                <input class="formbtn" type="reset" name="Submit2" value="重置" />
            </td>
        </tr>
        </table>
    </form>
</div>
<?php echo $this->fetch('footer.html'); ?>
