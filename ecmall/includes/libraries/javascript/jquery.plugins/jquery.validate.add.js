jQuery.validator.addMethod("mobile", function(value, element) {
        return this.optional(element) || /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/.test(value);
}, "请输入正确的手机号码");