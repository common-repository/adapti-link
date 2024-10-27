(function( $ ) {
	'use strict';

	 var status = {
	 	tests: [
	 		function(cb){
	 			$.ajax({
					type: "HEAD",
					url: window.api_url + 'api/',
				}).done(function(res){
					cb();
				}).fail(function(){
					status.done(false, 'The Adapti website is currently down.');
				})
	 		},
	 		function(cb){
	 			$.ajax({
 					type: 'POST',
 					url: ajaxurl,
 					data: { 
 						action: 'get_token'
 					}
 				}).done(function(data) {
 					console.log(data);
		 			if(data.token != null){
		 				window.adapti.token = data.token;
		 				cb();
		 			}
		 			else{
		 				status.done(false, 'Please enter an API key.');
		 			}
	 			}).fail(function(){

	 				status.done(false, 'Ahem ... Is the plugin properly installed ?');
	 			});
	 			console.log(this);
	 		},
	 		function(cb){
	 			$.ajax({
 					type: 'POST',
 					url: window.api_url + 'api/check',
 					data: { token_check: window.adapti.token }
 				}).done(function(data) {
					if(data.check == true){
						cb();
					}
					else{
						status.done(false, data.msg);
					}
				}).fail(function(){
					status.done(false, 'The Adapti website is having some trouble.');
				})
	 		},
	 		function(cb){
	 			if(true){
	 				cb();
	 			}
	 			else{
	 				status.done(false, 'There is no valid regex that match your website.');
	 			}
	 		}
	 	],
	 	done: function(ok, msg){
	 		var msg_ok = 'Connected';
	 		var msg_ko = 'Not connected';

	 		//if(ok){
	 			//$('.statuscheck .updateStatus').removeClass("updateStatus--red").removeClass("updateStatus--green").addClass('updateStatus--green');
	 			//$('.statuscheck .updateStatus').text(msg_ok);
	 		//}
	 		//else{
	 			$('.statuscheck .updateStatus').removeClass("updateStatus--red").removeClass("updateStatus--green").addClass('updateStatus--red');
	 			$('.statuscheck .updateStatus').text(msg_ko);
	 		//}
	 	},
	 	recurseCheck: function(tests){
	 		if(tests.length){
	 			var test = tests.shift();
	 			test(function(){
	 				this.recurseCheck(tests);
	 			}.bind(this));
	 		}
	 		else{
	 			this.done(true);
	 		}
	 	},
	 	check: function(){
	 		$('.status .text .text-spotlight').text('');
	 		$('.status .text .text-light').text('');
	 		this.recurseCheck([].concat(this.tests));
	 	}
	 }


	 $(document).on('ready', function(){

	 	if(window.adapti != undefined){
		 	$('.step.token').find('input[name=token]').val(window.adapti.token);
		 	$('.status .update').on('click', function(e){
		 		status.check();
		 	});

		 	status.check();
            
            
            $.ajax({
                type: 'POST',
                url: window.api_url + 'api/version',
                data: { 
                },
                success: function(data) {
                    if(data['version'] == $(".versioncheck").attr("data-version")) {
                        $(".versioncheck .updateStatus").addClass("updateStatus--green").text("Up to date");
                    } else {
                        $(".versioncheck .updateStatus").addClass("updateStatus--red").text("Please update");
                    }
                },
                fail: function(data) {
                    
                }
            })
            
		 	var stepIndex = 0;
		 	if(window.adapti.account != null){
		 		if(window.adapti.token != null){
		 			//stepIndex = 2;
		 		}
		 		else{
		 			//stepIndex = 1;
		 		}
		 	}

		 	//steps.goTo(stepIndex);
            $('.statuscheck').find('.adapti-button').on('click', function(){
	 				var token = $('.statuscheck').find('#token').val();
	 				var error = $('.step.token').find('.error_msg');
	 				error.html('');
	 				$.ajax({
	 					type: 'POST',
	 					url: window.api_url + 'api/check',
	 					data: { token: token, token_check:token },
                        success: function(data) {
                            if(data.check == true){
				                error.html('');
                                $.ajax({
                                    type: 'POST',
                                    url: ajaxurl,
                                    data: { 
                                        action: 'set_token',
                                        token: token 
                                    },
                                    success: function(data) {
                                        location.reload();
                                    },
                                    fail: function(data) {
                                        error.html('Please contact our support. Error #000_b100');
                                    }
                                })
                            }
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) { 
                            error.html("Token seems to be invalid.");
                        }  
                    });
	 			});
            
		 }
     });

})( jQuery );
