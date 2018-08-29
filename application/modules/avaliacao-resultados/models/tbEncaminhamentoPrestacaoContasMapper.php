<?php

class AvaliacaoResultados_Model_tbEncaminhamentoPrestacaoContasMapper extends MinC_Db_Mapper
{
    public function __construct()
    {
        $this->setDbTable('AvaliacaoResultados_Model_DbTable_tbEncaminhamentoPrestacaoContas');
    }

    public function save($model)
    {
        if ($this->isValid($model)) {
            return parent::save($model);
        }
        return false;
    }


    public function isValid( $model)
    {
        $booStatus = true;
        $arrData = $model->toArray();
        $arrRequired = [
            'idPronac',
        ];

        foreach ($arrRequired as $strValue) {
            if (!isset($arrData[$strValue]) || empty($arrData[$strValue])) {
                $this->setMessage('Campo obrigat&oacute;rio!', $strValue);
                $booStatus = false;
            }
        }

        return $booStatus;
    }
}