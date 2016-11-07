<div class="panel-body bg-warning">

	<div class="edit-comment">

		<div class="form-wrapper">

			@include('errors.list')

			<form name="editCommentForm" ng-submit="updateComment(editCommentForm, {{ $comment->id }})" novalidate>

				<div class="form-group">
					{!! Form::textarea('content', $comment->content, [
						'rows' 			=> 3,
						'class' 		=> 'form-control',
						'ng-model' 		=> 'editCommentFormData.content',
						'required'
					]) !!}
					<div class="error" ng-messages="editCommentForm.content.$error" ng-if="editCommentForm.$submitted" ng-cloak>
						<div ng-message="required">Please enter comment text!</div>
					</div>
				</div>

				<div class="form-group">
					{!! Form::submit('Save Changes', [
						'class' => 'btn btn-success'
					]) !!}
					{!! Form::button('Discard Changes', [
						'class' 	=> 'btn btn-link',
						'ng-click' 	=> 'refreshComment('.$comment->id.')'
					]) !!}
				</div>

			{!! Form::close() !!}

		</div>

	</div>

</div>