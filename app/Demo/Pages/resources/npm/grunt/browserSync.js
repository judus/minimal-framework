module.exports = {
  options: {
    watchTask: true,
    notify: false,
    proxy: "",
    port: 3000,
    open: false
  },
  files: {
    bsFiles: {
      src : [
        '<%= paths.source %>/css/**/*.css',
        '<%= paths.source %>/js/**/*.js',
        '/**/*.{html,php}'
      ]
    }
  }

};
