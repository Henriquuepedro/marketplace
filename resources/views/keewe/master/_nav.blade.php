<div class="accordion" id="master-menu">
    <!-- Dashboard -->
    <div class="card">
        <div class="card-header" id="sm-dashboard">
            <a href="{{ url('/dashboard') }}" class="">Dashboard</a>
        </div>
    </div>

    <!-- Sales -->
    <div class="card">
        <div class="card-header" id="sm-stores">
            <a href="#" class="{{ active_if('stores', 'collapsed') }}" data-toggle="collapse" data-target="#col-stores" aria-expanded="false" aria-controls="col-stores">
                Lojas
            </a>
        </div>
        <div id="col-stores" class="collapse {{ active_if('stores', '') }}" aria-labelledby="sm-stores" data-parent="#master-menu">
            <div class="card-body">
                <a class="" href="{{ url('/stores') }}">Lista de Lojas</a>
                <a class="" href="{{ url('/stores?status=inactive') }}">Lojas bloqueadas</a>
            </div>
        </div>
    </div>

    <!-- Products -->
    <!--
    <div class="card">
        <div class="card-header" id="sm-products">
            <a href="#" class="{{ active_if('products', 'collapsed') }}" data-toggle="collapse" data-target="#col-products" aria-expanded="false" aria-controls="col-products">
                Produtos
            </a>
        </div>
        <div id="col-products" class="collapse {{ active_if('products', '') }}" aria-labelledby="sm-products" data-parent="#master-menu">
            <div class="card-body">
                <a class="" href="{{ url('/products') }}">Todos os produtos</a>
            </div>
        </div>
    </div>
    -->

    <!-- Pages -->
    <div class="card">
        <div class="card-header" id="sm-pages">
            <a href="#" class="{{ active_if('pages', 'collapsed') }}" data-toggle="collapse" data-target="#col-pages" aria-expanded="false" aria-controls="col-pages">
                Páginas
            </a>
        </div>
        <div id="col-pages" class="collapse {{ active_if('pages', '') }}" aria-labelledby="sm-pages" data-parent="#master-menu">
            <div class="card-body">
                <a class="" href="{{ url('/pages') }}">Lista de páginas</a>
                <a class="" href="{{ url('/pages/create') }}">Nova página</a>
            </div>
        </div>
    </div>

    <!-- Menus -->
    <div class="card">
        <div class="card-header" id="sm-menus">
            <a href="#" class="{{ active_if('menus', 'collapsed') }}" data-toggle="collapse" data-target="#col-menus" aria-expanded="false" aria-controls="col-menus">
                Navegação
            </a>
        </div>
        <div id="col-menus" class="collapse {{ active_if('menus', '') }}" aria-labelledby="sm-pages" data-parent="#master-menu">
            <div class="card-body">
                <a class="" href="{{ url('/menus') }}">Lista de Menus</a>
                <a class="" href="{{ url('/menus/create') }}">Novo Menu</a>
                <a class="" href="{{ url('/menus-items') }}">Itens de Menus</a>
            </div>
        </div>
    </div>

</div>