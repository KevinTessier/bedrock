{{--
  Cards « More books » sur le single Book.
  $related (array de WP_Post) est fourni par le composer App\View\Composers\Book.
--}}
<section class="mt-12" aria-labelledby="related-heading">
  <div class="content-grid">
    <h2 id="related-heading" class="mb-6 text-2xl font-semibold text-gray-900">
      {{ __('More books', 'sage') }}
    </h2>

    <div class="grid grid-cols-2 gap-6 sm:grid-cols-3 lg:grid-cols-4">
      @foreach ($related as $post)
        @include('partials.card-book', ['post' => $post])
      @endforeach
    </div>
  </div>
</section>
