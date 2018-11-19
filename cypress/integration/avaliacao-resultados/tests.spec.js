describe('Testes da Análise de Resultados', function() {
    before(function () { // Inicia todos os testes na Tela de Análise de Resultados - Perfil Técnico
      cy.get('#combousuario div input').click()
      .get('ul li span').eq(59).click();
 

      cy.get('ul li a.dropdown-button').contains('Avaliação de Resultados').click()
        .get('#prestacao-contas li a').contains('Analisar Parecer (Nova)').click();
    
      cy.wait(4000);
  
    });
    
    it('Verifica se há pelo menos um item na tabela - Aba "Em análise" ', function() {
      cy.get('div.v-tabs__div a').contains('Em Analise').click();
      cy.get('tbody > :nth-child(1) > .text-xs-right').should('not.be.empty');
    });
      
    it('Renderização da tabela de análise de resultados - Perfil Técnico', function() {
      cy.get('div.v-card .theme--light');
    });

    it('Verifica se há pelo menos um item na tabela - Aba "Assinar" ', function() {
      cy.get('div.v-tabs__div a').contains('Assinar').click();
      cy.get('tbody > :nth-child(1) .text-xs-right').should('not.be.empty');
    });

    it('Verifica se há pelo menos um item na tabela - Aba "Histórico" ', function() {
      cy.get('div.v-tabs__div a').contains('Historico').click();
      cy.get('tbody > :nth-child(1) .text-xs-right').should('not.be.empty');
    });

    it('Click para abrir modal Histórico de Encaminhamentos ', function() {
      let a = cy.get(':nth-child(4) > .v-card > .v-card__text > :nth-child(1) > :nth-child(2) > .v-table__overflow > .v-datatable > tbody > :nth-child(1) > :nth-child(6) > .v-dialog__container > .v-dialog__activator > .v-tooltip > span > .v-btn > .v-btn__content > .v-icon')
      a.click();
    });

    it('Click para fechar modal Histórico de Encaminhamentos ', function() {
      cy.get('.v-dialog__content--active > .v-dialog > .v-card > .v-card__actions > .v-btn > .v-btn__content').click();
    });

    it('Click para Analisar/Visualizar Projeto', function() {
      let a = cy.get(':nth-child(4) > .v-card > .v-card__text > :nth-child(1) > :nth-child(2) > .v-table__overflow > .v-datatable > tbody > :nth-child(1) > :nth-child(6) > .v-btn--router > .v-btn__content > .v-tooltip > span > .v-icon')
      a.click();
    });

    // it('Click para voltar da Analisar projeto -> Tabela de Análise Resultados', function() {
    //   let a = cy.get('i.v-icon .material-icons .theme--light').contains('arrow_back');
    //   a.click();
    // });
    


    
  });