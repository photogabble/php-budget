
window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.$ = window.jQuery = require('jquery');

    require('bootstrap-sass');
    require('chart.js');
    require('bootstrap-daterangepicker');
    require('chosen-js');
    window.moment = require('moment/moment');
} catch (e) {}
