;
var access_set_opts = {
  init:function () {
      this.bindEvent();
  },
  bindEvent:function () {
    $(".access_set_wrap .save").click(function () {
        var btn_target = $(this);
        if(btn_target.hasClass('disabled')) {
            alert('正在处理中，请不要重复提交~');
            return false;
        }
        var title = $(".access_set_wrap input[name='title']").val();
        var urls = $(".access_set_wrap textarea[name='urls']").val();
        if(!title) {
            alert('请输入标题');
            return false;
        }
        if(!urls) {
            alert("请输入urls");
            return false;
        }
        btn_target.addClass("disabled");
        $.ajax({
            url:'/access/set',
            type:'POST',
            data:{
                id:$(".access_set_wrap input[name='id']").val(),
                title:title,
                urls:urls
            },
            dataType:'json',
            success:function (res) {
                alert(res.msg);
                if(res.code == 200) {
                    window.location.href='/access/index'
                }
            }
        })
    })  
  }
};
$(document).ready(function () {
    access_set_opts.init();
});