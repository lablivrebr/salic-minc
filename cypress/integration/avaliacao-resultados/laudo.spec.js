describe('Testes da Avaliação de Resultados: Laudo Final', function() {
    before(function () { // Inicia todos os testes na Tela Avaliação de Resultados: Laudo final - Perfil Coordenador Geral de Prestação de Contas
      cy.visit('localhost');
    
      var login = cy.get('form div div').find('input').eq(0).type('239.691.561-49'); // Login
      var senha = cy.get('form div div').find('input').eq(1).type('123456'); // Senha
      cy.get('form').submit();
    
      cy.visit('http://localhost/principal');
    
      cy.get('#combousuario div input').click()
      .get(':nth-child(55) > span').click(); //perfil Coordenador Geral
 
      cy.wait(2000);

      cy.get('ul li a.dropdown-button').contains('Avaliação de Resultados').click()
        .get('#prestacao-contas li a').contains('Analisar Laudo Final (Novo)').click();
    
      cy.wait(2000);
      
    });
    
    it('Renderização do Painel do Laudo Final - Perfil Coordenador Geral', function() {
      cy.get('div.v-card .theme--light');
    });
    
    it('Verifica se há pelo menos um item na tabela - Aba "Em análise" ', function() {
      cy.get(':nth-child(2) > .v-tabs__item').click();
      cy.get('tbody > :nth-child(1) > .text-xs-right').should('not.be.empty');
      
      cy.wait(1000);
    });

    it('Executar o click no PRONAC da aba Em análise', function() {
      cy.get(':nth-child(1) > :nth-child(1) > :nth-child(2) > .v-table__overflow > .v-datatable > tbody > tr > :nth-child(2) > .flex > :nth-child(1) > .v-btn--active').click();
      cy.go('forward');
      cy.url('http://localhost/projeto/#/150056');
      cy.wait(2000);
      
    //   cy.get('.v-toolbar__content > .v-btn > .v-btn__content > .v-icon').click();
      
    //   cy.wait(1000);
    });

    it('Executar o click na Manifestação da aba Em análise', function() {
      cy.get(':nth-child(1) > :nth-child(1) > :nth-child(2) > .v-table__overflow > .v-datatable > tbody > tr > :nth-child(6) > .v-btn').click();
      
      cy.wait(2000);
      
      cy.get('.v-toolbar__content > .v-btn > .v-btn__content > .v-icon').click();
      
      cy.wait(1000);
    });

    it('Executar o click no Devolver da aba Em análise', function() {
      cy.get(':nth-child(1) > :nth-child(1) > :nth-child(2) > .v-table__overflow > .v-datatable > tbody > tr > :nth-child(5) > .v-dialog__container > .v-dialog__activator > .v-tooltip > span > .v-btn').click();
      
      cy.wait(2000);
      
      cy.get('.v-dialog__content--active > .v-dialog > .v-card > .v-card__actions > .error--text').click();
      
      cy.wait(1000);
    });

    it('Executar o click na ação da aba Em análise', function() {
      cy.get(':nth-child(1) > :nth-child(1) > :nth-child(2) > .v-table__overflow > .v-datatable > tbody > tr > :nth-child(6) > .v-btn').click();
      
      cy.wait(2000);
      
      cy.get('.v-card > .v-toolbar > .v-toolbar__content > .v-btn--icon').click();

      cy.wait(1000);
    });

    it('Verifica se há pelo menos um item na tabela - Aba "Assinar" ', function() {
      cy.get(':nth-child(3) > .v-tabs__item').click();
      cy.get('tbody > :nth-child(1) > :nth-child(2)').should('not.be.empty');
    });

    // it('Executar o click na ação da aba Assinar', function() {
    //   cy.get(':nth-child(1) > :nth-child(1) > :nth-child(2) > .v-table__overflow > .v-datatable > tbody > tr > :nth-child(6) > .v-btn').click();
      
    //   cy.wait(2000);
      
    //   cy.get('.v-card > .v-toolbar > .v-toolbar__content > .v-btn--icon').click();

    //   cy.wait(1000);
    // });

    it('Verifica se há pelo menos um item na tabela - Aba "Em Assinatura" ', function() {
      cy.get(':nth-child(4) > .v-tabs__item').click();
      cy.get('tbody > :nth-child(1) .text-xs-right').should('not.be.empty');
    });

    it('Verifica se há pelo menos um item na tabela - Aba "Finalizados" ', function() {
      cy.get(':nth-child(5) > .v-tabs__item').click();
      cy.get('tbody > :nth-child(1) .text-xs-right').should('not.be.empty');
    });

    it('Executar o click na ação da aba Finalizados', function() {
      cy.get(':nth-child(4) > :nth-child(1) > :nth-child(2) > .v-table__overflow > .v-datatable > tbody > tr > :nth-child(6) > .v-btn').click();
    
      cy.wait(2000);

      cy.get('.v-card > .v-toolbar > .v-toolbar__content > .v-btn').click();
    });
  });