<div class="mt-4">
    @if(empty($filename))
        <img src="{{ asset('images/no_image.jpg') }}">
    @else
        <img src="{{ asset('storage/images/'.$filename) }}">

    @endif

</div>
