// To prevent reclicks while animating
var progressing = false;

jQuery(function(){

	//Starting download
	jQuery('.sp_free_access .sp_button.download').click(function(event){
		event.preventDefault();

		jQuery(this).attr('class','sp_button download cancel');
		file = jQuery(this).attr('id').split('_'); file = file[1];

		var timer = jQuery(this).prevAll('.sp_timer');
		var term  = jQuery('.sp_term').html();
		var timer_count = parseInt(timer.html());
		if(term == 'minutes' && timer_count == 1) { timer_count = parseInt(jQuery('#timer_minimum').val()); term = 'seconds'; }

		if( timer_count > 0 ) {

			if(term == 'seconds') {

				waiting_seconds = setInterval(function() {

					timer_count--;

					timer.html(timer_count);

					if(timer_count == 0) { 
						clearInterval(waiting_seconds); 
						jQuery.post(wpajax_url, { action: 'sp_ajax', file: file }, function(url) { 
							window.location.href = url;
						});
						return; 
					}

				}, 1000);

			} else {

				waiting_mins = setInterval(function() {

					timer_count--;

					timer.html(timer_count);

					if(timer_count == 1) {
						jQuery('.sp_timer').html(60);
						jQuery('.sp_term').html('seconds');

						secs_count = 60;
						waiting_secs = setInterval(function() {
							secs_count--;
							timer.html(secs_count);
							if(secs_count == 0) { 
								clearInterval(waiting_secs);
								clearInterval(waiting_mins); 
								jQuery.post(wpajax_url, { action: 'sp_ajax', file: file }, function(url) { 
									window.location.href = url;
								});
								return; 
							}
						}, 1000);

					}

				}, 60000);

			}

			

		}
	});

	jQuery('.sp_pro_access .sp_button.register,.sp_pro_access .sp_button.login').click(function(event){
		event.preventDefault();
		action = jQuery(this).attr('class').split(' '); action = action[1];

		if(progressing==false) {
			progressing=true;
			jQuery(this).parent().fadeOut('normal', function(){
				jQuery('#'+ action +'_form').slideToggle();
				progressing = false;
			});
		}
	});

	jQuery('.sp_pro_access .sp_button.cancel').click(function(event){
		event.preventDefault();

		if(progressing==false) {
			progressing=true;
			jQuery(this).parent().parent().slideToggle(function(){
				jQuery('#promotion').fadeIn('normal');
				progressing = false;
			});
		}
	});

	jQuery('.sp_pro_access .sp_button.download').click(function(event){
		event.preventDefault();
		file = jQuery(this).attr('id').split('_'); file = file[1];

		jQuery.post(wpajax_url, { action: 'sp_ajax', file: file }, function(url) { 
			window.location.href = url;
		});
	});

});