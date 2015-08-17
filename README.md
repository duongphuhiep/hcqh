# Hop ca Que-Huong website

Work in progress...

Revamping website

* Base on [jquery](http://jquery.com/) / [bootstrap](http://getbootstrap.com/), try to keep the default bootstrap style as much as possible
* Use [riotjs](http://riotjs.com/) to group related html, javascript, css together as component (ref. `app/*.rtg`)
* Use [i18next](http://i18next.com/) for internationalization


## Local installation  
Require: [nodejs](https://nodejs.org/)

```
npm install
bower install
gulp            #build and watch file changes (npm install gulp -g) 
live-server     #open browser, and auto-refresh to view website (npm install live-server -g) 
```

* Website contents is configured in the `content` folder by Cô Chú
* The `backend` folder hosts the future PHP API service


## Notice
* No module loader yet (because the website is still simple)