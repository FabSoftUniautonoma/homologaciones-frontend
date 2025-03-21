<div class="card shadow-sm">
	@isset($title)
		<div class="card-header bg-dark2 rounded-top">
            <h1 class="text-white">{{ $title }}</h1>
            @isset($show)
                @if (!$show)
                    <span class="text-danger">* Indica que la pregunta es obligatoria</span>
                @endif
            @endisset
  		</div>
  	@endisset

	<div class="card-body">
		{{ $slot }}
	</div>
</div>
