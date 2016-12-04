module.exports = {
  options: {
    mangle: {
      except: ['jQuery, u']
    },
    compress: {
      drop_console: true
    },
    preserveComments: false,
    sourceMap: true
  },
  dist: {
    files: {
      '<%= paths.destination %>/js/main.min.js': ['<%= paths.source %>/js/main.js']
    }
  },
  fallback: {
    files: {
      '<%= paths.destination %>/js/fallback.min.js': ['<%= paths.source %>/js/fallback.js']
    }
  }
};
