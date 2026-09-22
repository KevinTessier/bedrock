@extends('layouts.app')

@section('content')
  <section class="content-grid py-16 sm:py-24">
    <div class="mx-auto flex max-w-xl flex-col items-center text-center">
      {{-- Numéro décoratif : masqué aux lecteurs d'écran (le H1 porte le sens). --}}
      <p class="text-7xl font-bold leading-none tracking-tight text-gray-900 sm:text-8xl my-0" aria-hidden="true">
        404
      </p>

      <h1 class="mt-6 text-3xl font-bold text-gray-900 sm:text-4xl">
        {{ __('This page got lost', 'sage') }}
      </h1>

      <p class="mt-4 text-lg text-gray-600">
        {{ __('The page you are looking for has been moved, removed, or never existed. Let’s get you back on track.', 'sage') }}
      </p>

      <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
        <a
          href="{{ $homeUrl }}"
          class="inline-flex items-center justify-center rounded-md bg-gray-900 px-5 py-2.5 text-sm font-semibold text-white no-underline transition-colors hover:bg-black focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900"
        >
          {{ __('Back to home', 'sage') }}
        </a>

        <a
          href="{{ $articlesUrl }}"
          class="inline-flex items-center justify-center rounded-md border border-gray-900 px-5 py-2.5 text-sm font-semibold text-gray-900 no-underline transition-colors hover:bg-gray-900 hover:text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900"
        >
          {{ __('Browse articles', 'sage') }}
        </a>

        <a
          href="{{ $booksUrl }}"
          class="inline-flex items-center justify-center rounded-md border border-gray-900 px-5 py-2.5 text-sm font-semibold text-gray-900 no-underline transition-colors hover:bg-gray-900 hover:text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900"
        >
          {{ __('Browse books', 'sage') }}
        </a>
      </div>

      <div class="mt-12 w-full rounded-lg bg-beige p-6">
        <h2 class="mb-3 text-sm font-semibold uppercase tracking-wide text-gray-500">
          {{ __('Or search the site', 'sage') }}
        </h2>

        {!! get_search_form(false) !!}
      </div>
    </div>
  </section>
@endsection
