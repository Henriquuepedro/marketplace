<div class="accordion" id="seller-menu">
    <!-- Dashboard -->
    <div class="card">
        <div class="card-header" id="sm-dashboard">
            <a href="{{ url('/minha-loja') }}" class="">Dashboard</a>
        </div>
    </div>

    <!-- Products -->
    <div class="card">
        <div class="card-header" id="sm-products">
            <a href="#" class="{{ active_if(['produtos','esgotados','variacoes'], 'collapsed') }}" data-toggle="collapse" data-target="#col-products" aria-expanded="false" aria-controls="col-products">
                Produtos
            </a>
        </div>
        <div id="col-products" class="collapse {{ active_if(['produtos','esgotados','variacoes'], '') }}" aria-labelledby="sm-products" data-parent="#seller-menu">
            <div class="card-body">
                <a class="" href="{{ url('/produtos') }}">Gerenciar produtos</a>
                <a class="" href="{{ url('/esgotados') }}">Esgotados</a>
                <a class="" href="{{ url('/produtos/create') }}">Novo produto</a>
                <a class="" href="{{ url('/variacoes') }}">Tabela de variações</a>
                <!-- <a class="" href="#">Esgotados (em breve)</a> -->
                <!--
                <a class="" href="#">Organizar</a>
                <a class="" href="#">Configurar</a>
                <a class="" href="#">Variações</a>
                <a class="" href="#">Medidas</a>
                -->
            </div>
        </div>
    </div>

    <!-- Sales -->
    <div class="card">
        <div class="card-header" id="sm-sales">
            <a href="#" class="{{ active_if(['pedidos','vendas'], 'collapsed') }}" data-toggle="collapse" data-target="#col-sales" aria-expanded="false" aria-controls="col-sales">
                Vendas
            </a>
        </div>
        <div id="col-sales" class="collapse {{ active_if(['pedidos','vendas'], '') }}" aria-labelledby="sm-sales" data-parent="#seller-menu">
            <div class="card-body">
                <a class="" href="{{ url('/pedidos') }}">Pedidos</a>
                <a class="" href="{{ url('/vendas') }}">Vendas</a>
                <!-- <a class="" href="#">Entregas (em breve)</a> -->
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="card">
        <div class="card-header" id="sm-actions">
            <a href="#" class="{{ active_if(['promocao','precos','estoque'], 'collapsed') }}" data-toggle="collapse" data-target="#col-actions" aria-expanded="false" aria-controls="col-actions">
                Ações em Massa
            </a>
        </div>
        <div id="col-actions" class="collapse {{ active_if(['promocao','precos','estoque'], '') }}" aria-labelledby="sm-actions" data-parent="#seller-menu">
            <div class="card-body">
                <a class="" href="{{ url('/promocao?act=add') }}">Colocar em promoção</a>
                <a class="" href="{{ url('/promocao?act=rem') }}">Retirar da promoção</a>
                <a class="" href="{{ url('/precos') }}">Reajustar preços</a>
                <a class="" href="{{ url('/estoque?act=minus') }}">Esgotar produtos</a>
                <a class="" href="{{ url('/estoque?act=plus') }}">Relistar produtos</a>
                <!--
                <a class="" href="#">Destacar produtos (em breve)</a>
                <a class="" href="#">Ajustar frete (em breve)</a>
                -->
            </div>
        </div>
    </div>

    <!-- Discounts / Coupons -->
    <div class="card">
        <div class="card-header" id="sm-coupon">
            <a href="#" class="{{ active_if(['cupons'], 'collapsed') }}" data-toggle="collapse" data-target="#col-coupon" aria-expanded="false" aria-controls="col-coupon">
                Descontos
            </a>
        </div>
        <div id="col-coupon" class="collapse {{ active_if(['cupons'], '') }}" aria-labelledby="sm-coupon" data-parent="#seller-menu">
            <div class="card-body">
                <a class="" href="{{ url('/cupons/create') }}">Criar cupom</a>
                <a class="" href="{{ url('/cupons') }}">Listar cupons</a>
                <!--
                <a class="" href="#">Desconto na loja (em breve)</a>
                <a class="" href="#">Desconto progressivo (em breve)</a>
                <a class="" href="#">Primeira compra (em breve)</a>
                -->
            </div>
        </div>
    </div>

    <!-- Store -->
    <div class="card">
        <div class="card-header" id="sm-store">
            <a href="#" class="{{ active_if(['minha-loja'], 'collapsed') }}" data-toggle="collapse" data-target="#col-store" aria-controls="col-store">
                Loja
            </a>
        </div>
        <div id="col-store" class="collapse {{ active_if(['minha-loja'], '') }}" aria-labelledby="sm-store" data-parent="#seller-menu">
            <div class="card-body">
                <a class="" href="{{ url('/minha-loja/' . $store->id .'/edit') }}">Dados da Loja</a>
                <a class="" href="{{ url('/minha-loja/bank') }}">Dados bancários</a>
                <!-- <a class="" href="{{-- url('/minha-loja/config') --}}">Configurações</a> -->
                <a class="" href="{{ url('/lojas/' . $store->slug) }}">Ver loja</a>
                <!--
                <a class="" href="#">Google Analytics</a>
                -->
            </div>
        </div>
    </div>

    <!-- Interactions -->
    <div class="card">
        <div class="card-header" id="sm-interact">
            <a href="#" class="{{ active_if(['perguntas','reputacao'], 'collapsed') }}" data-toggle="collapse" data-target="#col-interact" aria-controls="col-interact">
                Interações
            </a>
        </div>
        <div id="col-interact" class="collapse {{ active_if(['perguntas','reputacao'], '') }}" aria-labelledby="sm-interact" data-parent="#seller-menu">
            <div class="card-body">
                <a class="" href="{{ url('/perguntas') }}">Perguntas</a>
                <a class="" href="{{ url('/reputacao') }}">Reputação</a>
            </div>
        </div>
    </div>

    <!-- Help -->
    <!--
    <div class="card">
        <div class="card-header" id="sm-help">
            <a href="#" class="" data-toggle="collapse" data-target="#col-help" aria-expanded="false" aria-controls="col-help">
                Ajuda
            </a>
        </div>
        <div id="col-help" class="collapse" aria-labelledby="sm-help" data-parent="#seller-menu">
            <div class="card-body">
                <a class="" href="#">Dúvidas frequentes</a>
                <a class="" href="#">Turbine sua loja</a>
                <a class="" href="#">Fale conosco</a>
            </div>
        </div>
    </div>
    -->
</div>