describe('Testes da Avaliação de Resultados: Laudo Final', () => {
    it('Alterando perfil', () => {
      cy.mudarPerfil(126, 303); //perfil Coordenador Geral
 
      cy.wait(1000);
    });

    it('Acesso ao menu de Avaliação de Resultados', () => {
      cy.get('.left > :nth-child(4) > .dropdown-button').contains('Avaliação de Resultados').click(); //Selecionando o menu
      cy.wait(1000);
      cy.get('#prestacao-contas > :nth-child(5) > a').contains('Analisar Laudo Final (Novo)').click(); //Selecionando o item do menu

      cy.wait(1000);
      
    });
    
    it('Renderização do Painel do Laudo Final - Perfil Coordenador Geral', () => {
      cy.get('div.v-card .theme--light');
    });
    
    it('Verifica se há pelo menos um item na tabela - Aba "Em análise" ', () => {
      cy.get('#emAnalise a').click();
      cy.get('tbody > :nth-child(1) > .text-xs-right').should('not.be.empty');
      
      cy.wait(1000);
    });

    it('Executar o click na Manifestação da aba Em análise', () => {
      cy.get(':nth-child(1) > :nth-child(1) > :nth-child(2) > .v-table__overflow > .v-datatable > tbody > tr > :nth-child(4) > .v-btn').click();
      
      cy.wait(2000);
      
      cy.get('.v-toolbar__content > .v-btn > .v-btn__content > .v-icon').click();
      
      cy.wait(1000);
    });

    it('Executar o click no Devolver da aba Em análise', () => {
      cy.get(':nth-child(1) > :nth-child(1) > :nth-child(2) > .v-table__overflow > .v-datatable > tbody > tr > :nth-child(5) > .v-dialog__container > .v-dialog__activator > .v-tooltip > span > .v-btn').click();
      
      cy.wait(2000);
      
      cy.get('.v-dialog__content--active > .v-dialog > .v-card > .v-card__actions > .error--text').click();
      
      cy.wait(1000);
    });

    it('Executar o click na ação da aba Em análise', () => {
      cy.get(':nth-child(1) > :nth-child(1) > :nth-child(2) > .v-table__overflow > .v-datatable > tbody > tr > :nth-child(6) > .v-btn').click();
      
      cy.wait(2000);
      
      cy.get('.v-card > .v-toolbar > .v-toolbar__content > .v-btn--icon').click();

      cy.wait(1000);
    });

    it('Verifica se há pelo menos um item na tabela - Aba "Assinar" ', () => {
      cy.get(':nth-child(3) > .v-tabs__item').click();
      cy.get('tbody > :nth-child(1) > :nth-child(2)').should('not.be.empty');

      cy.wait(1000);
    });

    it('Verifica se há pelo menos um item na tabela - Aba "Em Assinatura" ', () => {
      cy.get(':nth-child(4) > .v-tabs__item').click();
      cy.get('tbody > :nth-child(1) .text-xs-right').should('not.be.empty');

      cy.wait(1000);
    });

    it('Verifica se há pelo menos um item na tabela - Aba "Finalizados" ', () => {
      cy.get(':nth-child(5) > .v-tabs__item').click();
      cy.get('tbody > :nth-child(1) .text-xs-right').should('not.be.empty');

      cy.wait(1000);
    });

    it('Executar o click na ação da aba Finalizados', () => {
      cy.get(':nth-child(4) > :nth-child(1) > :nth-child(2) > .v-table__overflow > .v-datatable > tbody > tr > :nth-child(6) > .v-btn').click();
    
      cy.wait(2000);

      cy.get('.v-card > .v-toolbar > .v-toolbar__content > .v-btn').click();
    });
  });