module.exports = {
	generate: {
		options: {
			exclude: [""],
			verbose: false,
			timestamp: true,
			headcomment: " <%= package.name %> ",
		},
		src: [
			"<%= paths.destination %>/js/**/*.*",
			"<%= paths.destination %>/css/**/*.*",
			"<%= paths.destination %>/img/**/*.*",
			"<%= paths.destination %>/fonts/**/*.*",
			"*.{html,txt,ico,png}"
		],
		dest: "manifest.appcache"
	}
};
