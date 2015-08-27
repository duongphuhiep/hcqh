var nock = require('nock');

var server = nock('http://127.0.0.1:8080/backend')
                .get('blog.php')
                //.replyWithFile(200, __dirname + '/backend/blogpage2.json');
				.replyWithFile(200, __dirname + '/blogpage2.json');

console.log(server);

module.exports = server;