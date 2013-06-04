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
	
	 var ty_uploader;
	 var ty_reciever;
	 
	 // Bind to our click event in order to open up the new media experience.
	 $(document.body).on('click.tyOpenMediaManager', '.ty-open-media', function(e){ //ty-open-media is the class of our form button
	 
		 // Prevent the default action from occuring.
		 e.preventDefault();
		 
		// Get our Parent element
		 ty_reciever = jQuery(this).parent();
		 
		 // If the frame already exists, re-open it.
		 if ( ty_uploader ) {
			 ty_uploader.open();
		 return;
		 }
		 ty_uploader = wp.media.frames.ty_uploader = wp.media({
		 
		//Create our media frame
			 className: 'media-frame ty-media-frame',
			 frame: 'select', //Allow Select Only
			 multiple: true, //Disallow Mulitple selections
			 library: {
			 type: 'image' //Only allow images
			 },
		 });
		 
/**
 *  选择
 */
		 ty_uploader.on('select', function(){
		 // Grab our attachment selection and construct a JSON representation of the model.
		 var media_attachment = ty_uploader.state().get('selection').map( function( attachment ) {
											  attachment = attachment.toJSON();
											  return attachment; 
											  });		 
		// Send the attachment URL to our custom input field via jQuery.

		imgurl = media_attachment[0].url;
		
	  	formfield = jQuery('.custom_upload_image');
		
		preview = jQuery('.custom_preview_image');
		
		formfield.val(imgurl);
		
		preview.attr('src', imgurl);

		 });
		 
		// Now that everything has been set, let's open up the frame.
		 ty_uploader.open();
	 });

	
})
