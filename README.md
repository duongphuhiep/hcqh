# (Last) News

The Website <hopcaquehuong.org> had been closed on Feb 2024. A backup of the published website (and all the content) is available in the [_published](_published) folder.

**For Windows Users**: You won't be able to `git clone` this repository to Windows, because some files name in the `content/` folders contains special characters. Windows users could use [WSL](https://learn.microsoft.com/en-us/windows/wsl/install) to works with this repository.

You can also download the repository as a zip file (but again, Windows users might not be able to unzip the files on Windows)

![download zip](https://github.com/duongphuhiep/hcqh/assets/1638594/30a89892-bd98-4eb1-a586-9e84d488891d)

---

# Overview

Goto [wiki](https://github.com/duongphuhiep/hcqh/wiki) for detail and updated information.

The Hop ca Que-Huong website project comprehends 3 applications (2 front-end applications and 1 backend service):
 
 * The ["Website" (or Main application)](http://hopcaquehuong.org/) is a Single Web page Application (SPA). 
 * The ["Admin" application](http://hopcaquehuong.org/admin/) is also a SPA, it helps to manage website content and configuration.
 * The "Backend" application service provides Restful APIs to access and manipulate the [database](https://github.com/duongphuhiep/hcqh/wiki/Database)

# Run "Website" on localhost

Required [nodejs](https://nodejs.org/en/)

After `git clone`. Run the following commands:

    npm install -g bower         # install package manager globally
    npm install -g gulp          # install build tools globally
    npm install -g live-server   # install a server http globally
    
    cd hcqh                      # Go to the project folder
    gulp                         # build the website
    live-server                  # run a http server, automatically open Chrome at localhost:8080

You can play with the website. It is just a quick start. [Read more infos on Wiki](https://github.com/duongphuhiep/hcqh/wiki/Local-installation)

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
 * Use other polymer tools e.g. [Polybuild](https://github.com/PolymerLabs/polybuild) for packaging

**Back-end**
 
 * PHP, no library 
 
[Read more infos on Wiki](https://github.com/duongphuhiep/hcqh/wiki)
