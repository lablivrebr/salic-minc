<?php

namespace Application\Modules\Solicitacao\Service\Solicitacao;

class Mensagem
{
    /**
     * @var \Zend_Controller_Request_Abstract $request
     */
    private $request;

    /**
     * @var \Zend_Controller_Response_Abstract $response
     */
    private $response;


    function __construct(\Zend_Controller_Request_Abstract $request, \Zend_Controller_Response_Abstract $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function historicoSolicitacoes()
    {
        $idPronac = $this->request->getParam('idPronac', null);
        $idPreProjeto = $this->request->getParam('idPreProjeto', null);
        $listarTudo = $this->request->getParam('listarTudo', null);

        if (strlen($idPronac) > 7) {
            $idPronac = Seguranca::dencrypt($idPronac);
        }

        $where = [];
        if ($idPronac) {
            $where['a.idPronac = ?'] = (int) $idPronac;
        }

        if ($idPreProjeto) {
            $where['a.idProjeto = ?'] = (int) $idPreProjeto;
        }

        # Proponente
        if (isset($this->usuario['cpf'])) {
            $where["(a.idAgente = {$this->idAgente} OR a.idSolicitante = {$this->idUsuario})"] = '';
        }

        # funcionarios do minc
        if (isset($this->usuario['usu_codigo'])) {

            if (empty($listarTudo)) {

                $tecnicos = (new \Autenticacao_Model_Grupos)->buscarTecnicosPorOrgao($this->grupoAtivo->codOrgao)->toArray();

                if (in_array($this->grupoAtivo->codGrupo, array_column($tecnicos, 'gru_codigo'))) {
                    $where['a.idTecnico = ?'] = $this->idUsuario;
                }

                $where['a.idOrgao = ?'] = $this->grupoAtivo->codOrgao;
                $where['a.siEncaminhamento = ?'] = \Solicitacao_Model_TbSolicitacao::SITUACAO_ENCAMINHAMENTO_ENCAMINHADA_AO_MINC;
            }
        }

        $obterSolicitacoes = new \Solicitacao_Model_DbTable_TbSolicitacao();
        $solicitacoes = $obterSolicitacoes->obterSolicitacoes($where)->toArray();

        foreach ($solicitacoes as $key => $solicitacao) {
            $solicitacoes[$key]['dsSolicitacao'] = $this->removeHtmlTags($solicitacao['dsSolicitacao']);
            $solicitacoes[$key]['dsResposta'] = $this->removeHtmlTags($solicitacao['dsResposta']);
        }

        array_walk($solicitacoes, function (&$value) {
            $value = array_map('utf8_encode', $value);
        });

        return $solicitacoes;
    }

    private function removeHtmlTags($string)
    {
        $result = $this->stripTags($string);
        return $this->stringReplace($result);
    }

    private function stripTags($string)
    {
        return strip_tags($string);
    }

    private function stringReplace($string)
    {
        return str_replace('&nbsp;', '', $string);
    }
}
