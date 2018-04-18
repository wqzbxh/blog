/****************************************************************
 *																*		
 * 						      为卿执笔博客						*
 * 																*
****************************************************************/
$(function(){
	$('.login_btn').on('click',function(){
		var remember = 'noSelect';
		var telephone = $(" input[ name='telephone' ] ").val();
		var password = $(" input[ name='password' ] ").val();
	    remember = $('input:radio[name="remember"]:checked').val();
	    $.post("loginAction",{'telephone':telephone,'password':password,'remember':remember,},function(res){
			console.log(res);
			if(res['code'] == 1){
					alert(res['info']);
					$(location).attr('href', '../index/index');
				}else{
					alert(res['info']);
				}
			});
	})
})