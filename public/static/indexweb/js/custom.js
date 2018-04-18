function ReComment_CallBack(){for(var i=0;i<=ReComment_CallBack.list.length-1;i++){ReComment_CallBack.list[i]()}}
ReComment_CallBack.list=[];
ReComment_CallBack.add=function(s){ReComment_CallBack.list.push(s)};
//重写了common.js里的同名函数
function RevertComment(i){
	$("#inpRevID").val(i);
	var frm=$('#divCommentPost'),cancel=$("#cancel-reply"),temp = $('#temp-frm');
	var div = document.createElement('div');
	div.id = 'temp-frm';
	div.style.display = 'none';
	frm.before(div);
	$('#AjaxCommentEnd'+i).before(frm);
	frm.addClass("reply-frm");
	cancel.show();
	cancel.click(function(){
		$("#inpRevID").val(0);
		var temp = $('#temp-frm'), frm=$('#divCommentPost');
		if ( ! temp.length || ! frm.length )return;
		temp.before(frm);
		temp.remove();
		$(this).hide();
		frm.removeClass("reply-frm");
		ReComment_CallBack();
		return false;
	});
	try { $('#txaArticle').focus(); }
	catch(e) {}
	ReComment_CallBack();
	return false;
}
$(document).ready(function() {
    var s = document.location;
    $(".nav a").each(function() {
        if (this.href == s.toString().split("#")[0]) {
            $(this).addClass("current");
            return false
        }
    });
    $(".nav li").hover(function() {
        $(this).find('ul:first').slideDown("fast").css({
            display: "block"
        })
    },
    function() {
        $(this).find('ul:first').slideUp("fast").css({
            display: "none"
        })
    });
    $(".topbar #loading").animate({
        width: "100%"
    },
    800,
    function() {
        setTimeout(function() {
            $(".topbar #loading").fadeOut(500)
        })
    })
});
$(window).scroll(function() {
    if ($(this).scrollTop() > 200) {
        $("#head").addClass("new-header");
        $('.topbar').css('display', 'none')
    } else {
        $("#head").removeClass("new-header");
        $('.topbar').css('display', 'block')
    }
    if ($(this).scrollTop() > 200) {
        $('#gotop-fixed').css('bottom', '183px')
    } else {
        $('#gotop-fixed').css('bottom', '-385px')
    }
});