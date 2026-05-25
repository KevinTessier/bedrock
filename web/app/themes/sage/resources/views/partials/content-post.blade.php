<article class="card group relative flex flex-col overflow-hidden rounded-lg border border-black/10 bg-white transition-shadow hover:shadow-lg">
  @if ($thumbnail)
    <div class="aspect-[16/9] overflow-hidden bg-beige">
      {!! $thumbnail !!}
    </div>
  @endif

  <div class="flex flex-1 flex-col gap-2 p-4">
    @if ($category)
      <span class="text-xs font-semibold uppercase tracking-wide text-gray-500">
        {{ $category->name }}
      </span>
    @endif

    <h2 class="text-lg font-semibold leading-snug text-gray-900 my-0">
      {{-- Lien étiré : couvre toute la carte, reste l'unique lien (RGAA) --}}
      <a
        href="{{ $permalink }}"
        class="after:absolute after:inset-0 after:content-[''] hover:underline focus-visible:underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900"
      >
        {!! $title !!}
      </a>
    </h2>

    <p class="line-clamp-3 text-sm text-gray-600 my-0">
      {{ $excerpt }}
    </p>

    <p class="mt-auto pt-2 text-xs text-gray-500 my-0">
      <time datetime="{{ $dateIso }}">{{ $date }}</time>
    </p>
  </div>
</article>
