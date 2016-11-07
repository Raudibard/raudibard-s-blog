<article id="article-{{ $article->id }}" class="article">

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

					<p class="text-info"><span class="glyphicon glyphicon-user"></span> <strong>{{ $author->name }}</strong></p>
					<p class="posted small text-muted">Posted on {{ $article->created_at->format('j F Y \a\t H:i') }}</p>

					@if ($article->edited_at)
						<p class="edited small text-danger"><span class="glyphicon glyphicon-edit"></span> Edited: {{ $article->edited_at->diffForHumans() }}</p>
					@endif

				</div>

				<div class="col-md-4 col-xs-12 buttons">

					@if (Auth::guest())

						@if ($article->counter_likes)
							<span class="likes single" data-toggle="tooltip" title="{{ $article->counter_likes }} people {{ str_plural('like', $article->counter_likes) }} this Post">
								<span class="glyphicon glyphicon-thumbs-up"></span> <span class="amount">{{ $article->counter_likes }}</span>
							</span>
						@endif

					@elseif (Auth::user()->id == $author->id)

						<span class="likes" data-toggle="tooltip" title="{{ $article->counter_likes }} people {{ str_plural('like', $article->counter_likes) }} this Post">
							<span class="glyphicon glyphicon-thumbs-up"></span> <span class="amount">{{ $article->counter_likes }}</span>
						</span>

						<div class="btn-group" role="group">
							<button type="button" class="btn btn-default" data-toggle="tooltip" title="Edit Post" ng-click="editArticle({{ $article->id }})">
								<span class="glyphicon glyphicon-pencil"></span>
							</button>
							<button type="button" class="btn btn-default" data-toggle="tooltip" title="Delete Post" ng-click="deleteArticle({{ $article->id }})">
								<span class="glyphicon glyphicon-trash"></span>
							</button>
						</div>

					@else

						<button type="button" class="btn btn-default 
							@if ($article->wasLiked) 
								btn-success 
							@endif
						" data-toggle="tooltip" title="I like this Post" ng-click="likeArticle({{ $article->id }}, $event)">
							<span class="glyphicon glyphicon-thumbs-up"></span> <span class="amount">{{ $article->counter_likes }}</span>
						</button>
						&mdash;
						<button type="button" class="btn btn-default" data-toggle="tooltip" title="Not interesting (hide)" ng-click="ignoreArticle({{ $article->id }})">
							<span class="glyphicon glyphicon-eye-close"></span>
						</button>

					@endif

				</div>

			</div>

		</div>

		<div class="panel-body">

			<section class="content">
				{!! formatMyText($article->content) !!}
			</section>

			@if ($photos->count())

				<hr />

				<section class="photos">

					@foreach ($photos as $photo)

						<a href="{{ url('uploads' . $photo->filepath) }}" class="btn btn-default" rel="article-gallery-{{ $article->id }}">
							{{ Html::image(url('uploads' . $photo->filethumb)) }}
						</a>

					@endforeach

				</section>

			@endif

			<hr />

			<section class="comments" ng-controller="CommentsCtrl">

				<h4>Comments <span class="badge">{{ $article->counter_comments }}</span> 
					@if ($comments->count())
						<a role="button" class="btn btn-default btn-sm" ng-click="writeComment($event)"><span class="glyphicon glyphicon-arrow-down"></span> Leave a New Comment</a>
					@endif
				</h4>

				<div class="tree">

					@if ($comments->count())

						<div class="htree">

							@foreach ($comments as $key => $comment)

								@if ($comment->level > 0) <div class="htree-li"> @endif

								<div class="htree-row"><div class="htree-row-inner">

									@include('partials.comment', ['author' => $comment->author])

								</div></div>

								@if (isset($comments[$key + 1]))

									@if ($comments[$key + 1]->level > $comment->level) <div class="htree-ul"> @endif

									@if ($comments[$key + 1]->level == $comment->level && $comment->level > 0) </div> @endif

									@if ($comments[$key + 1]->level < $comment->level)

										<?php $amount = $comment->level - $comments[$key + 1]->level; ?>

										@for ($i = 0; $i < $amount; $i ++) </div></div> @endfor

										@if ($comments[$key + 1]->level > 0) </div> @endif

									@endif

								@else

									@for ($i = 0; $i < $comment->level; $i ++) </div></div> @endfor

								@endif

							@endforeach

						</div>

					@endif

				</div>

				<hr />

				<button type="button" class="btn btn-default btn-lg" ng-click="writeComment($event)">
					<span class="glyphicon glyphicon-pencil"></span> Leave a New Comment
				</button>

				@include('partials.comment.create')

			</section>

		</div>

	</div>

</article>