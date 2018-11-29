<?php
/**
 * DAO tbTipoReadequacao
 * @since 28/02/2014
 */

class Readequacao_Model_DbTable_TbTipoReadequacao extends MinC_Db_Table_Abstract
{
    protected $_banco  = "SAC";
    protected $_schema = "SAC";
    protected $_name   = "tbTipoReadequacao";

    public function buscarTiposReadequacoesPermitidos($idPronac)
    {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(
            array('a' => $this->_name),
            array(
                new Zend_Db_Expr("idTipoReadequacao, dsReadequacao"),
            )
        );

        $select->where('siReadequacao = ?', 0);
        $select->where('stEstado = ?', 0);

        $select->where(new Zend_Db_Expr("idTipoReadequacao not in (
            SELECT idTipoReadequacao FROM SAC.dbo.tbReadequacao WHERE idPronac = $idPronac 
            AND stEstado = " . Readequacao_Model_DbTable_TbReadequacao::ST_ESTADO_EM_ANDAMENTO . " 
            AND stAtendimento != '" . Readequacao_Model_DbTable_TbReadequacao::ST_ATENDIMENTO_DEVOLVIDA . "'
        )"));

        $select->order('2');

        return $this->fetchAll($select);
    }
}
