# Overview

The Hop ca Que-Huong website project comprehends 3 applications (2 front-end applications and 1 backend service):
 
 * The "Website" (or Main application) is a Single Web page Application (SPA). 
 * The "Admin" application is also a SPA, it helps to manage website content and configuration.
 * The "Backend" application service provides Restful APIs to access and manipulate the [database](https://github.com/duongphuhiep/hcqh/wiki/Database)

# Framework and library
  
I chose to implement as much complexity as possible on the Front-end (Website & Admin) so that the "Backend" stays as stupid and minimal as possible. Normally, the 2 front-end applications should use the same stack framework / library. However I want to to experience different technique to develop SPA, so the "Website" and "Admin" are based on 2 different frameworks. 
     
**Website application's library stack**
 
 * Base on [jquery](http://jquery.com/) / [bootstrap](http://getbootstrap.com/), try to keep the default bootstrap style as much as possible so that we switch between many available bootstrap themes
 * Try to use [CDN](http://htmlcheats.com/cdn-2/6-reasons-use-cdn/) whenever possible, but to be consider latter 
    * It is debatable, I might choose some reliable CDN for the big scripts and switch other to local. It is easy to migrate anyway 
 * Use [browserify](http://browserify.org/) for modularization and bundling
 * [riotjs](http://riotjs.com/) for templating (ref. [`app/*.tag`](app))
 * [i18next](http://i18next.com/) for internationalization
 * [commonmarkjs](https://github.com/jgm/commonmark.js) to render Markdown data
 * [sinonjs](http://sinonjs.org/docs/#server) to fake ajax request (in order to simulate the backend API)
 * [lodash.debounce](https://lodash.com/docs#debounce)

**Admin application's library stack**

 * [Polymer](https://www.polymer-project.org/1.0/) based application 
 * Use [Google Sign-In](https://developers.google.com/identity/) for authorization
 * Use other polymer tools eg: [Polybuild](https://github.com/PolymerLabs/polybuild) to packaging

**Back-end**
 
 * PHP, no library 
 
[See wiki for more info](https://github.com/duongphuhiep/hcqh/wiki)
