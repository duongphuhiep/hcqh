# Hop ca Que-Huong website

Work in progress...

The website tends to be a Single Page application

* Front-end
 * Base on [jquery](http://jquery.com/) / [bootstrap](http://getbootstrap.com/), try to keep the default bootstrap style as much as possible so that we switch between many available bootstrap themes
 * Try to use [CDN](http://htmlcheats.com/cdn-2/6-reasons-use-cdn/) whenever possible, but to be consider latter 
    * It is debatable, I might choose some reliable CDN for the big scripts and switch other to local. It is easy to migrate anyway 
 * Use [browserify](http://browserify.org/) for modularization and bundling
 * [riotjs](http://riotjs.com/) for templating (ref. [`app/*.tag`](app))
 * [i18next](http://i18next.com/) for internationalization
 * [commonmarkjs](https://github.com/jgm/commonmark.js) to render Markdown data
 * [sinonjs](http://sinonjs.org/docs/#server) to fake ajax request (in order to simulate the backend API)
 * [lodash.debounce](https://lodash.com/docs#debounce)
 
* Back-end - provide API to access to database
 * PHP 
 * the database is files-based in the `/content` folder, and will be manipulated by Cô Chú 
 
## Local installation  
Require: [nodejs](https://nodejs.org/)

```
npm install
bower install
gulp            #build dist/* and open browser which auto-refresh for each changes in the dist/* 
gulp watch      ##listen for files change to rebuilt the dist/* 
```
###Installation for Ubuntu

Loading project from github

```
$ git clone https://github.com/duongphuhiep/hcqh.git
```

Installation backages for nodejs

```
$ sudo apt-get install npm
$ sudo npm install -g bower
$ sudo npm install -g gulp
```

Installation backages for project hcqh

```
$ sudo npm install -g live-server
$ cd hcqh
$ sudo npm install
$ sudo bower install
$ sudo bower install i18next --allow-root
$ gulp bundle
```

* Website contents is configured in the `content` folder by Cô Chú
* The `backend` folder hosts the future PHP API service

## Gulp task
`gulp watch` command will watch file changes during the developement, foreach changes:
* it will compile riot tags in `/app` to `/gen`
  * you can do it manually with `gulp bundle:tag`
* then use `browserify` discovers from `app/main.js` and bundle all applicatif modules to `dist/main.js`
* then minify it `/dist/main.min.js`
  * you can manually compile `/dist/main*.js` with `gulp bundle`
* then `live-server` will see changes in `/dist` and ask to browser to refresh the local web page `localhost:8080`
