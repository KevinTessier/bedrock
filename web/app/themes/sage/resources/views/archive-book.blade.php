@extends('layouts.app')

@section('content')
  <div class="content-grid">
    @include('partials.page-header')

    @if (!have_posts())
      <x-alert type="warning">
        {!! __('Sorry, no results were found.', 'sage') !!}
      </x-alert>
    @endif

    @if (have_posts())
      {{-- Denser, cover-oriented grid to distinguish books from the article listing. --}}
      <div class="grid grid-cols-2 gap-6 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
        @while (have_posts())
          @php(the_post())
          @include('partials.card-book')
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
