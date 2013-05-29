jQuery(document).ready(function($) {
	var catdata = {
		action: 'cliclu_cat',
		cattitle: ''
	};
	var catlist = {
		action: 'cliclu_cat_list',
		catlist: {}		
	}

	$('.cat-list .sub-cat').click(function(){
		catdata.cattitle = $('#creat-cat-name').val();
		if(!catdata.cattitle){
			alert("no");
						return;
		} 
		alert(catdata.cattitle);
		// We can also pass the url value separately from ajaxurl for front end AJAX implementations
		jQuery.post(ajax_object.ajax_url, catdata, function(response) {
			alert(response);
		});
		
		//add list to 
		$('.cat-title').not('.creat').each(function(i,n){
			catlist.catlist[i] = $(this).parent().attr('catno');
		});

		
		jQuery.post(ajax_object.ajax_url, catlist, function(response) {
			$.each(response,function(k,v){
				alert(v);
			});
		});
	
	})
});
