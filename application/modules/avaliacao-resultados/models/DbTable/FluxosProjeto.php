<?php

class AvaliacaoResultados_Model_DbTable_FluxosProjeto extends MinC_Db_Table_Abstract
{
    protected $_name = "FluxosProjeto";
    protected $_schema = "SAC";
    protected $_primary = "id";

    public function projetos($estadoId, $idAgente = null)
    {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(
            array('e' => $this->_name),
            array('*'),
            $this->_schema
        )
        ->join(
            ['p' => 'projetos'],
            'p.idpronac = e.idpronac',
            ['*', new Zend_Db_Expr('anoprojeto+sequencial as PRONAC')],
            $this->_schema
        )
        //inner join Tabelas.dbo.Usuarios as a ON a.usu_codigo = fp.idAgente
        ->joinLeft(
            ['u' => 'Usuarios'],
            'u.usu_codigo = e.idAgente',
            ['u.usu_nome'],
            'Tabelas.dbo'
        )
        ->where('estadoId = ? ', $estadoId);

        if($idAgente) {
            $select->where('idAgente = ? ', $idAgente);
        }

        return $this->fetchAll($select);
    }

    public function estado($idPronac)
    {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(
            array('e' => $this->_name),
            array('*'),
            $this->_schema
        )
        ->where('idpronac = ? ', $idPronac);

        return $this->fetchRow($select);
    }
}