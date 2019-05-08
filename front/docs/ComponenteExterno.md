# Como utilizar um componente Vue externo no projeto

Utilizando como base um perfil **Secretario** acessando a funcionalidade **http://{server}/avaliacao-resultados/#/laudo**
deve ser tomado como referência os itens a seguir:

- AvaliacaoResultadosController > indexAction > index.phtml > encontrar esse cara:
- `<script type="text/javascript" 
    charset="utf-8" 
    src="/public/dist/js/avaliacao_resultados.js<?= $gitTag; ?>"></script>`
- O item **avaliacao_resultados** referencia o módulo utilizado pelo front-end.

- Para acessar esse módulo deve ser acessado o arquivo **webpack.base.conf.js** do projeto.
O arquivo está localizado em **...culturagovbr/salic-minc/front/build/webpack.base.conf.js**.

- Para o mesmo nome definido acima como **avaliacao_resultados** deve existir um entrypoint especificando
a localização do módulo no arquivo **webpack.base.conf.js**.

```
entry: {
        main: './src/main.js',
        foo: './src/modules/foo/main.js',
   >>>> avaliacao_resultados: './src/modules/avaliacaoResultados/main.js', <<<<
        proposta: './src/modules/proposta/main.js',
        projeto: './src/modules/projeto/main.js',
        autenticacao: './src/modules/autenticacao/main.js',
    },
```

- Dentro do arquivo **main.js** do módulo, o componente externo deve ser importado e associado a instância Vue.

Exemplo:
```
    import CommunicationWebapp from '@vinnyfs89/communication-webapp';
        
    import {
        router,
        store,
    } from './config';
    
    Vue.use(CommunicationWebapp, { store });

```

- No arquivo ".vue" basta utilizar o componente.

Exemplo da utilização do componente externo **communication-service-notificacao-badge** 
 dentro do arquivo **Index.vue** que faz parte do módulo **avaliacao_resultados**.

```
    <template>
        <div id="app">
            <v-app :dark="isModoNoturno">
                <communication-service-notificacao-badge />
                ...
```
