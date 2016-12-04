module.exports = {
  dist: {
    files: [
		{
			expand: true,
			cwd: '<%= paths.source %>',
			src: ['fonts/**', 'vendor/**/*.js', '**/*.php'],
			dest: '<%= paths.destination %>'
		},
		{
			expand: true,
			cwd: '<%= paths.source %>',
			src: ['audio/**'],
			dest: '<%= paths.destination %>'
		}
	],
  },
  jquery: {
    files: [
      {
        expand: true,
        cwd: 'bower_components/jquery/dist',
        src: ['**/*.*'],
        dest: '<%= paths.destination %>/vendor/jquery'
      }
    ],
  },
  bootstrap: {
    files: [
      {
        expand: true,
        cwd: 'bower_components/bootstrap/dist',
        src: ['**/*.*'],
        dest: '<%= paths.destination %>/vendor/bootstrap'
      }
    ],
  },
};
