@if ($comment->was_removed)
	
	<div class="alert alert-removed small" role="alert">
		<span class="glyphicon glyphicon-trash"></span> Comment was deleted ...
	</div>

@else
	
	<div id="comment-{{ $comment->id }}" class="comment">

		@if (Auth::guest())
			<div class="panel panel-default">
		@elseif (Auth::user()->id == $author->id)
			<div class="panel panel-success">
		@else
			<div class="panel panel-default">
		@endif

			<div class="panel-heading">

				<div class="row">

					<div class="col-md-8 col-xs-12">

						<p class="author small text-info"><span class="glyphicon glyphicon-user"></span> <strong>{{ $author->name }}</strong></p>
						<p class="posted small text-muted">Posted on {{ $comment->created_at->format('j F Y \a\t H:i') }}</p>
						
						@if ($comment->edited_at)
							<p class="edited small text-danger"><span class="glyphicon glyphicon-edit"></span> Edited: {{ $comment->edited_at->diffForHumans() }}</p>
						@endif

					</div>

					<div class="col-md-4 col-xs-12 buttons">

						@if (Auth::guest())

							@if ($comment->counter_likes)
								<span class="likes single" data-toggle="tooltip" title="{{ $comment->counter_likes }} people {{ str_plural('like', $comment->counter_likes) }} this Post">
									<span class="glyphicon glyphicon-thumbs-up"></span> <span class="amount">{{ $comment->counter_likes }}</span>
								</span>
							@endif

						@elseif (Auth::user()->id == $author->id)

							<span class="likes" data-toggle="tooltip" title="{{ $comment->counter_likes }} people {{ str_plural('like', $comment->counter_likes) }} this Post">
								<span class="glyphicon glyphicon-thumbs-up"></span> <span class="amount">{{ $comment->counter_likes }}</span>
							</span>

							<div class="btn-group" role="group">
								<button type="button" class="btn btn-default btn-xs" data-toggle="tooltip" title="Edit Comment" ng-click="editComment({{ $comment->id }})">
									<span class="glyphicon glyphicon-pencil"></span>
								</button>
								<button type="button" class="btn btn-default btn-xs" data-toggle="tooltip" title="Delete Comment" ng-click="deleteComment({{ $comment->id }}, $event)">
									<span class="glyphicon glyphicon-trash"></span>
								</button>
							</div>
							&mdash;
							<button type="button" class="btn btn-default btn-xs" ng-click="replyComment({{ $comment->id }}, $event)">
								<span class="glyphicon glyphicon-share-alt"></span> Reply
							</button>

						@else

							<button type="button" class="btn btn-default btn-xs 
								@if ($comment->wasLiked) 
									btn-success 
								@endif
							" data-toggle="tooltip" title="I like this Comment" ng-click="likeComment({{ $comment->id }}, $event)">
								<span class="glyphicon glyphicon-thumbs-up"></span> <span class="amount">{{ $comment->counter_likes }}</span>
							</button>
							&mdash;
							<button type="button" class="btn btn-default btn-xs" ng-click="replyComment({{ $comment->id }}, $event)">
								<span class="glyphicon glyphicon-share-alt"></span> Reply
							</button>

						@endif
						
					</div>

				</div>

			</div>

			<div class="panel-body">
				{!! formatMyText($comment->content) !!}
			</div>

		</div>

	</div>

@endif