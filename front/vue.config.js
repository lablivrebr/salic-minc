module.exports = {
  filenameHashing: false,
  outputDir: '../public/dist',
  pages: {
    main: {
      entry: './src/main.js',
    },
    foo: {
      entry: './src/modules/foo/main.js',
    },
    avaliacao_resultados: {
      entry: './src/modules/avaliacaoResultados/main.js',
    },
    proposta: {
      entry: './src/modules/proposta/main.js',
    },
    projeto: {
      entry: './src/modules/projeto/main.js',
    },
  },
  // chainWebpack: config => {
  //   const options = module.exports
  //   const pages = options.pages
  //   const pageKeys = Object.keys(pages)
  //
  //   // Long-term caching
  //
  //   const IS_VENDOR = /[\\/]node_modules[\\/]/
  //
  //   config.optimization
  //     .splitChunks({
  //       cacheGroups: {
  //         commons: {
  //           chunks: "initial",
  //           name: "manifest",
  //           minChunks: 2,
  //           maxInitialRequests: 5, // The default limit is too small to showcase the effect
  //           minSize: 0 // This is example is too small to create commons chunks
  //         },
  //         vendor: {
  //           test: IS_VENDOR,
  //           chunks: "all",
  //           name: "vendor",
  //           priority: 10,
  //           enforce: true
  //         }
  //       },
  //     })
  // }
};
