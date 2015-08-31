"use strict";

(function () {
    //for test only
    require("../backend_mock/fake-backend");

    var riot = require("riot");
    require('./route');
    require("../gen/tags");
    riot.mount('*');
}());