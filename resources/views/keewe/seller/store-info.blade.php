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
                        @include('keewe.seller._nav')

                    </div>
                    <div class="col-md-9">
                        <h5 class="title2">Dados bancários</h5>
                        <p>
                            Para receber pelas suas vendas em nossa plataforma,
                            preencha abaixo os dados bancários de sua loja.
                        </p>
                        <form method="POST" action="{{ url('/minha-loja/bank') }}" accept-charset="utf-8" onsubmit="return false;">
                            @csrf
                            <input type="hidden" name="id" value="{{ $info->id ?? '' }}">
                            <input type="hidden" name="store_id" value="{{ $store->id ?? '' }}">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="bank_id">Seu banco</label>
                                    <select id="bank_id" name="bank_id" palceholder="Banco" class="form-control select2">
                                        <option>Selecione</option>
                                        {!! options( 'App\Models\Common\Bank', 'name', 'id', 'name', ($info->bank_id ?? null) ) !!}
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="bank_account_type">Tipo de conta</label>
                                    <select id="bank_account_type" name="bank_account_type" palceholder="Tipo de conta" class="form-control select2">
                                        <option>Selecione</option>
                                        {!! options_data( $acc_types, ($info->bank_account_type ?? null) ) !!}
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="bank_branch">N&ordm; da Agência</label>
                                    <input id="bank_branch" name="bank_branch" type="text" class="form-control msk-num" value="{{ $info->bank_branch ?? '' }}">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="bank_branch_dv">Dígito</label>
                                    <input id="bank_branch_dv" name="bank_branch_dv" type="text" class="form-control msk-num" value="{{ $info->bank_branch_dv ?? '' }}">
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="bank_account">N&ordm; da Conta</label>
                                    <input id="bank_account" name="bank_account" type="text" class="form-control msk-num" value="{{ $info->bank_account ?? '' }}">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="bank_account_dv">Dígito</label>
                                    <input id="bank_account_dv" name="bank_account_dv" type="text" class="form-control msk-num" value="{{ $info->bank_account_dv ?? '' }}">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="account_holder_name">Nome do Titular da conta</label>
                                    <input id="account_holder_name" name="account_holder_name" type="text" class="form-control" value="{{ $info->account_holder_name ?? '' }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="account_holder_doc">CPF do Titular da conta</label>
                                    <input id="account_holder_doc" name="account_holder_doc" type="text" class="form-control msk-cpf" value="{{ $info->account_holder_doc ?? '' }}">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4 offset-md-4">
                                    <button type="submit" class="btn btn-secondary btn-block" onclick="Common.save(this);">gravar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Content Section End -->
@endsection
