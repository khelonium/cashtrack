define([
    'jquery',
    'underscore',
    'backbone',
    'd3',
    'views/report/MonthBarChart'


], function ($, _, Backbone, d3, MonthBarChart) {

    return MonthBarChart.extend({


        breakdownAPI : '/report/week/',
        overviewAPI  : "/api/overview/time/week/"

    });

});
