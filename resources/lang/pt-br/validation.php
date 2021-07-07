<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'O :attribute precisa ser aceito.',
    'active_url' => 'O campo :attribute deve conter uma URL válida.',
    'after' => 'O campo :attribute precisa conter uma data posterior à :date.',
    'after_or_equal' => 'O campo :attribute precisa conter uma data igual ou posterior à :date.',
    'alpha' => 'O campo :attribute deve conter apenas letras.',
    'alpha_dash' => 'O campo :attribute deve conter letras, números, traços e sublinhados.',
    'alpha_num' => 'O campo :attribute deve conter apenas letras e números.',
    'array' => 'O campo :attribute deve conter uma matriz.',
    'before' => 'O campo :attribute deve ser uma data anterior à :date.',
    'before_or_equal' => 'O campo :attribute precisa ser uma data igual ou anterior à :date.',
    'between' => [
        'numeric' => 'O campo :attribute deve ser um número entre :min e :max.',
        'file' => 'O arquivo :attribute deve possuir entre :min e :max kilobytes.',
        'string' => 'O campo :attribute deve possuir entre :min e :max caracteres.',
        'array' => 'O campo :attribute deve possuir entre :min e :max itens.',
    ],
    'boolean' => 'O campo :attribute deve ser verdadeiro ou falso.',
    'confirmed' => 'A confirmação do campo :attribute não corresponde.',
    'date' => 'O campo :attribute deve possuir uma data válida.',
    'date_equals' => 'O campo :attribute deve conter uma data igual à :date.',
    'date_format' => 'O campo :attribute deve possuir o formato :format.',
    'different' => 'Os campos :attribute e :other devem ser diferentes.',
    'digits' => 'O campo :attribute deve possuir :digits dígitos.',
    'digits_between' => 'O campo :attribute deve possuir entre :min e :max dígitos.',
    'dimensions' => 'A imagem :attribute está com as dimensões inválidas.',
    'distinct' => 'O campo :attribute possui valores duplicados.',
    'email' => 'O campo :attribute deve possuir um endereço de email válido.',
    'ends_with' => 'O campo :attribute deve terminar com um dos valores: :values.',
    'exists' => 'O valor selecionado no campo :attribute não é válido.',
    'file' => 'O campo :attribute deve ser um arquivo.',
    'filled' => 'O campo :attribute precisa conter um valor.',
    'gt' => [
        'numeric' => 'O campo :attribute deve conter um número maior que :value.',
        'file' => 'O arquivo :attribute deve ser maior que :value kilobytes.',
        'string' => 'O campo :attribute deve possuir mais que :value caracteres.',
        'array' => 'O campo :attribute precisa possuir mais de :value itens.',
    ],
    'gte' => [
        'numeric' => 'O campo :attribute deve conter um número igual ou maior que :value.',
        'file' => 'O arquivo :attribute deve ser igual ou maior que :value kilobytes.',
        'string' => 'O campo :attribute deve ser igual ou maior que :value caracteres.',
        'array' => 'O campo :attribute deve possuir :value itens ou mais.',
    ],
    'image' => 'O campo :attribute deve ser uma imagem.',
    'in' => 'O valor selecionado no campo :attribute não é válido.',
    'in_array' => 'O valor do campo :attribute não existem em :other.',
    'integer' => 'O campo :attribute deve ser um número inteiro.',
    'ip' => 'O campo :attribute deve possuir um endereço IP válido.',
    'ipv4' => 'O campo :attribute deve possuir um endereço IPv4 válido.',
    'ipv6' => 'O campo :attribute deve possuir um endereço IPv6 válido.',
    'json' => 'O campo :attribute deve ser preenchido com uma string JSON válida.',
    'lt' => [
        'numeric' => 'O campo :attribute deve possuir um número menor que :value.',
        'file' => 'O arquivo :attribute deve ser menor que :value kilobytes.',
        'string' => 'O campo :attribute deve possuir menos de :value caracteres.',
        'array' => 'O campo :attribute deve possuir meno de :value itens.',
    ],
    'lte' => [
        'numeric' => 'O campo :attribute deve possuir um número igual ou menor que :value.',
        'file' => 'O arquivo :attribute deve ser igual ou menor que :value kilobytes.',
        'string' => 'O campo :attribute deve ser igual ou menor que :value caracteres.',
        'array' => 'O campo :attribute não deve possuir mais de :value itens.',
    ],
    'max' => [
        'numeric' => 'O campo :attribute não pode ser maior que :max.',
        'file' => 'O arquivo :attribute não deve ser maior que :max kilobytes.',
        'string' => 'O campo :attribute não deve possuir mais de :max caaracteres.',
        'array' => 'O campo :attribute não deve possuir mais de :max itens.',
    ],
    'mimes' => 'O campo :attribute deve ser um arquivo do tipo: :values.',
    'mimetypes' => 'O campo :attribute deve ser um arquivo do tipo: :values.',
    'min' => [
        'numeric' => 'O campo :attribute deve ser, no mínimo, :min.',
        'file' => 'O arquivo :attribute deve possuir, no mínimo, :min kilobytes.',
        'string' => 'O campo :attribute deve possuir, no mínimo, :min caracteres.',
        'array' => 'O campo :attribute deve possuir, no mínimo, :min itens.',
    ],
    'not_in' => 'O valor selecionado no campo :attribute não é válido.',
    'not_regex' => 'O formato do campo :attribute não é válido.',
    'numeric' => 'O campo :attribute deve conter um número.',
    'password' => 'A senha não está correta.',
    'present' => 'O campo :attribute deve ser preenchido.',
    'regex' => 'O formato do campo :attribute não é válido.',
    'required' => 'O campo :attribute é obrigatório.',
    'required_if' => 'O campo :attribute é obrigatório quando o campo :other possuir o valor :value.',
    'required_unless' => 'O campo :attribute é obrigatórioa não ser que o campo :other possua algum dos valores: :values.',
    'required_with' => 'O campo :attribute é obrigatório quando o valor :values está presente.',
    'required_with_all' => 'O campo :attribute é obrigatório quando os valores :values estão presentes.',
    'required_without' => 'O campo :attribute é obrigatório quando o valor :values não está presente.',
    'required_without_all' => 'O campo :attribute é obrigatório quando nenhum dos valores :values estiverem presentes.',
    'same' => 'Os campos :attribute e :other devem ser iguais.',
    'size' => [
        'numeric' => 'O campo :attribute deve ser igual à :size.',
        'file' => 'O arquivo :attribute deve possuir :size kilobytes.',
        'string' => 'O campo :attribute deve possuir :size caracteres.',
        'array' => 'O campo :attribute deve conter :size itens.',
    ],
    'starts_with' => 'O valor do campo :attribute deve iniciar com um desses prefixos: :values.',
    'string' => 'O campo :attribute deve ser um texto.',
    'timezone' => 'O campo :attribute deve possuir uma zona válida.',
    'unique' => 'O :attribute já está em uso.',
    'uploaded' => 'Ocorreu um erro no envio do arquivo :attribute',
    'url' => 'O campo :attribute deve ser uma URL.',
    'uuid' => 'O campo :attribute deve possuir um UUID válido.',

    // Custom rules
    'cnpj' => 'O CNPJ digitado não é válido.',
    'cpf' => 'O CPF digitado não é válido.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
