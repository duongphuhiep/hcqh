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

## Notice
* No module loader yet (because the website is still simple)