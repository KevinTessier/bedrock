<article @php(post_class('h-entry'))>
  {{-- content-grid : contenu à 48rem ; blocs .alignwide -> 64rem ; .alignfull -> pleine largeur --}}
  <div class="e-content content-grid">
    <header class="py-4">
      <h1 class="text-4xl font-bold">
        {!! $title !!}
      </h1>

      @include('partials.entry-meta')
    </header>
  
    @php(the_content())

  </div>

</article>
