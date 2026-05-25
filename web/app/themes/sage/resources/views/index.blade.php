@extends('layouts.app')

@section('content')
  @include('partials.page-header')

  @if (!have_posts())
    <x-alert type="warning">
      {!! __('Sorry, no results were found.', 'sage') !!}
    </x-alert>

    {!! get_search_form(false) !!}
  @endif

  @if (have_posts())
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
      @while (have_posts())
        @php(the_post())
          @includeFirst(['partials.content-' . get_post_type(), 'partials.content'])
      @endwhile
    </div>
  @endif

  {!! get_the_posts_navigation() !!}
@endsection
