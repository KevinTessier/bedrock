@extends('layouts.app')

@section('content')
  @while (have_posts())
    @php(the_post())
    @include('partials.content-single-book')
  @endwhile
@endsection

@section('related')
  @if (!empty($related))
    @include('partials.related-single-book')
  @endif
@endsection
