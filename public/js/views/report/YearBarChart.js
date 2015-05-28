define([
    'jquery',
    'underscore',
    'backbone',
    'd3',
    'views/report/MonthBarChart'


], function ($, _, Backbone, d3, MonthBarChart) {

    return MonthBarChart.extend({

        breakdownAPI : '/report/year/',
        overviewAPI : "/api/overview/time/year/",

        getBreakdownUrl : function( year) {
            return this.breakdownAPI;
        }
    });

});
