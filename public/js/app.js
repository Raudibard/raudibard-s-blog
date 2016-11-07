
jQuery(function(){

	jQuery('body').tooltip({ selector: '[data-toggle="tooltip"]', container: 'body', trigger : 'hover' });

	jQuery('body').on('click', '.btn', function(){ $(this).blur(); });

});

function scrollTo(target, effect = false) {
	jQuery('html, body').animate({
		scrollTop: target.offset().top - 100
	}, 'fast', function(){
		if (effect) {
			jQuery(target).animate({ opacity:.2 }, 'fast').animate({ opacity:1 }, 'fast');
		}
	});
}

raudibardApp.run(function($rootScope) {
	
	$rootScope.showResponse = function(caption, error = false, replace = true, errors = []) {
		var list = '';
		var alert = 'alert-success';
		var glyphicon = 'glyphicon-ok-sign';
		if (error) {
			list = '<ul></ul>';
			alert = 'alert-danger';
			glyphicon = 'glyphicon-remove-sign';
		}
		var obj = jQuery('.form-wrapper:visible');
		var template = '<div class="alert ' + alert + '" role="alert"><span class="glyphicon ' + glyphicon + '"></span>' + caption + list + '</div>';
		if (replace) {
			obj.replaceWith(template);
		} else {
			if (obj.find('.alert').length) {
				obj.find('.alert').replaceWith(template);
			} else {
				obj.prepend(template);
			}
		}
		if (errors) {
			var drop = obj.find('.alert').find('ul');
			jQuery.each(errors, function(key, value){
				drop.append('<li>' + value + '</li>')
			});
		}
	}

	$rootScope.showProcessing = function(revert = false){
		var template = '<div class="preloader"><img src="' + baseUrl + 'images/loader-circle.gif" alt="Loading..."> Processing...</div>';
		if (revert) {
			jQuery('.form-wrapper:visible').find('.form-group:last').find('.btn').show();
			jQuery('.form-wrapper:visible').find('.form-group:last').find('.preloader').remove();
		} else {
			jQuery('.form-wrapper:visible').find('.form-group:last').find('.btn').hide();
			jQuery('.form-wrapper:visible').find('.form-group:last').append(template);
		}
	}

	$rootScope.refreshColorbox = function () {
		jQuery('.article .photos a').colorbox({ maxWidth: '95%' });
	}

});