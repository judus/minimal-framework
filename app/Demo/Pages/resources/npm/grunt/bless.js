module.exports = {
  css: {
    options: {
      // cacheBuster: false,
      // compress: true,
      force: true,
    },
    files: {
      '<%= paths.destination %>/css/main.min.css': '<%= paths.destination %>/css/main.min.css'
    }
  }
};
