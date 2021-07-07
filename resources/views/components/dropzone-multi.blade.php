@foreach( $images as $image )
    <div class="form-group col-md-3">
        <x-image :source="$image->url" :value="$image->id" :delete="$delete"/>
        <input type="hidden" name="{{ $name }}" value="{{ $image->id }}">
    </div>
@endforeach
<div class="form-group col-md-3">
    <input type="hidden" name="{{ $name }}">
    <div class="dropzone"></div>
</div>
<div class="form-group col-md-3 add-dropzone">
    <button class="btn btn-primary" onclick="Uploader.addDropzone(this);">
        <i class="fa fa-plus"></i>
    </button>
</div>