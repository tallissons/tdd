<?php

namespace Tests\Feature\Rules;

use App\Rules\ValidCNPJ;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ValidCNPJTest extends TestCase
{
    /** @test */
    public function check_if_the_cnpj_is_valid_and_active()
    {
        Http::fake([
            'https://brasilapi.com.br/api/cnpj/v1/06990590000123' => Http::response(
                [
                    'cnpj' => '06.990.590/0001-23',
                    'razao_social' => 'Empresa Teste',
                    'descricao_situacao_cadastral' => 'ATIVA'
            ], 200
            )
        ]);
        // CNPJ Google: 06990590000123

        $rule = new ValidCNPJ();
        $this->assertTrue(
            $rule->passes('cnpj', '06990590000123')
        );
    }

    /** @test */
    public function return_false_if_the_cnpj_is_not_found_or_not_active()
    {
        Http::fake([
            'https://brasilapi.com.br/api/cnpj/v1/12356456789' => Http::response(
                [], 404
            ),

            'https://brasilapi.com.br/api/cnpj/v1/06990590000125' => Http::response(
                [
                    'cnpj' => '06.990.590/0001-25',
                    'razao_social' => 'Empresa Teste',
                    'descricao_situacao_cadastral' => 'INATIVA'
                ], 200
            ),
        ]);

        $rule = new ValidCNPJ();
        //CNPJ Not Found
        $this->assertFalse(
            $rule->passes('cnpj', '12356456789')
        );

        //CNPJ INATIVA
        $this->assertFalse(
            $rule->passes('cnpj', '06990590000125')
        );
    }
}
