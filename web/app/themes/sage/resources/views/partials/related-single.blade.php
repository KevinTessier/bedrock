{{--
  Cards « À lire aussi » sur le single.
  $related (array de WP_Post) est fourni par le composer App\View\Composers\Single.
--}}
<section class="mt-12" aria-labelledby="related-heading">
  <h2 id="related-heading" class="mb-6 text-2xl font-semibold text-gray-900">
    {{ __('Continue reading', 'sage') }}
  </h2>

  <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
    @foreach ($related as $post)
      @include('partials.content-post', ['post' => $post])
    @endforeach
  </div>
</section>
