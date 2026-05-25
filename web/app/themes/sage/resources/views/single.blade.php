@extends('layouts.app')

@section('content')
  @while (have_posts())
    @php(the_post())
    @includeFirst(['partials.content-single-' . get_post_type(), 'partials.content-single'])
  @endwhile
@endsection

@section('related')  
  @if (!empty($related))
    @includeFirst(['partials.related-single-' . get_post_type(), 'partials.related-single'])
  @endif
@endsection

