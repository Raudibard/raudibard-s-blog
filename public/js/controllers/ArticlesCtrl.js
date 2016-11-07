
var raudibardApp = angular.module('RaudibardApp');
	raudibardApp.controller('ArticlesCtrl', function($scope, $compile, $timeout, $http, FileUploader, CSRF_TOKEN){

		$http.defaults.headers.get = { 'X-Requested-With' : 'XMLHttpRequest' };

		$scope.articles = [];
		$scope.sortParam = 'created|desc';

		$scope.getArticles = function(){

			jQuery('#articlesEmpty').hide();

			$http.get(baseUrl + 'articles?sort=' + $scope.sortParam).success(function(response){
				jQuery('#articlesPreloader').hide();
				if (response.length) {
					angular.forEach(response, function(article){
						var compiledArticle = $compile(article)($scope);
						angular.element('.articles').append(compiledArticle);
					});
					$scope.refreshColorbox();
				} else {
					jQuery('#articlesEmpty').show();
				}
			});

		}

		$scope.getArticles();

		$scope.$watch('sortParam', function(newValue, oldValue){
			if (newValue != oldValue) {
				jQuery('#articlesPreloader').show();
				jQuery('.articles .article').remove();
				$scope.getArticles();
			}
		});

		$scope.uploaderCreate = new FileUploader({ url: baseUrl + 'upload', queueLimit: 5 });
		$scope.uploaderCreateFiles = [];

		$scope.uploaderCreate.filters.push({
            name: 'imageFilter',
            fn: function(item, options) {
                var type = '|' + item.type.slice(item.type.lastIndexOf('/') + 1) + '|';
                return '|jpg|png|jpeg|bmp|gif|'.indexOf(type) !== -1;
            }
        });

        $scope.uploaderCreate.onBeforeUploadItem = function(item) {
        	item.formData.push({ _token: CSRF_TOKEN });
        }

        $scope.uploaderCreate.onCompleteItem = function(item, response, status, headers) {
        	if (item.isSuccess) {
        		$scope.uploaderCreateFiles.push(response);
        	}
        }

		$scope.createArticle = function(createArticleForm) {
			if (createArticleForm.$valid) {
				$scope.showProcessing();
				if ($scope.uploaderCreate.queue.length) {
					$scope.uploaderCreate.uploadAll();
					$scope.uploaderCreate.onCompleteAll = function() {
						$scope.createArticleObject(createArticleForm, true);
					}
				} else {
					$scope.createArticleObject(createArticleForm);
				}
			}
		}

		$scope.createArticleObject = function(createArticleForm, uploaded = false) {
			if (uploaded) {
				$scope.createArticleFormData._files = $scope.uploaderCreateFiles;
			}
			$scope.createArticleFormData._token = CSRF_TOKEN;
			$http.post(baseUrl + 'articles/create', $scope.createArticleFormData).then(function(response){

				jQuery('#articlesEmpty').hide();

				var compiledData = $compile(response.data)($scope);
				angular.element('.articles').prepend(compiledData);

				$scope.showProcessing(true);
				$scope.refreshColorbox();

				$scope.createArticleFormData = {};
				createArticleForm.$setPristine();
				jQuery('#createArticleFormWrapper').collapse('hide');

				if (uploaded) {
					$scope.uploaderCreateFiles = [];
					$scope.uploaderCreate.clearQueue();
				}

			}, function(response){
				var errors = angular.fromJson(response.data);
				$scope.showProcessing(true);
				$scope.showResponse('Something went wrong:', true, false, errors);
			});
		}

		$scope.refreshArticle = function(id) {
			$http.get(baseUrl + 'articles/' + id).then(function(response){
				jQuery('.btn').tooltip('hide');
				var compiledData = $compile(response.data)($scope);
				angular.element('#article-' + id).replaceWith(compiledData);
				$scope.refreshColorbox();
			});
		}

		$scope.uploaderEdit = new FileUploader({ url: baseUrl + 'upload', queueLimit: 5 });
		$scope.uploaderEditFiles = [];

		$scope.uploaderEdit.filters.push({
            name: 'imageFilter',
            fn: function(item, options) {
                var type = '|' + item.type.slice(item.type.lastIndexOf('/') + 1) + '|';
                return '|jpg|png|jpeg|bmp|gif|'.indexOf(type) !== -1;
            }
        });

        $scope.uploaderEdit.onBeforeUploadItem = function(item) {
        	item.formData.push({ _token: CSRF_TOKEN });
        }

        $scope.uploaderEdit.onCompleteItem = function(item, response, status, headers) {
        	if (item.isSuccess) {
        		$scope.uploaderEditFiles.push(response);
        	}
        }

        $scope.editArticle = function(id) {

			$scope.uploaderEditFiles = [];
			$scope.uploaderEdit.clearQueue();
			$scope.uploaderEdit.queueLimit = 5;

			if (jQuery('.edit-article').length) {
				var article = jQuery('.edit-article').parents('article').attr('id').split('-')[1];
				$scope.refreshArticle(article);
			}

			$timeout(function(){
				$http.get(baseUrl + 'articles/' + id + '/edit').then(function(response){

					var compiledForm = $compile(response.data.html)($scope);
					angular.element('#article-' + id).find('section.content').replaceWith(compiledForm);
					jQuery('#article-' + id).find('.edit-article').find('textarea').focus();
					jQuery('#article-' + id).find('section.photos').prev('hr').remove();
					jQuery('#article-' + id).find('section.photos').remove();

					$scope.editArticleFormData = {};
					$scope.editArticleFormData.content = response.data.content;

					$scope.uploaderEdit.queueLimit -= (response.data.photosAmount < 6) ? response.data.photosAmount : 5;

				});
			}, 100);
			
		}

		$scope.updateArticle = function(editArticleForm, id) {
			if (editArticleForm.$valid) {
				$scope.showProcessing();
				if ($scope.uploaderEdit.queue.length) {
					$scope.uploaderEdit.uploadAll();
					$scope.uploaderEdit.onCompleteAll = function() {
						$scope.updateArticleObject(editArticleForm, id, true);
					}
				} else {
					$scope.updateArticleObject(editArticleForm, id);
				}
			}
		}

		$scope.updateArticleObject = function(editArticleForm, id, uploaded = false) {
			if (uploaded) {
				$scope.editArticleFormData._files = $scope.uploaderEditFiles;
			}
			$scope.editArticleFormData._token = CSRF_TOKEN;
			$http.patch(baseUrl + 'articles/' + id + '/update', $scope.editArticleFormData).then(function(response){

				$scope.refreshArticle(id);

				if (uploaded) {
					$scope.uploaderEditFiles = [];
					$scope.uploaderEdit.clearQueue();
				}

			}, function(response){
				var errors = angular.fromJson(response.data);
				$scope.showProcessing(true);
				$scope.showResponse('Something went wrong:', true, false, errors);
			});
		}

		$scope.deleteArticle = function(id) {
			if (confirm('Are you sure?')) {
				$http.delete(baseUrl + 'articles/' + id + '/delete').then(function(response){
					jQuery('#article-' + id).fadeOut('slow', function(){
						if (!jQuery('.articles .article:visible').length) {
							jQuery('#articlesEmpty').show();
						}
					});
				});
			}
		}

		$scope.ignoreArticle = function(id) {
			$http.post(baseUrl + 'articles/' + id + '/ignore').then(function(){
				jQuery('#article-' + id).fadeOut('slow');
			});
		}

		$scope.likeArticle = function(id, e) {
			$http.post(baseUrl + 'articles/' + id + '/like').then(function(response){
				jQuery(e.currentTarget).toggleClass('btn-success');
				jQuery(e.currentTarget).find('.amount').text(response.data);
			});
		}

		$scope.deleteArticlesPhoto = function(id, e) {
			if (confirm('Are you sure?\nThis photo will be deleted immediately!')) {
				$http.delete(baseUrl + 'remove/' + id).then(function(){
					jQuery(e.currentTarget).parents('tr').fadeOut('slow');
					if ($scope.uploaderEdit.queueLimit < 5) $scope.uploaderEdit.queueLimit ++;
				});
			}
		}

	});