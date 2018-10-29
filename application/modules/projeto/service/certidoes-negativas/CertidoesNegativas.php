<?php

namespace Application\Modules\Projeto\Service\CertidoesNegativas;

use Seguranca;

class CertidoesNegativas implements \MinC\Servico\IServicoRestZend
{
    /**
     * @var \Zend_Controller_Request_Abstract $request
     */
    private $request;

    /**
     * @var \Zend_Controller_Response_Abstract $response
     */
    private $response;

    function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function buscarCertidoesNegativas()
    {
        $idPronac = $this->request->idPronac;

        if (strlen($idPronac) > 7) {
            $idPronac = \Seguranca::dencrypt($idPronac);
        }

        $Projetos = new \Projetos();
        $rs = $Projetos->buscar(array('IdPRONAC = ?' => $idPronac))->current();

        $sv = new \certidaoNegativa();
        $resultado = $sv->buscarCertidaoNegativa($rs->CgcCpf);

        $certidoes = [];
        $resultArray = [];

        foreach ($resultado as $item) {
            $dsCertidao = html_entity_decode($item['dsCertidao']);
            $situacao = html_entity_decode($item['Situacao']);
            $objDateTimeDtEmissao = new \DateTime($item['DtEmissao']);
            $objDateTimeDtValidade = new \DateTime($item['DtValidade']);

            $itemArray = [
                'dsCertidao' => $dsCertidao,
                'CodigoCertidao' => $item['CodigoCertidao'],
                'DtEmissao' => $objDateTimeDtEmissao->format('d/m/Y H:i:s'),
                'DtValidade' => $objDateTimeDtValidade->format('d/m/Y H:i:s'),
                'Pronac' => $item['Pronac'],
                'Situacao' => $situacao,
            ];

            $certidoes[] = $itemArray;
        }


        $resultArray['certidoes'] = $certidoes;

        return $resultArray;
    }
}
