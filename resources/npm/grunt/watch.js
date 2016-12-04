module.exports = {
  js: {
    files: [
      '<%= paths.source %>/js/**/*.js'
    ],
    tasks: ['js'],
    options: {
    }
  },
  css: {
    files: [
      '<%= paths.source %>/css/**/*.scss',
    ],
    tasks: ['css']
  },
  paths: {
    files: [
      'path.json',
    ],
    tasks: ['paths']
  },
  grunt: {
    files: [
      'grunt/**/*.*'
    ],
    tasks: ['dist']
  }
};
