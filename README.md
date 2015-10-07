# Hop ca Que-Huong website

Work in progress...

The website tends to be a Single Page application

## Front-end
 * Base on [jquery](http://jquery.com/) / [bootstrap](http://getbootstrap.com/), try to keep the default bootstrap style as much as possible so that we switch between many available bootstrap themes
 * Try to use [CDN](http://htmlcheats.com/cdn-2/6-reasons-use-cdn/) whenever possible, but to be consider latter 
    * It is debatable, I might choose some reliable CDN for the big scripts and switch other to local. It is easy to migrate anyway 
 * Use [browserify](http://browserify.org/) for modularization and bundling
 * [riotjs](http://riotjs.com/) for templating (ref. [`app/*.tag`](app))
 * [i18next](http://i18next.com/) for internationalization
 * [commonmarkjs](https://github.com/jgm/commonmark.js) to render Markdown data
 * [sinonjs](http://sinonjs.org/docs/#server) to fake ajax request (in order to simulate the backend API)
 * [lodash.debounce](https://lodash.com/docs#debounce)

## Front-end administration application
 * [Polymer](https://www.polymer-project.org/1.0/) based application 
 * Use [browserify](http://browserify.org/) for modularization and bundling
 * [sinonjs](http://sinonjs.org/docs/#server) to fake ajax request (in order to simulate the backend API)

## Back-end - provide API to access to database
 * PHP 
 * the database is files-based in the `/content` folder, and will be manipulated by Cô Chú 
 
[See wiki for more info](https://github.com/duongphuhiep/hcqh/wiki)
