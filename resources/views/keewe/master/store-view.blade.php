@extends('keewe.layout.master')

@section('content')
<!-- Content Section Begin -->
<section class="content-adm">
    <div class="container">
        <div class="row">
            <div class="col">
                <h4>{{ $page_title }} <small class="text-muted">Administração</small></h4>
                <br>
                <div class="row">
                    <div class="col-md-3">

                        <!-- Seller menu -->
                        @include('keewe.master._nav')

                    </div>
                    <div class="col-md-9">
                        <h5 class="title2">Detalhes da Loja</h5>
                        <div class="row">
                            <div class="col">
                                &nbsp;
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <dl class="row">
                                    <dt class="col-sm-3">Nome da loja</dt>
                                    <dd class="col-sm-9">{{ $store->name }}</dd>

                                    <dt class="col-sm-3">Razão Social</dt>
                                    <dd class="col-sm-9">{{ $store->business_name }}</dd>

                                    <dt class="col-sm-3">CNPJ</dt>
                                    <dd class="col-sm-9">{{ $store->cnpj }}</dd>

                                    <dt class="col-sm-3">Data de criação</dt>
                                    <dd class="col-sm-9">{{ dtf( $store->created_at, 'd.m.Y' ) }}</dd>

                                    <dt class="col-sm-3">Email de contato</dt>
                                    <dd class="col-sm-9">{{ $store->email }}</dd>

                                    <dt class="col-sm-3">Telefone de contato</dt>
                                    <dd class="col-sm-9">{{ $store->phone }}</dd>

                                    <dt class="col-sm-3">Lojista</dt>
                                    <dd class="col-sm-9">{{ $store->owner->fullname }}</dd>

                                    <dt class="col-sm-3">Email do lojista</dt>
                                    <dd class="col-sm-9">{{ $store->owner->username }}</dd>

                                    <dt class="col-sm-3">Produtos</dt>
                                    <dd class="col-sm-9">{{ $store->products_count }}</dd>
                                </dl>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@csrf
<!-- Content Section End -->
@endsection

@section('scripts')
    @parent
    <script>
        // Global var
            var Fields = {
                _token: null,
                _method: null
            };

            $(document).ready(function(){
                //
                Fields._token = $('input[name=_token]').val();
            });

            /**
             * Redirect to Store details page.
             */
            function viewStore( item_id )
            {
                document.location.href = BASE_URL + '/stores/' + item_id;
            }

            /**
             * Block a Store
             */
            function blockStore( item_id )
            {
                Fields.id = item_id;

                var url = BASE_URL + '/stores/block';

                Common.ajax( url, 'POST', Fields, null, actionHandler );
            }

            /**
             * Deletes a Store
             */
            function delStore( item_id )
            {
                Fields._method = 'DELETE';
                Fields.id      = item_id;

                var url = BASE_URL + '/stores/' + item_id;

                Common.ajax( url, 'POST', Fields, null, actionHandler );
            }

            function actionHandler( json )
            {
                Common.responseHandler( json );

                document.location.reload();
            }
    </script>
@endsection
