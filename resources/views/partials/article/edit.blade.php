<section class="content">

	<div class="edit-article bg-warning">

		<div class="form-wrapper">

			@include('errors.list')

			<form name="editArticleForm" ng-submit="updateArticle(editArticleForm, {{ $article->id }})" novalidate>

				<div class="form-group">
					{!! Form::textarea('content', $article->content, [
						'rows' 			=> 6,
						'class' 		=> 'form-control',
						'ng-model' 		=> 'editArticleFormData.content',
						'ng-minlength' 	=> 10,
						'required'
					]) !!}
					<div class="error" ng-messages="editArticleForm.content.$error" ng-if="editArticleForm.$submitted" ng-cloak>
						<div ng-message="required">Please enter post text!</div>
						<div ng-message="minlength">This text is too short, isn't it? It must be at least 10 characters!</div>
					</div>
				</div>

				<div class="form-group">
					<span class="btn btn-primary btn-file">
					    Browse Files <input type="file" nv-file-select uploader="uploaderEdit" multiple />
					</span>
					<span class="btn-file-info">
						<span class="glyphicon glyphicon-arrow-left"></span> You can attach <span ng-bind="uploaderEdit.queueLimit">5</span> more pictures: <strong>jpeg, png, jpg, gif</strong> with maximum size of <strong>2 Mb</strong> for each file.
					</span>
				</div>

				<table class="table table-condensed">
					<tr ng-repeat="item in uploaderEdit.queue" ng-cloak>
						<td ng-thumb="{ file: item._file, width: 100 }"></td>
						<td>@{{ item.file.name }}</td>
						<td><strong>@{{ item.file.size/1024/1024|number:2 }} MB</strong></td>
						<td>
							<div class="progress">
								<div class="progress-bar progress-bar-success" role="progressbar" ng-style="{ 'width': item.progress + '%' }"></div>
							</div>
						</td>
						<td>
							<span ng-show="item.isSuccess"><i class="glyphicon glyphicon-ok"></i></span>
							<span ng-show="item.isError"><i class="glyphicon glyphicon-ban-circle"></i></span>
						</td>
						<td><span ng-show="!item.isUploading && !item.isUploaded"><button class="btn btn-default btn-sm" ng-click="item.remove()">Cancel</button></span></td>
					</tr>
				</table>

				@if ($photos->count())

					<table class="table table-condensed">

						<thead>
							<tr>
								<th colspan="2">Current attached photos:</th>
							</tr>
						</thead>

						<tbody>

							@foreach ($photos as $photo)

								<tr>
									<td>{{ Html::image(url('uploads' . $photo->filethumb)) }}</td>
									<td><a role="button" class="btn btn-warning btn-sm" ng-click="deleteArticlesPhoto({{ $photo->id }}, $event)">Delete</a></td>
								</tr>

							@endforeach

						</tbody>

					</table>

				@endif

				<div class="form-group">
					{!! Form::submit('Save Changes', [
						'class' => 'btn btn-success'
					]) !!}
					{!! Form::button('Discard Changes', [
						'class' 	=> 'btn btn-link',
						'ng-click' 	=> 'refreshArticle('.$article->id.')'
					]) !!}
				</div>

			{!! Form::close() !!}

		</div>

	</div>

</section>