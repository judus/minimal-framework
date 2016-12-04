module.exports = {
  all: {
    options: {
      config: 'grunt/configs/.csscomb.json'
    },
    files: {
      '<%= paths.source %>/css/main.css': ['<%= paths.source %>/css/main.css'],
    }
  }
};
