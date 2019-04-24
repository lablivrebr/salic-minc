<?php
/**
 * Class Proposta_Model_DbTable_Verificacao
 *
 * @name Proposta_Model_DbTable_Verificacao
 * @package Modules/Agente
 * @subpackage Models/DbTable
 * @version $Id$
 *
 * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
 * @since 21/09/2016
 *
 * @copyright © 2012 - Ministerio da Cultura - Todos os direitos reservados.
 * @link http://salic.cultura.gov.br
 */
class Proposta_Model_DbTable_Verificacao extends MinC_Db_Table_Abstract
{
    protected $_schema = 'sac';
    protected $_name  = 'verificacao';

    public function buscarFonteRecurso()
    {
//        $sql = "select Verificacao.idVerificacao, ltrim(Verificacao.Descricao) as VerificacaoDescricao
//                from SAC.dbo.Verificacao as Verificacao
//                inner join SAC.dbo.Tipo as Tipo
//                on Verificacao.idTipo = Tipo.idTipo
//                where Tipo.idTipo = 5";
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(
            array('v' => $this->_name),
            array(
                'idverificacao',
                $this->getExpressionTrim('v.descricao', 'verificacaodescricao'),
            ),
            $this->_schema
            );
        $select->joinInner(
            array('tipo'=>'Tipo'),
            'v.idtipo = tipo.idtipo',
            null,
            $this->_schema
        );
        $select->where('tipo.idtipo = ?', '5');

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($select);
    }

    public function buscarTipicidades()
    {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(
            ['a' => $this->_name],
            [
                'idVerificacao',
                new Zend_Db_Expr('
                    CASE
                        WHEN a.idVerificacao = 713 THEN 31
                        WHEN a.idVerificacao = 714 THEN 30
                        WHEN a.idVerificacao = 715 THEN 29
                    END as tipo'
                ),
                'Descricao as tipicidade'
            ],
            $this->_schema
        );
        $select->where('a.idTipo IN (?)', [28]);
        return $this->fetchAll($select);
    }
}
