
	jQuery(function() {
		jQuery('[data-wp-form]').FormHandler({
		});
	});
	
		(function( jQuery ){
			jQuery.fn.FormHandler = function(options) {
				
				var ajaxSendForm = false;
				var successFlag = true;
				return this.each(function(i, obj) {
					var checker = (function() {
						return {
							check_NotEmpty: function(input, data) {
								var value = input.val();
								if (value.length<1) {
									input.addClass('error');
									successFlag = false;
									return;
								}
							},
							check_Email: function(input, data) {
								var value = input.val();
								if (value.length<1) {
									input.addClass('error');
									successFlag = false;
									return;
								}
							}							
						}
					}());
					
					var form = jQuery(this);
					
					form.attr('novalidate', 'novalidate');
					
					form.on('submit', function(e) {
						var inputs = form.find('[data-required]');
						successFlag = true;
						inputs.each(function(i, input) {
							var input = jQuery(input);
							var validateData = input.data('required');
							input.removeClass('error');
							
							jQuery.each(validateData, function(type, validateData) {
								var checkTypeFunction = 'check_' + type;
								if (jQuery.isFunction(checker[checkTypeFunction])) {
									checker[checkTypeFunction](input, validateData);
								}								
							});							
						});

						if(form.data('ajax') === 'enable'){
							ajaxSendForm = true;
						}						
						
						if ( ajaxSendForm == true && successFlag ) {
							var dataForm = form.serialize();
							
								jQuery.ajax({
								type: form.attr( 'method' ),
								url: form.attr( 'action' ),
								dataType: form.data('ajaxtype'),
								data: {'data':dataForm},
							});
							return false;
						}else{
							return successFlag;
						}
	
					});					
				});
			}
		})( jQuery );	