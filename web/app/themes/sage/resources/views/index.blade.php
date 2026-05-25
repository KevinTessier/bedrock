@extends('layouts.app')

@section('content')
  <div class="content-grid">
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
          @includeFirst(['partials.card-' . get_post_type(), 'partials.card'])
      @endwhile
    </div>
  @endif

  @if ($pagination)
      <nav class="pagination py-4" aria-label="{{ __('Pagination', 'sage') }}">
        {!! $pagination !!}
      </nav>
    @endif
  </div>
@endsection
