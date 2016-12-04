module.exports = {
  options: {
    position: 'top',
    banner: '<%= banner %>',
    linebreak: true
  },
  css: {
    files: {
      src: [ '<%= paths.destination %>/css/main.css', '<%= paths.destination %>/css/main.min.css' ]
    }
  },
  js: {
    files: {
      src: [ '<%= paths.destination %>/js/main.js', '<%= paths.destination %>/js/main.min.js' ]
    }
  }
};
