module.exports = {
  all: {
    options: {
      browsers: ['last 4 version', 'ie 8', 'ie 9', 'Android 4', 'iOS 7'/*, 'iOS 6'*/],
      diff: true,
      map: false,
      remove: false
    },
    src: '<%= paths.destination %>/css/main.css'
  }
};
