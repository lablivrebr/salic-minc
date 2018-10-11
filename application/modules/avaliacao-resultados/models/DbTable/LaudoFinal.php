<?php

class AvaliacaoResultados_Model_DbTable_LaudoFinal extends MinC_Db_Table_Abstract
{
    protected $_name = "tbLaudoFinal";
    protected $_schema = "SAC";
    protected $_primary = "idLaudoFinal";

    public function all() {
        return $this->fetchAll();
    }

    public function findBy($id)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select();
        $select->from(
            array('a' => $this->_name),
            array('*'),
            $this->_schema
        )
        ->where('idPronac = ? ', $id);

        return $db->fetchRow($select);
    }

    public function projetosLaudoFinal()
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select();
        $select->from(
            ['p' => 'Projetos'],
            ['p.IdPronac', 'p.NomeProjeto', 'av.siManifestacao'],
            'sac.dbo'
        )
        ->join(['doc'=>'tbDocumentoAssinatura'], 'p.IdPRONAC=doc.IdPRONAC', null, 'sac.dbo')
        ->join(['av'=>'tbAvaliacaoFinanceira'], 'av.idPronac=p.IdPRONAC', null, 'sac.dbo')
        ->where('doc.idTipoDoAtoAdministrativo=622')
        ->where('doc.cdSituacao=2')
        ->where('doc.stEstado=1');
        
        return $db->fetchAll($select);
    }
}