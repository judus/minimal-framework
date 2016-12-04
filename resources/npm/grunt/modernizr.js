module.exports = {

    dist: {
        "devFile" : "<%= paths.source %>/vendor/modernizr-2.8.3-respond-1.4.2.min.js",
        "outputFile" : "<%= paths.destination %>/vendor/modernizr-2.8.3-respond-1.4.2.min.js",
        "dest" : "<%= paths.destination %>/vendor/modernizr-2.8.3-respond-1.4.2.min.js",
        "cache": false,
        "extra" : {
          "shiv" : true,
          "printshiv" : true,
          "load" : true,
          "mq" : true,
          "cssclasses" : true
        },
        "options" : [
          "setClasses",
          "addTest",
          "html5printshiv",
          "testProp",
          "fnBind",
          "mq"
        ],
        "excludeTests": [
          "hidden"
        ],
        "parseFiles" : true,
        "crawl" : true,
        "uglify" : true,
        "matchCommunityTests" : true,
    }
};
