module.exports = {
	options: {
		separator: ';\n',
		stripBanners: {
			block: false,
			line: false
		}
	},
	dist: {
		src: [
			'<%= paths.source %>/js/main.js'
		],
		dest: '<%= paths.destination %>/js/main.js',
	},
	fallback: {
		src: [
			'<%= paths.source %>/js/fallback.js',
		],
		dest: '<%= paths.destination %>/js/fallback.js',
	},


};
