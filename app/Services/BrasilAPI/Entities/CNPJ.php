<?php

namespace App\Services\BrasilAPI\Entities;

class CNPJ
{
    public string $cnpj;
    public string $razaoSocial;
    public string $descricaoSituacaoCadastral;

    public function __construct(array $data)
    {
        $this->cnpj = $data['cnpj'];
        $this->razaoSocial = $data['razao_social'];
        $this->descricaoSituacaoCadastral = $data['descricao_situacao_cadastral'];
    }
}
