module.exports = {
  options: {
    aggressiveMerging: false,
    shorthandCompacting: false,
    processImport: false,
    rebase: false,
    keepSpecialComments: 0
  },
  target: {
    files: {
      '<%= paths.destination %>/css/main.min.css': ['<%= paths.destination %>/css/main.css']
    }
  }
};
