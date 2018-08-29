<?php

class AvaliacaoResultados_Model_tbEncaminhamentoPrestacaoContas extends MinC_Db_Model
{
    protected $_idEncPrestContas;
    protected $_idSituacaoEncPrestContas;
    protected $_idPronac;
    protected $_stAtivo;
    protected $_dtInicioEncaminhamento;
    protected $_dtFimEncaminhamento;
    protected $_idAgenteOrigem;
    protected $_idAgenteDestino;
    protected  $_idOrgao;

    /**
     * @return mixed
     */
    public function getIdEncPrestContas()
    {
        return $this->_idEncPrestContas;
    }

    /**
     * @param mixed $idEncPrestContas
     * @return AvaliacaoResultados_Model_tbEncaminhamentoPrestacaoContas
     */
    public function setIdEncPrestContas($idEncPrestContas)
    {
        $this->_idEncPrestContas = $idEncPrestContas;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdSituacaoEncPrestContas()
    {
        return $this->_idSituacaoEncPrestContas;
    }

    /**
     * @param mixed $idSituacaoEncPrestContas
     * @return AvaliacaoResultados_Model_tbEncaminhamentoPrestacaoContas
     */
    public function setIdSituacaoEncPrestContas($idSituacaoEncPrestContas)
    {
        $this->_idSituacaoEncPrestContas = $idSituacaoEncPrestContas;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdPronac()
    {
        return $this->_idPronac;
    }

    /**
     * @param mixed $idPronac
     * @return AvaliacaoResultados_Model_tbEncaminhamentoPrestacaoContas
     */
    public function setIdPronac($idPronac)
    {
        $this->_idPronac = $idPronac;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStAtivo()
    {
        return $this->_stAtivo;
    }

    /**
     * @param mixed $stAtivo
     * @return AvaliacaoResultados_Model_tbEncaminhamentoPrestacaoContas
     */
    public function setStAtivo($stAtivo)
    {
        $this->_stAtivo = $stAtivo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDtInicioEncaminhamento()
    {
        return $this->_dtInicioEncaminhamento;
    }

    /**
     * @param mixed $dtInicioEncaminhamento
     * @return AvaliacaoResultados_Model_tbEncaminhamentoPrestacaoContas
     */
    public function setDtInicioEncaminhamento($dtInicioEncaminhamento)
    {
        $this->_dtInicioEncaminhamento = $dtInicioEncaminhamento;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDtFimEncaminhamento()
    {
        return $this->_dtFimEncaminhamento;
    }

    /**
     * @param mixed $dtFimEncaminhamento
     * @return AvaliacaoResultados_Model_tbEncaminhamentoPrestacaoContas
     */
    public function setDtFimEncaminhamento($dtFimEncaminhamento)
    {
        $this->_dtFimEncaminhamento = $dtFimEncaminhamento;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdAgenteOrigem()
    {
        return $this->_idAgenteOrigem;
    }

    /**
     * @param mixed $idAgenteOrigem
     * @return AvaliacaoResultados_Model_tbEncaminhamentoPrestacaoContas
     */
    public function setIdAgenteOrigem($idAgenteOrigem)
    {
        $this->_idAgenteOrigem = $idAgenteOrigem;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdAgenteDestino()
    {
        return $this->_idAgenteDestino;
    }

    /**
     * @param mixed $idAgenteDestino
     * @return AvaliacaoResultados_Model_tbEncaminhamentoPrestacaoContas
     */
    public function setIdAgenteDestino($idAgenteDestino)
    {
        $this->_idAgenteDestino = $idAgenteDestino;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdOrgao()
    {
        return $this->_idOrgao;
    }

    /**
     * @param mixed $idOrgao
     * @return AvaliacaoResultados_Model_tbEncaminhamentoPrestacaoContas
     */
    public function setIdOrgao($idOrgao)
    {
        $this->_idOrgao = $idOrgao;
        return $this;
    }

}