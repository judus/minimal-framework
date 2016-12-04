module.exports = {
	options: {
		jshintrc: 'grunt/configs/.jshintrc',
		force: true,
		ignores: [
			'<%= paths.source %>/vendor/**/*.js',
			'<%= paths.source %>/vendor/**/*.js',
			'<%= paths.destination %>/vendor/**/*.js'
		]
	},
	all: ['<%= paths.source %>/js/**/*.js']
};
