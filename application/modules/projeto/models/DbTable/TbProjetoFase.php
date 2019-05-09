<?php

class Projeto_Model_DbTable_TbProjetoFase extends MinC_Db_Table_Abstract
{
    protected $_schema = 'sac';
    protected $_name = 'TbProjetoFase';
    protected $_primary = 'idNormativo';

    public function obterNormativoProjeto(
        $where,
        $order = null,
        $start = 0,
        $limit = 20)
    {
        if (empty($where)) {
            return [];
        }

        $sql = $this->select()
            ->setIntegrityCheck(false)
            ->from(
                ['a' => 'Projetos'],
                [
                    new Zend_Db_Expr('a.AnoProjeto+a.Sequencial as Pronac'),
                    'a.IdPRONAC',
                    'a.NomeProjeto',
                ],
                $this->_schema
            );

        $sql->joinLeft(
            ['b' => $this->_name],
            'a.IdPRONAC = b.idPronac',
            [
                'b.idNormativo',
            ],
            $this->_schema
        );

        $sql->where('b.stEstado = ?', 1);

        foreach ($where as $coluna => $valor) {
            $sql->where($coluna, $valor);
        }

        if (!empty($order)) {
            $sql->order($order);
        }

        if (!is_null($start) && $limit) {
            $start = (int) $start;
            $limit = (int) $limit;
            $sql->limit($limit, $start);
        }

        return $this->fetchAll($sql);
    }

    public function isNormativo2019ByIdPreProjeto($idPreProjeto)
    {
        $projeto = $this->obterNormativoProjeto(['a.idProjeto = ?' => $idPreProjeto])->current();
        return (empty($projeto)
            || $projeto->idNormativo >= Projeto_Model_TbNormativo::INSTRUCAO_NORMATIVA_2019);
    }
}
