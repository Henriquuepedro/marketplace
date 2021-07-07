<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Auth\User;
use App\Models\Store\Store;
use App\Models\Store\ProductImage;
use App\Models\Common\Image;

class MediaController extends Controller
{
    /**
     * Handles a upload request
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function upload( Request $request )
    {
        // Check if file exists
        if( ! $request->hasFile( 'upload' ) )
            return $this->responseError('Erro: Nenhum arquivo foi recebido por upload.');

        // Alias to files
        $file = $request->file('upload');

        // Check for array
        if( is_array($file) )
            $file = $file[0];

        // Check if file is valid
        if( ! $file->isValid() )
            return $this->responseError('Erro: O arquivo enviado não é válido.');

        // Get file properties
        $original_name = $file->getClientOriginalName();
        $mime_type     = $file->getMimeType();
        $extension     = $file->getClientOriginalExtension();
        $filesize      = $file->getSize();

        // Get user
        $user = auth()->user() ?? User::find(1);

        // Builds file path
        $path = 'uploads/'. $user->id .'/'. date('Y') .'/'. date('m');

		if( ! is_dir( public_path($path) ) )
		{
			@mkdir( public_path($path), 0777, true );
		}

        // Saves file to disk
        $filepath = Storage::putFile( $path, $file );
        $file_url = Storage::url( $filepath );

        // Creates the database record
        $media = new Image();

        $media->store_id           = $request->store_id;
        $media->user_id            = $user->id;
        $media->original_name      = $original_name;
        $media->original_extension = $extension;
        $media->mime_type          = $mime_type;
        $media->size               = $filesize;
        $media->name               = $original_name;
        $media->path               = $path;
        $media->url                = $file_url;

        $media->save();

        // Return the Media
        return response()->json( $media );
    }

    /**
     * Deletes a media file
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function delete( Request $request, $id )
    {
        // Load media data
        $image = Image::find( $id );

        if( ! $image )
            return response()->json(['success' => false, 'message' => 'A imagem não foi encontrada.']);

        // Get file url
        $url = $image->url;

        if( file_exists( public_path( $url ) ) )
        {
            unlink( public_path($url) );
        }

        // Check if this image is a Store image
        $store = Store::where('background_id', '=', $id)
                ->orWhere('cover_id', '=', $id)
                ->orWhere('logo_id', '=', $id)
                ->first();

        if( $store )
        {
            // Unlink image from store
            $store->background_id = ($store->background_id == $id) ? null : $store->background_id;
            $store->cover_id      = ($store->cover_id == $id) ? null : $store->cover_id;
            $store->logo_id       = ($store->logo_id == $id) ? null : $store->logo_id;
            $store->save();
        }

        // Check if this image is a Product image
        ProductImage::where('image_id', '=', $id)->delete();

        // Finally, deletes the record
        $image->delete();

        return $this->responseSuccess( 'A imagem foi excluída.', [ 'next_page' => $request->input('referer') ] );
    }

    // OTHER PUBLIC METHODS =======================================================================

    // PROTECTED METHODS ==========================================================================
}
