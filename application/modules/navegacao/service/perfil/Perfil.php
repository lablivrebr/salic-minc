<?php

namespace Application\Modules\Navegacao\Service\Perfil;

class Perfil
{
    private $request;
    private $response;

    function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function buscarPerfisDisponoveis($grupoAtivo)
    {
        $auth = \Zend_Auth::getInstance();
        $usuarioAtivo = $auth->getIdentity();
        $arrAuth = array_change_key_case((array) $usuarioAtivo);

        $objModelUsuario = new \Navegacao_Model_PerfilMapper();
        $perfisDisponoveis = $objModelUsuario->buscarPerfisDisponiveis($arrAuth['usu_codigo'], 21);
        $reposta = $this->montaPerfis($perfisDisponoveis);

        return $this->montaResultado($reposta, (array) $usuarioAtivo, $grupoAtivo);
    }

    private function montaResultado($perfisDisponoveis, $usuarioAtivo, $grupoAtivo)
    {
        $resposta = [];
        $resposta['perfisDisponoveis'] = $this->utf8Encode($perfisDisponoveis);
        $resposta['usuarioAtivo'] = $this->utf8Encode([(array) $usuarioAtivo ]);
        $resposta['grupoAtivo'] = [ 'codGrupo' => $grupoAtivo->codGrupo, 'codOrgao' => $grupoAtivo->codOrgao ];
        $resposta['grupoSelecionadoIndex'] = $this->grupoSelecionadoIndex(
            $resposta['perfisDisponoveis'],
            $resposta['grupoAtivo']
        );

        return $resposta;
    }


    private function grupoSelecionadoIndex($perfisDisponoveis, $grupoAtivo)
    {
        foreach ($perfisDisponoveis as $key => $perfil) {
            if ($perfil['gru_codigo'] == $grupoAtivo['codGrupo'] && $perfil['uog_orgao'] == $grupoAtivo['codOrgao']) {
                return $key;
            }
        }

        return 0;
    }

    private function montaPerfis($perfis)
    {
        $result = [];
        array_push($result, $this->montarProponente());

        foreach ($perfis as $perfil) {
            $current_object = [];
            $current_object['usu_orgao'] = $perfil->getUsuOrgao();
            $current_object['usu_orgao_lotacao'] = $perfil->getUsuOrgaoLotacao();
            $current_object['uog_orgao'] = $perfil->getUogOrgao();
            $current_object['orgao_sigla_autorizada'] = $perfil->getOrgaoSiglaAutorizada();
            $current_object['org_nome_autorizado'] = $perfil->getOrgNomeAutorizado();
            $current_object['gru_codigo'] = $perfil->getGruCodigo();
            $current_object['nome_grupo'] = $perfil->getNomeGrupo();
            $current_object['org_superior'] = $perfil->getOrgSuperior();
            $current_object['uog_status'] = $perfil->getUogStatus();
            $current_object['id_unico'] = $perfil->getIdUnico();

            array_push($result, $current_object);
        }

        return $result;
    }

    private function montarProponente()
    {
        $current_object = [];

        $current_object['usu_orgao'] = '';
        $current_object['usu_orgao_lotacao'] = '';
        $current_object['uog_orgao'] = 2222;
        $current_object['orgao_sigla_autorizada'] = '';
        $current_object['org_nome_autorizado'] = '';
        $current_object['gru_codigo'] = 1111;
        $current_object['nome_grupo'] = 'Proponente';
        $current_object['org_superior'] = '';
        $current_object['uog_status'] = '';
        $current_object['id_unico'] = '';

        return $current_object;
    }

    private function utf8Encode($perfis)
    {
        array_walk($perfis, function (&$value) {
            $value = array_map('utf8_encode', $value);
        });

        return $perfis;
    }
}
