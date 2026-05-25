{{--
  Front render of the dal/citation block.
  Variables passed from resources/blocks/citation/render.php:
  - $quote  (string, déjà filtré par wp_kses : strong/em/br autorisés)
  - $author (string, prénom + nom)
--}}

<figure {!! get_block_wrapper_attributes(['class' => 'sage-citation text-center']) !!}>
  <blockquote class="sage-citation__quote">
    <p>{!! $quote !!}</p>
  </blockquote>

  @if ($author)
    <figcaption class="sage-citation__attribution">{{ $author }}</figcaption>
  @endif
</figure>
