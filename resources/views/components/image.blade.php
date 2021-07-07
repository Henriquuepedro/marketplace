@if( $source )
    <div class="card can-delete">
        <div class="card-image">
            <img src="{{ asset($source) }}" class="responsive-img" alt="" title="">
        </div>
        @if( $delete )
            <button type="button" class="btn btn-danger" title="Excluir imagem" onclick="Uploader.remove({{ $value }});">
                <i class="fa fa-close"></i>
            </button>
        @endif
    </div>
@endif