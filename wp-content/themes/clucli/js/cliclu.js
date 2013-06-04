jQuery(function($){
	jQuery('.cat-list .creat').live('click',function(){
		if(!$(this).hasClass('open')){
			$(this).addClass('open');
			$('.cat-creat-area').css('right','0');
		}
		else{
			$(this).removeClass('open');
			
			$('.cat-creat-area').css('right','-20%');
		}
	});

	jQuery('.custom_upload_image_button').click(function() {
		formfield = jQuery('.custom_upload_image');
		preview = jQuery('.custom_preview_image');
		tb_show('', 'media-upload.php?type=image&TB_iframe=true');
		window.send_to_editor = function(html) {
			imgurl = jQuery('img',html).attr('src');
/*
			classes = jQuery('img', html).attr('class');
			id = classes.replace(/(.*?)wp-image-/, '');
			formfield.val(id);
*/
			formfield.val(imgurl);
			preview.attr('src', imgurl);
			tb_remove();
		}
		return false;
	});
	
	jQuery('.custom_clear_image_button').click(function() {
		jQuery('.custom_upload_image').val('');
		preview = jQuery('.custom_preview_image');
		newpre = preview.clone(true);
		jQuery(newpre).attr('src','');
		preview.after(newpre).remove();
		return false;
	});
	jQuery('.defalt-img li').click(function(){
		jQuery(this).addClass('selected').siblings('li').removeClass('selected');
		jQuery('.custom_upload_image').val(jQuery(this).find('img').attr('src'));
		jQuery('.custom_preview_image').attr('src',jQuery(this).find('img').attr('src'));
	})
	
})
