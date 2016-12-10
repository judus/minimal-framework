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
				src: ['**/jquery.min.js'],
				dest: '<%= paths.destination %>/vendor/jquery'
			}
		],
	},
	bootstrap: {
		files: [
			{
				expand: true,
				cwd: 'bower_components/bootstrap/dist',
				src: ['**/bootstrap.min.js'],
				dest: '<%= paths.destination %>/vendor/bootstrap'
			},
			{
				expand: true,
				cwd: 'bower_components/bootstrap/dist',
				src: ['**/bootstrap.min.css'],
				dest: '<%= paths.destination %>/vendor/bootstrap'
			}
		],
	},
	theter: {
		files: [
			{
				expand: true,
				cwd: 'bower_components/tether/dist',
				src: ['**/tether.min.js'],
				dest: '<%= paths.destination %>/vendor/tether'
			},
		],
	},
	theter: {
		files: [
			{
				expand: true,
				cwd: 'bower_components/fastclick',
				src: ['**/fastclick.js'],
				dest: '<%= paths.destination %>/vendor/fastclick'
			},
		],
	},
};
