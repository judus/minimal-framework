module.exports = {

  dist: {
    files: [{
      expand: true,
      cwd: '<%= paths.source %>/img/',
      src: ["**/*.{png,jpg,gif,svg}"],
      dest: '<%= paths.destination %>/img/'
    }]
  }


};
