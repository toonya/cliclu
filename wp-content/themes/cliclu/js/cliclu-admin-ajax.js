jQuery(document).ready(function($) {
	var catdata = {
		action: 'cliclu_cat',
		cattitle: ''
	};
	$('.cat-creat-area .sub-cat').click(function(){
		catdata.cattitle = $('#creat-cat-name').val();
		if(!catdata.cattitle){
			alert("no");
						return;
		} 
		// We can also pass the url value separately from ajaxurl for front end AJAX implementations
		jQuery.post(ajax_object.ajax_url, catdata, function(cat_res) {
			if(cat_res){
				//获取最后一个
				$obj = $('.cat-temp');

				//修改参数
				listhtml = $obj.clone(true);
				jQuery(listhtml).removeClass('cat-temp').addClass('cat-li').attr({
					catno:cat_res.id,
					catcount:cat_res.count
				}).find('.cat-title').text(cat_res.name);
				//添加到dom
				$obj.prev().before(listhtml);
				//保存列表			
					
				refresh_list();
			
			}
			else alert('出现错误');
		});
		
		//add list to 
/*
*/
	
	});
	var catdel = {
		action: 'cliclu_cat_del',
		catid: -1
	}
	$('.cat-li .del').live('click',function(){
		catdel.catid = $(this).parent().attr('catno');
		$(this).parent().remove();	
		refresh_list();
		jQuery.post(ajax_object.ajax_url, catdel, function(response) {
			
		});

	});
	
	//refresh list
	function refresh_list(){
		var catlist = {
			action: 'cliclu_cat_list',
			catlist: {}		
		}
		$('.cat-li').each(function(i,n){
			catlist.catlist[i] = $(this).attr('catno');
		});
	
		
		jQuery.post(ajax_object.ajax_url, catlist, function(response) {
			alert('success');
		});
	}
	
	//disable enter key
	$("input").keypress(function (evt) {
		//Deterime where our character code is coming from within the event
		var charCode = evt.charCode || evt.keyCode;
		if (charCode  == 13) { //Enter key's keycode
			return false;
		}
	});
});
