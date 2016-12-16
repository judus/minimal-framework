module.exports = {
	multiple: {
		options: {
			removeComments: true,
			collapseWhitespace: true
		},
		files: [{
			expand: true,
			cwd: 'dist/',
			src: ['*.html','views/*.html'],
			dest: 'dist/'
		}]
	}
};
