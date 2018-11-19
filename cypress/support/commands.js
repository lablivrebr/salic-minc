// Cypress.Commands.add("dismiss", { prevSubject: 'optional'}, (subject, options) => { ... })
//
//
// -- This is will overwrite an existing command --
// Cypress.Commands.overwrite("visit", (originalFn, url, options) => { ... })

Cypress.Commands.add("mudarPerfil", (codGrupo, codOrgao) => { 
    cy.request(`http://localhost/autenticacao/perfil/alterarperfil?codGrupo=${codGrupo}&codOrgao=${codOrgao}`);

    cy.visit('http://localhost/principal');
})
