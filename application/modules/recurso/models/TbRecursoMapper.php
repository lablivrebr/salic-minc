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

    public function obterProjetoPassivelDeRecurso($idPronac, $cpfCnpj = null)
    {
        try {

            if (empty($idPronac)) {
                throw new Exception("IdPronac n&atilde;o informado");
            }

            $dbTableProjetos = new Projeto_Model_DbTable_Projetos();

            $where = [];
            $where['projeto.IdPRONAC = ?'] = $idPronac;
            $where['projeto.Situacao in (?)'] = Recurso_Model_TbRecurso::SITUACOES_PASSIVEIS_DE_RECURSO_PADRAO;

            if ($cpfCnpj) {
                $where['projeto.CgcCpf = ?'] = $cpfCnpj;
            }

            $projeto = $dbTableProjetos->obterProjetosComSituacao($where)->current()->toArray();

            if (!empty($projeto)) {
                $projeto['statusProjeto'] = 'Projeto Indeferido';
                if ($projeto['situacao'] == 'D02' || $projeto['situacao'] == 'D03') {
                    $projeto['statusProjeto'] = 'Projeto Aprovado';
                }
            }

            return $projeto;
        } catch (Exception $objException) {
            throw $objException;
        }
    }
}
