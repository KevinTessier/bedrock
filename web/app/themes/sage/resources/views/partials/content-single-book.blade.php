<article @php(post_class('h-entry'))>
  {{-- Book-specific layout: cover + meta side by side, synopsis below. --}}
  <div class="content-grid">
    <header class="grid gap-8 py-8 md:grid-cols-[minmax(0,16rem)_1fr] md:items-start">
      @if (has_post_thumbnail())
        <div class="overflow-hidden rounded-lg shadow-md">
          {!! get_the_post_thumbnail(null, 'card-thumbnail', [
            'class' => 'h-auto w-full object-cover',
            'alt' => '',
          ]) !!}
        </div>
      @endif

      <div class="flex flex-col gap-3">
        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 my-0">
          {{ __('Book', 'sage') }}
        </p>

        <h1 class="text-4xl font-bold my-0">{!! $title !!}</h1>

        @if (has_excerpt())
          <p class="text-lg text-gray-600 my-0">{{ get_the_excerpt() }}</p>
        @endif

        @include('partials.entry-meta')
      </div>
    </header>
  </div>

  <div class="e-content content-grid">
    @php(the_content())
  </div>
</article>
