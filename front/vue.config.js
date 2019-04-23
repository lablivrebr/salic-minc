const IS_NOT_DEV_SERVER = process.env.NODE_ENV === 'production' || process.env.npm_lifecycle_event === 'watch';
const PATH_LAYOUT = '../../application/layouts/scripts/';
const TEMPLATE_IN_HTML = 'public/template_legado.html';
const TEMPLATE_ZEND = '../application/layouts/scripts/layout.phtml';

module.exports = {
  publicPath: IS_NOT_DEV_SERVER
    ? 'public/dist'
    : '',
  runtimeCompiler: true, //remover esta opcao apos migrar os templates de proposta
  filenameHashing: false,
  outputDir: '../public/dist',
  pages: {
    main: {
      entry: 'src/main.js',
      template: 'public/index.html',
      filename: IS_NOT_DEV_SERVER
      ? PATH_LAYOUT + '/main.phtml'
      : 'main.html',
    },
    avaliacao_resultados: {
      entry: 'src/modules/avaliacaoResultados/main.js',
      template: 'public/index.html',
      filename: IS_NOT_DEV_SERVER
        ? PATH_LAYOUT + '/avaliacao_resultados.phtml'
        : 'avaliacao_resultados.html',
    },
    proposta: {
      entry: 'src/modules/proposta/main.js',
      template: TEMPLATE_IN_HTML,
      filename: IS_NOT_DEV_SERVER
        ? PATH_LAYOUT + '/proposta.phtml'
        : 'proposta.html',
    },
    projeto: {
      entry: 'src/modules/projeto/main.js',
      template: TEMPLATE_ZEND,
      filename: IS_NOT_DEV_SERVER
        ? PATH_LAYOUT + '/projeto.phtml'
        : 'projeto.html',
    },
    foo: {
      entry: 'src/modules/foo/main.js',
      template: 'public/index.html',
      filename: IS_NOT_DEV_SERVER
        ? PATH_LAYOUT + '/foo.phtml'
        : 'foo.html',
    },
  },
  devServer: {
    proxy: 'http://localhost:80',
    historyApiFallback: {
      rewrites: [
        { from: /\/avaliacao-resultados/, to: '/avaliacao_resultados.html' },
        { from: /\/proposta/, to: '/proposta.html' },
        { from: /\/projeto/, to: '/projeto.html' },
        { from: /\/foo/, to: '/foo.html' },
      ]
    }
  },
  // chainWebpack: (config) => {
  //   config
  //     .output
  //     .filename('js/[name].js');
  // },
};
