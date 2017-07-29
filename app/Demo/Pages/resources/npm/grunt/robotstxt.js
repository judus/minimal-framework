module.exports = {
  dist: {
    dest: '',
    policy: [
      {
        ua: '*',
        disallow: [''],
        allow: '/'
      },
      // {
      //   sitemap: ['http://example.com/sitemap.xml', 'http://alernate.org/sitemap.xml']
      // },
      // {
      //   crawldelay: 100
      // },
      // {
      //   host: 'www.example.org'
      // }
    ]
  }
};
