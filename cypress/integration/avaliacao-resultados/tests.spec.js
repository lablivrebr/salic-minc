describe('Testes da Análise de Resultados', function() {
    before(function () { // Inicia todos os testes na Tela de Análise de Resultados - Perfil Técnico
      cy.visit('localhost');
    
      var login = cy.get('form div div').find('input').eq(0).type('239.691.561-49'); // Login
      var senha = cy.get('form div div').find('input').eq(1).type('123456'); // Senha
      cy.get('form').submit();
    
      cy.visit('http://localhost/principal');
    
      cy.get('#combousuario div input').click()
      .get('ul li span').eq(59).click();
 
      // cy.server()
      // cy.route('/avaliacao-resultados/fluxo-projeto?estadoid=5&idAgente=236', 'fixtures:avaliacao-resultados/projetos.json');

      cy.get('ul li a.dropdown-button').contains('Avaliação de Resultados').click()
        .get('#prestacao-contas li a').contains('Analisar Parecer (Nova)').click();
    
      cy.wait(4000);
  
    });
  
    it('Renderização da tabela de análise de resultados - Perfil Técnico', function() {
      cy.get('div.v-card .theme--light');
    });

    it('Verifica se há pelo menos um item na tabela - Aba "Em análise" ', function() {
      cy.get('tbody > :nth-child(1) > .text-xs-right').should('not.be.empty');
    });

    it('Verifica se há pelo menos um item na tabela - Aba "Assinar" ', function() {
      cy.get('div.v-tabs__div a').contains('Assinar').click();
      cy.get('tbody > :nth-child(1) .text-xs-right').should('not.be.empty');
    });

    it('Verifica se há pelo menos um item na tabela - Aba "Histórico" ', function() {
      cy.get('div.v-tabs__div a').contains('Historico').click();
      cy.get('tbody > :nth-child(1) .text-xs-right').should('not.be.empty');
    });
  });