let is_not_dev_server = process.env.NODE_ENV === 'production' || process.env.npm_lifecycle_event === 'watch';

module.exports = {
  publicPath: is_not_dev_server
    ? 'public/dist'
    : '',
  filenameHashing: false,
  outputDir: '../public/dist',
  pages: {
    main: {
      entry: 'src/main.js',
      template: 'public/index.html',
      filename: is_not_dev_server
      ? '../../application/layouts/scripts/main.phtml'
      : 'main.html',
    },
    avaliacao_resultados: {
      entry: 'src/modules/avaliacaoResultados/main.js',
      template: 'public/index.html',
      filename: is_not_dev_server
        ? '../../application/layouts/scripts/avaliacao_resultados.phtml'
        : 'avaliacao_resultados.html',
    },
    proposta: {
      entry: 'src/modules/proposta/main.js',
      template: '../../application/layouts/scripts/layout.phtml',
      filename: is_not_dev_server
        ? '../../application/layouts/scripts/proposta.phtml'
        : 'proposta.html',
    },
    projeto: {
      entry: 'src/modules/projeto/main.js',
      template: '../../application/layouts/scripts/layout.phtml',
      filename: is_not_dev_server
        ? '../../application/layouts/scripts/projeto.phtml'
        : 'projeto.html',
    },
    foo: {
      entry: 'src/modules/foo/main.js',
      template: 'public/index.html',
      filename: 'foo.html',
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
};
