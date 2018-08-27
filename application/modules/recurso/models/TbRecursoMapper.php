<?php

class Recurso_Model_TbRecursoMapper extends MinC_Db_Mapper
{
    public function __construct()
    {
        parent::setDbTable('Recurso_Model_DbTable_TbRecurso');
    }

    public function save($model)
    {
        return parent::save($model);
    }

    public function obterProjetoPassivelDeRecurso($idPronac, $cpfCnpj, $siFaseProjeto = 2)
    {
        try {

            if (empty($idPronac)) {
                throw new Exception("IdPronac n&atilde;o informado");
            }

            $dbTableProjetos = new Projeto_Model_DbTable_Projetos();

            $whereProjeto = [];
            $whereProjeto['projeto.IdPRONAC = ?'] = $idPronac;
            $whereProjeto['projeto.Situacao in (?)'] = Recurso_Model_TbRecurso::obterSituacoesPassiveisDeRecurso();

            if ($cpfCnpj) {
                $whereProjeto['projeto.CgcCpf = ?'] = $cpfCnpj;
            }

            $projeto = $dbTableProjetos->obterProjetosESituacao($whereProjeto)->current()->toArray();

            if (empty($projeto)) {
                return false;
            }

            $projeto['tpSolicitacao'] = Recurso_Model_TbRecurso::TIPO_RECURSO_ORCAMENTO;
            $projeto['diasParaRecurso'] = Recurso_Model_TbRecurso::PRAZO_RECURSAL_ORCAMENTO - (int)$projeto['diasSituacao'];

            if (in_array($projeto['situacao'], Recurso_Model_TbRecurso::SITUACOES_RECURSO_PROJETO_INDEFERIDO)) {
                $projeto['tpSolicitacao'] = Recurso_Model_TbRecurso::TIPO_RECURSO_PROJETO_INDEFERIDO;

                $tbReuniao = new tbreuniao();
                $reuniao = $tbReuniao->obterReuniaoDeAvaliacaoDoProjeto($idPronac);
                $projeto['diasParaRecurso'] = Recurso_Model_TbRecurso::PRAZO_RECURSAL_INDEFERIDO - (int)$reuniao->diasAnaliseProjeto;
            }

            if (!$this->isPassivelRecurso($projeto)) {
                return false;
            }

            return $projeto;
        } catch (Exception $objException) {
            throw $objException;
        }
    }

    public function isPassivelRecurso($projeto, $siFaseProjeto = 2)
    {

        $tbRecurso = new tbRecurso();
        $whereRecursoExistente = [
            'IdPRONAC = ?' => $projeto['idPronac'],
            'tpSolicitacao = ?' => $projeto['tpSolicitacao'],
        ];

        if ($siFaseProjeto) {
            $whereRecursoExistente['siFaseProjeto = ?'] = $siFaseProjeto;
        }

        $recursoExistente = $tbRecurso->buscar($whereRecursoExistente, ['idRecurso DESC']);

        if (count($recursoExistente) == 0) {
            return true;
        }


        /**
         *  'stEstado = ?' => 1,
         *  'siRecurso in (?)' => [9, 15],
         */
        # 9 => solicitacao encaminhada portaria,
        # 15 - solicitacao finalizada pelo minc
        #tbTipoEncaminhamento

        $projeto['tpRecurso'] = Recurso_Model_TbRecurso::PEDIDO_DE_RECONSIDERACAO;
        if (count($recursoExistente) > 0) {

            /**
             * se existir um recurso do tipo 2, o proponente não pode entrar com novo recurso, ou seja,
             * esse projeto não tem direito à recurso nessa fase.
             */
            if ($recursoExistente['tpRecurso'] == Recurso_Model_TbRecurso::RECURSO) {
                return false;
            }

            $projeto['tpRecurso'] = Recurso_Model_TbRecurso::RECURSO;
        }

        return $projeto;
    }

    public function inserirRecurso($recurso)
    {
        try {

            if (empty($recurso['idPronac'])) {
                throw new Exception("IdPronac &eacute; obrigat&oacute;rio");
            }

            if (empty($recurso['dsSolicitacaoRecurso']) || strlen($recurso['dsSolicitacaoRecurso']) < 5) {
                throw new Exception ("Texto do recurso &eacute; obrigat&oacute;rio!");
            }

            $auth = Zend_Auth::getInstance();
            $dados = [
                'IdPRONAC' => $recurso['idPronac'],
                'dtSolicitacaoRecurso' => new Zend_Db_Expr('GETDATE()'),
                'dsSolicitacaoRecurso' => $recurso['dsSolicitacaoRecurso'],
                'idAgenteSolicitante' => $auth->getIdentity()->IdUsuario,
                'stAtendimento' => 'N',
                'tpSolicitacao' => $recurso['tpSolicitacao']
            ];

            $tbRecurso = new tbRecurso();
            $whereRecursoExistente = [
                'IdPRONAC = ?' => $recurso['idPronac'],
                'tpSolicitacao = ?' => $recurso['tpSolicitacao'],
            ];

            $recursoExistente = $tbRecurso->buscar($whereRecursoExistente, ['idRecurso DESC'])->current();

            $dados['tpRecurso'] = Recurso_Model_TbRecurso::PEDIDO_DE_RECONSIDERACAO;
            if(count($recursoExistente) > 0) {
                if ($recursoExistente->tpRecurso == Recurso_Model_TbRecurso::RECURSO) {
                    throw new Exception("Voc&ecirc; n&atilde;o pode solicitar recurso");
                }

                $dados['tpRecurso'] = Recurso_Model_TbRecurso::RECURSO;
            }

            if (!$tbRecurso->inserir($dados)) {
                return false;
            }

//            $tbProjetos = new Projetos();
//            $tbProjetos->alterarSituacao($recurso['idPronac'], '', $situacao, $providenciaTomada);
            ProjetoDAO::alterarSituacao($recurso['idPronac'], 'D20');

            return true;
        } catch (Exception $objException) {
            throw $objException;
        }
    }
}
