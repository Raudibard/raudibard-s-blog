@extends('layouts.app')

@section('content')

<br />

<div class="row">

	<div class="col-md-1"><!--//--></div>

	<div class="col-md-10 col-xs-12">

		<div ng-controller="ArticlesCtrl">

			@if (Auth::guest())
				<div class="alert alert-warning alert-dismissable" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<span class="glyphicon glyphicon-info-sign"></span><a href="{{ url('/login') }}" class="alert-link">Login</a> or <a href="{{ url('/register') }}" class="alert-link">Register</a> to start writing and commenting! 
				</div>
			@else

				<div class="profile">
					<p class="text-info"><span class="glyphicon glyphicon-user"></span> <strong>{{ Auth::user()->name }}</strong></p>
					<a href="{{ url('/logout') }}" class="btn btn-default" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"> Logout</a>
					<form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
						{{ csrf_field() }}
					</form>
				</div>

				<div class="create-article">

					<button type="button" class="btn btn-primary btn-lg" data-toggle="collapse" data-target="#createArticleFormWrapper" aria-expanded="false" aria-controls="createArticleFormWrapper">
						<span class="glyphicon glyphicon-pencil"></span> Write a New Post
					</button>

					<div id="createArticleFormWrapper" class="form-wrapper collapse">

						@include('errors.list')

						<form name="createArticleForm" ng-submit="createArticle(createArticleForm)" novalidate>

							<div class="form-group">
								{!! Form::textarea('content', null, [
									'rows' 			=> 6,
									'class' 		=> 'form-control',
									'ng-model' 		=> 'createArticleFormData.content',
									'ng-minlength' 	=> 10,
									'placeholder'   => 'Text...',
									'required'
								]) !!}
								<div class="error" ng-messages="createArticleForm.content.$error" ng-if="createArticleForm.$submitted" ng-cloak>
									<div ng-message="required">Please enter post text!</div>
									<div ng-message="minlength">This text is too short, isn't it? It must be at least 10 characters!</div>
								</div>
							</div>

							<div class="form-group">
								<span class="btn btn-primary btn-file">
								    Browse Files <input type="file" nv-file-select uploader="uploaderCreate" multiple />
								</span>
								<span class="btn-file-info">
									<span class="glyphicon glyphicon-arrow-left"></span> You can attach 5 pictures: <strong>jpeg, png, jpg, gif</strong> with maximum size of <strong>2 Mb</strong> for each file.
								</span>
							</div>

							<table class="table table-condensed">
								<tr ng-repeat="item in uploaderCreate.queue" ng-cloak>
									<td ng-thumb="{ file: item._file, width: 50, height: 50 }"></td>
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

							<div class="form-group">
								{!! Form::submit('Submit', [
									'class' => 'btn btn-success btn-lg'
								]) !!}
							</div>

						{!! Form::close() !!}

					</div>

					<article class="article create-article-preview" ng-if="createArticleForm.$dirty && createArticleForm.$valid" ng-cloak>
						
						<div class="panel panel-success">

							<div class="panel-heading">

								<div class="row">
									<div class="col-md-8 col-xs-12">
										<p class="text-info"><span class="glyphicon glyphicon-user"></span> <strong>{{ Auth::user()->name }}</strong></p>
										<p class="posted small text-muted">Posted: <span class="date">{{ Carbon\Carbon::now()->format('j F Y \a\t H:i') }}</span></p>
									</div>
									<div class="col-md-4 col-xs-12 text-right">
										<div class="label label-primary">Preview</div>
									</div>
								</div>
							</div>

							<div class="panel-body">
								
								<section class="content" ng-bind-html="createArticleFormData.content | breakFilter"></section>

								<hr />

								<section class="photos" ng-repeat="item in uploaderCreate.queue">
									<span ng-thumb="{ file: item._file, width: 162 }"></span>
								</section>

							</div>

						</div>

					</article>

				</div>

			@endif

			<hr />

			<div class="sorter form-inline">

				<div class="form-group">

					<label for="sorter" class="control-label">Sort:</label>

					<select id="sorter" class="form-control" ng-model="sortParam">
						<option value="created|desc">from latest to oldest</option>
						<option value="created|asc">from oldest to latest</option>
						<option value="likes|desc">by most liked</option>
						<option value="likes|asc">by less liked</option>
					</select>

				</div>

			</div>

			<div class="articles">
				<div id="articlesPreloader" class="text-center">{{ Html::image('images/loader-wide.gif', 'Loading...') }}</div>
				<div id="articlesEmpty" class="alert alert-warning" role="alert">
					<span class="glyphicon glyphicon-info-sign"></span> This blog is empty...
				</div>
			</div>

		</div>

	</div>

	<div class="col-md-1"><!--//--></div>

</div>

@endsection
