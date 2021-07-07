<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\BaseResourceController;
use Illuminate\Http\Request;

use App\Models\Common\Page;

class PagesController extends BaseResourceController
{
    public function __construct()
    {
        $this->model_name   = Page::class;
        $this->route        = '/pages';

        // List
        $this->list_columns = [];
        $this->searchable   = [];
    }

    public function showPage( Request $request, $slug )
    {
        $page = Page::where('slug', '=', $slug)->first();

        if( ! $page )
        {
            return redirect('/');
        }

        // Build view data
        $data = $this->commonViewData( 'Keewe | ', $page->title );

        $data['page'] = $page;

        $data['breadcrumb'] = [
            (object)[ 'name' => $page->title, 'url' => url('/pg/' . $page->slug) ]
        ];

        return $this->page( 'store.pages', $data );
    }
}
