<article @php(post_class('h-entry'))>
  <header class="py-4">
    <h1 class="text-4xl font-bold">
      {!! $title !!}
    </h1>

    @include('partials.entry-meta')
  </header>

  <div class="e-content">
    @php(the_content())
  </div>

</article>
