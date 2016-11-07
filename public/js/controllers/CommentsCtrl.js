
var raudibardApp = angular.module('RaudibardApp');
	raudibardApp.controller('CommentsCtrl', function($scope, $compile, $timeout, $http, CSRF_TOKEN){

		$http.defaults.headers.get = { 'X-Requested-With' : 'XMLHttpRequest' };

		$scope.cancelComment = function(){
			$scope.createCommentFormData = {};
			var obj = jQuery('.create-comment:visible');
			if (jQuery(obj).length) {
				jQuery(obj).slideUp('fast', function(){
					jQuery(this).find('.reply').hide();
				});
			}
		}

		$scope.writeComment = function(e, replyToId = false){

			$scope.cancelComment();

			var obj = jQuery(e.currentTarget).parents('.comments').find('.create-comment');

			jQuery(obj).slideDown('fast', function(){
				scrollTo(obj);
				jQuery(this).find('textarea').focus();
				if (replyToId) {
					jQuery(this).find('.reply').slideDown('fast');
				}
			});

			if (replyToId) {
				var author = jQuery('#comment-' + replyToId).find('.author').html();
				$scope.createCommentFormData = {};
				$scope.createCommentFormData.commentId = replyToId;
				$scope.createCommentFormData.commentAuthor = author;
			}

		}

		$scope.refreshArticle = function(articleId, commentId) {
			$http.get(baseUrl + 'articles/' + articleId).then(function(response){
				
				jQuery('.btn').tooltip('hide');
				var compiledData = $compile(response.data)($scope);
				angular.element('#article-' + articleId).replaceWith(compiledData);

				$scope.refreshColorbox();

				var obj = jQuery('#comment-' + commentId);
				scrollTo(obj, true);

			});
		}

		$scope.createComment = function(createCommentForm, articleId) {
			if (createCommentForm.$valid) {
				$scope.showProcessing();
				$scope.createCommentFormData._token = CSRF_TOKEN;
				$scope.createCommentFormData.articleId = articleId;
				$http.post(baseUrl + 'comments/create', $scope.createCommentFormData).then(function(response){
					$scope.refreshArticle(articleId, response.data);
				}, function(response){
					var errors = angular.fromJson(response.data);
					$scope.showProcessing(true);
					$scope.showResponse('Something went wrong:', true, false, errors);
				});
			}
		}

		$scope.refreshComment = function(id) {
			$http.get(baseUrl + 'comments/' + id).then(function(response){
				jQuery('.btn').tooltip('hide');
				var compiledData = $compile(response.data)($scope);
				angular.element('#comment-' + id).replaceWith(compiledData);
			});
		}

		$scope.replyComment = function(id, e) {
			$scope.writeComment(e, id);
		}

		$scope.editComment = function(id) {

			if (jQuery('.edit-comment').length) {
				var comment = jQuery('.edit-comment').parents('.comment').attr('id').split('-')[1];
				$scope.refreshComment(comment);
			}

			$timeout(function(){
				$http.get(baseUrl + 'comments/' + id + '/edit').then(function(response){

					var compiledForm = $compile(response.data.html)($scope);
					angular.element('#comment-' + id).find('.panel-body').replaceWith(compiledForm);
					jQuery('#comment-' + id).find('.edit-comment').find('textarea').focus();

					$scope.editCommentFormData = {};
					$scope.editCommentFormData.content = response.data.content;

				});
			}, 100);
			
		}

		$scope.updateComment = function(editCommentForm, id) {
			if (editCommentForm.$valid) {
				$scope.showProcessing();
				$scope.editCommentFormData._token = CSRF_TOKEN;
				$http.patch(baseUrl + 'comments/' + id + '/update', $scope.editCommentFormData).then(function(){
					$scope.refreshComment(id);
				}, function(response){
					var errors = angular.fromJson(response.data);
					$scope.showProcessing(true);
					$scope.showResponse('Something went wrong:', true, false, errors);
				});
			}
		}

		$scope.deleteComment = function(id, e) {
			if (confirm('Are you sure?')) {
				$http.delete(baseUrl + 'comments/' + id + '/delete').then(function(response){
					if (response.data.refresh) {
						$scope.refreshComment(id);
					} else {
						jQuery('#comment-' + id).parents('.htree-row').fadeOut('slow');
					}
					jQuery(e.currentTarget).parents('.comments').find('h4').find('.badge').text(response.data.counter);
				});
			}
		}

		$scope.likeComment = function(id, e) {
			$http.post(baseUrl + 'comments/' + id + '/like').then(function(response){
				jQuery(e.currentTarget).toggleClass('btn-success');
				jQuery(e.currentTarget).find('.amount').text(response.data);
			});
		}

	})