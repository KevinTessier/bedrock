<article class="card group relative flex flex-col gap-3">
  <div class="aspect-2/3 overflow-hidden rounded-lg bg-beige shadow-sm transition-shadow group-hover:shadow-lg">
    @if ($thumbnail)
      {!! $thumbnail !!}
    @endif
  </div>

  <h2 class="text-base font-semibold leading-snug text-gray-900 my-0">
    {{-- Lien étiré : couvre toute la carte, reste l'unique lien (RGAA) --}}
    <a
      href="{{ $permalink }}"
      class="after:absolute after:inset-0 after:content-[''] hover:underline focus-visible:underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900"
    >
      {!! $title !!}
    </a>
  </h2>
</article>
