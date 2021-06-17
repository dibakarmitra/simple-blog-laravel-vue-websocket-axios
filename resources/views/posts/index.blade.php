@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-8">
      <h1>All Posts</h1>
    </div>

    <div class="col-md-4">
      <a href="{{ route('posts.create') }}" class="btn btn-primary pull-right" style="margin-top:15px;">Create New Post</a>
    </div>
  </div>
  <hr />
  @php
  function getIfSet(&$value, $default = 1)
  {
  return isset($value) ? $value : $default;
  }
  $page = getIfSet($_REQUEST['page']);
  @endphp
  <posts :page="{{ $page }}"></posts>

  <div class="content text-center">
    {{ $posts->links("pagination::bootstrap-4") }}
  </div>

</div>
@endsection