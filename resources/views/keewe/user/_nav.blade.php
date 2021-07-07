<div class="accordion" id="seller-menu">
    <!-- Personal Data -->
    <div class="card">
        <div class="card-header" id="sm-sales">
            <a href="#" class="{{ active_if(['minha-conta','mudar-senha','cobranca','entrega'], 'collapsed') }}" data-toggle="collapse" data-target="#col-sales" aria-expanded="false" aria-controls="col-sales">
                Meus Dados
            </a>
        </div>
        <div id="col-sales" class="collapse {{ active_if(['minha-conta','mudar-senha','cobranca','entrega'], '') }}" aria-labelledby="sm-sales" data-parent="#seller-menu">
            <div class="card-body">
                <a class="" href="{{ url('/minha-conta') }}">Dados Pessoais</a>
                <a class="" href="{{ url('/mudar-senha') }}">Alterar Senha</a>
                <a class="" href="{{ url('/cobranca') }}">Endereço de Cobrança</a>
                <a class="" href="{{ url('/entrega') }}">Endereços de Entrega</a>
            </div>
        </div>
    </div>

    <!-- Orders -->
    <div class="card">
        <div class="card-header" id="sm-products">
            <a href="#" class="{{ active_if(['wishlist','meus-pedidos'], 'collapsed') }}" data-toggle="collapse" data-target="#col-products" aria-expanded="false" aria-controls="col-products">
                Pedidos
            </a>
        </div>
        <div id="col-products" class="collapse {{ active_if(['wishlist','meus-pedidos'], '') }}" aria-labelledby="sm-products" data-parent="#seller-menu">
            <div class="card-body">
                <a class="" href="{{ url('/wishlist') }}">Minha lista de desejos</a>
                <a class="" href="{{ url('/meus-pedidos') }}">Meus pedidos</a>
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