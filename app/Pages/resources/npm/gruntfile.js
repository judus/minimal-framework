module.exports = function(grunt) {

  // measures the time each task takes
  require('time-grunt')(grunt);

  // load grunt config
  require('load-grunt-config')(grunt, {
    config: {
      paths: grunt.file.readJSON('paths.json'),
      humans: grunt.file.readJSON('humans.json'),
      banner: '/*!\n' +
      ' * <%= package.name %>\n' +
      ' * <%= package.url %>\n' +
      ' * Last updated: <%= grunt.template.today("yyyy-mm-dd") %>\n' +
      ' * \n' +
      ' * Made by <%= package.author %>\n' +
      ' * \n' +
      ' * Copyright (c) <%= grunt.template.today("yyyy") %> <%= package.copyright %>\n' +
      ' * License <%= package.license %>\n' +
      ' */'
    }
  });
};
