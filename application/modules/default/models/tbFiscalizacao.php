<?php

/**
 * Description of Projetos
 *
 * @author Andr� Nogueira Pereira
 */
class tbFiscalizacao extends MinC_Db_Table_Abstract
{
    protected $_name = 'tbFiscalizacao';
    protected $_schema = 'SAC';
    protected $_banco = 'SAC';

    public function buscarAtoresFiscalizacao($idPronac, $idusuario = null)
    {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(
            array('fisc' => $this->_name),
            array()
        );

        $select->joinInner(
            array('nm' => 'Nomes'),
            'nm.idAgente = fisc.idAgente',
            array('Nome' => 'nm.Descricao'),
            'AGENTES.dbo'
        );
        $select->joinInner(
            array('ag' => 'Agentes'),
            'ag.idAgente = fisc.idAgente',
            array('ag.idAgente'),
            'AGENTES.dbo'
        );
        $select->joinInner(
            array('usu' => 'Usuarios'),
            'ag.CNPJCPF = usu.usu_identificacao',
            array(),
            'TABELAS.dbo'
        );
        $select->joinInner(
            array('uog' => 'UsuariosXOrgaosXGrupos'),
            'usu.usu_codigo = uog.uog_usuario and uog.uog_status = 1',
            array(),
            'TABELAS.dbo'
        );
        $select->joinInner(
            array('org' => 'Orgaos'),
            'org.Codigo = uog.uog_orgao',
            array('Orgao' => 'org.Sigla'),
            'SAC.dbo'
        );
        $select->joinInner(
            array('gru' => 'Grupos'),
            'gru.gru_codigo = uog.uog_grupo',
            array('Perfil' => 'gru.gru_nome', 'cdPerfil' => 'gru.gru_codigo'),
            'TABELAS.dbo'
        );
        $select->where('gru.gru_codigo = 135 or gru.gru_codigo = 134');
        //$select->where('usu.usu_codigo <> ?', $idusuario);
        $select->where('fisc.IdPRONAC = ?', $idPronac);

        return $this->fetchAll($select);
    }

    public function gridFiscalizacaoProjetoFiltro($where = array(), $order = array(), $tamanho = -1, $inicio = -1, $qtdeTotal = false)
    {
        $queryFiscalizacao = $this->select();
        $queryFiscalizacao->setIntegrityCheck(false);
        $queryFiscalizacao->from(array("tbFiscalizacao" => $this->_name), array('tbFiscalizacao.IdPRONAC, tbFiscalizacao.stFiscalizacaoProjeto'), $this->_schema);
        $queryFiscalizacao->where('stFiscalizacaoProjeto = ?', '0');
        $queryFiscalizacao->orWhere('stFiscalizacaoProjeto = ?', '1');

        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(
            array("p" => 'projetos'),
            array(
                new Zend_Db_Expr('p.IdPRONAC'),
                'p.AnoProjeto',
                'p.Sequencial',
                'p.Area',
                'p.Situacao',
                'p.Segmento',
                'p.Mecanismo',
                'p.idProjeto',
                'p.NomeProjeto',
                'p.CgcCpf',
                new Zend_Db_Expr('sac.dbo.fnTotalCaptadoProjeto(p.AnoProjeto, p.Sequencial) as Total'),
                new Zend_Db_Expr('sac.dbo.fnTotalAprovadoProjeto(p.AnoProjeto, p.Sequencial) as  somatorio')
            )
        );
        $select->joinLeft(
            array('pr' => 'PreProjeto'),
            'p.idProjeto = pr.idPreProjeto',
            array('stPlanoAnual'),
            $this->_schema
        );
        $select->joinLeft(
            array('nom' => 'Nomes'),
            "nom.idAgente = pr.idAgente",
            array('nom.Descricao AS nmAgente'),
            'Agentes.dbo'
        );
        $select->joinInner(
            array('s' => 'Segmento'),
            'p.Segmento = s.Codigo',
            array('Descricao AS dsSegmento'),
            $this->_schema
        );
        $select->joinInner(
            array('si' => 'Situacao'),
            'p.Situacao = si.Codigo',
            array('Descricao AS dsSituacao'),
            $this->_schema
        );
        $select->joinInner(
            array('a' => 'Area'),
            'p.Area = a.Codigo',
            array('Descricao AS dsArea'),
            $this->_schema
        );
        $select->joinInner(
            array('m' => 'Mecanismo'),
            'p.Mecanismo = m.Codigo',
            array('Descricao AS dsMecanismo'),
            $this->_schema
        );
        $select->joinLeft(
            array('e' => 'EnderecoNacional'),
            'pr.idAgente = e.idAgente',
            array(),
            "AGENTES.dbo"
        );
        $select->joinLeft(
            array('u' => 'UF'),
            'u.idUF = e.UF',
            array('Regiao', 'Sigla as uf'),
            "AGENTES.dbo"
        );
        $select->joinLeft(
            array('mu' => 'Municipios'),
            'mu.idUFIBGE = e.UF and mu.idMunicipioIBGE = e.Cidade',
            array('Descricao AS cidade'),
            "AGENTES.dbo"
        );
        $select->joinLeft(
            array('tf' => 'tbFiscalizacao'),
            'tf.IdPRONAC = p.IdPRONAC',
            array('idFiscalizacao',
                'dtInicioFiscalizacaoProjeto',
                'dtFimFiscalizacaoProjeto',
                'stFiscalizacaoProjeto',
                'dsFiscalizacaoProjeto',
                'dtRespostaSolicitada',
                'idUsuarioInterno as idTecnico'
            ),
            $this->_schema
        );
        $select->joinLeft(
            array('tbNm' => 'Nomes'),
            "tf.idAgente = tbNm.idAgente",
            array('nmTecnico' => 'tbNm.Descricao'),
            $this->getSchema('agentes')
        );
        $select->joinLeft(
            array('trf' => 'tbRelatorioFiscalizacao'),
            'tf.idFiscalizacao = trf.idFiscalizacao',
            array('stAvaliacao'),
            $this->_schema
        );
//        $select->joinLeft(
//            array('AUXF' => $queryFiscalizacao),
//            'AUXF.IdPRONAC = tf.IdPRONAC'
//        );

        //adiciona quantos filtros foram enviados
        foreach ($where as $coluna => $valor) {
            $select->where($coluna, $valor);
        }

        if ($qtdeTotal) {
            return $this->fetchAll($select)->count();
        }

        $select->order($order);

        if ($tamanho > -1) {
            $tmpInicio = 0;
            if ($inicio > -1) {
                $tmpInicio = $inicio;
            }
            $select->limit($tamanho, $tmpInicio);
        }

        return $this->fetchAll($select);
    }

}
