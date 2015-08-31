# Hop ca Que-Huong website

Work in progress...

Revamping website

* Base on [jquery](http://jquery.com/) / [bootstrap](http://getbootstrap.com/), try to keep the default bootstrap style as much as possible
* Use [riotjs](http://riotjs.com/) to group related html, javascript, css together as component (ref. `app/*.rtg`)
* Use [i18next](http://i18next.com/) for internationalization
* Use [browserify](http://browserify.org/) for modularization and bundling

## Local installation  
Require: [nodejs](https://nodejs.org/)

```
npm install
bower install
gulp            #build dist/* 
gulp watch      ##open browser, listen for files change, rebuilt and auto-refresh the browser 
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
