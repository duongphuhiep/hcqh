System.config({
  "baseURL": "/",
  "transpiler": "traceur",
  "paths": {
    "*": "*.js",
    "github:*": "jspm_packages/github/*.js",
    "npm:*": "jspm_packages/npm/*.js",
    "bower:*": "bower_components/*.js"
  }
});

System.config({
  "map": {
    "jquery": "https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min",
    "riot": "bower:riot/riot", //"npm:riot@2.2.3"
    "tags": "/gen/tags.js"
  }
});