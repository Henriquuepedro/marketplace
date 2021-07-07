@if( $source )
    <x-image :source="$source->url" :value="$source->id" :delete="$delete" />
@else
    <input type="hidden" name="{{ $name }}" value="{{ $source->id ?? '' }}">
    <div class="dropzone {{ $source ? 'hide' : '' }}"></div>
@endif