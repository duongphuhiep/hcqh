"use strict";

(function () {
    //for test only
    require("../backend_mock/fake-backend");

    var riot = require("riot");
    require("../gen/tags");
    riot.mount('*');
}());