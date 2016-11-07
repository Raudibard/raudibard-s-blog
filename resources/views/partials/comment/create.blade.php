<div class="create-comment">

	<br />

	<div class="form-wrapper">
		
		@include('errors.list')

		<form name="createCommentForm" ng-submit="createComment(createCommentForm, {{ $article->id }})" novalidate>

			<div class="reply form-group">
				<h4>Reply to: <span class="text-info" ng-bind-html="createCommentFormData.commentAuthor"></span></h4>
				{!! Form::hidden('commentId', null, [
					'ng-model' 		=> 'createCommentFormData.commentId'
				]) !!}
			</div>

			<div class="form-group">
				{!! Form::textarea('content', null, [
					'rows' 			=> 3,
					'class' 		=> 'form-control',
					'ng-model' 		=> 'createCommentFormData.content',
					'placeholder'   => 'Comment...',
					'required'
				]) !!}
				<div class="error" ng-messages="createCommentForm.content.$error" ng-if="createCommentForm.$submitted" ng-cloak>
					<div ng-message="required">Please enter comment text!</div>
				</div>
			</div>

			<div class="form-group">
				{!! Form::submit('Submit', [
					'class' => 'btn btn-success'
				]) !!}
				{!! Form::button('Cancel', [
					'class' 	=> 'btn btn-link',
					'ng-click' 	=> 'cancelComment()'
				]) !!}
			</div>

		{!! Form::close() !!}

	</div>

</div>