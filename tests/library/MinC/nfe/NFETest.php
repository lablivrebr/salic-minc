<?php

class NFETest extends MinC_Test_Abstract
{
    public function setUp()
    {
    }

    public function testIndexAction()
    {
        $this->dispatch("/solicitacao/mensagem/index");
        $this->assertUrl('solicitacao', 'mensagem', 'index');
    }
}
