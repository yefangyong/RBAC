;
var access_set_opts = {
    init:function () {
        this.bindEvent();
    },
    bindEvent:function () {
        $(".role_access_set_wrap .save").click(function () {
            var btn_target = $(this);
            if(btn_target.hasClass('disabled')){
                alert("正在处理，请不要重复操作");
                return false;
            }
            var access_ids = [];
            $(".role_access_set_wrap input[name='access_ids[]']").each(function () {
                if($(this).prop('checked')){
                    access_ids.push($(this).val())
                }
            });
            btn_target.addClass('disabled');
            $.ajax({
                url:'/role/access',
                type:'POST',
                data:{
                    id:$(".role_access_set_wrap input[name='id']").val(),
                    access_ids:access_ids
                },
                dataType:'json',
                success:function (res) {
                    btn_target.removeClass('disabled');
                    alert(res.msg);
                    if(res.code == 200) {
                        window.location.href='/role/index';
                    }
                }
            });
        });

    }
}
$(document).ready(function () {
    access_set_opts.init();
})