"use strict";

/**
 * the default namespace __ns__ is 'translation' so it fetch for
 * vi-translation.json, en-US-translation.json...
 */
var option = { resGetPath: 'content/locales/__lng__-__ns__.json' };
i18n.init(option, function(err, t) {
    $(document).i18n();
});

riot.mount('*');


