define([
    'jquery',
    'underscore',
    'backbone',
    'd3',
    'views/report/BaseBarChart',


], function ($, _, Backbone, d3, BaseBarChart) {

    return BaseBarChart.extend({

        lastWeekReport: function () {
            var today = new Date();
            this.renderFrom( '/api/breakdown/week/' + today.getFullYear() + '/' +  (today.getWeek() -1));

        },

        thisMonthReport: function () {
            var today = new Date();
            this.monthReport(today.getFullYear(), today.getMonth() + 1);
        },


        monthReport : function(year, month) {
            this.renderFrom("/api/breakdown/month/" + year  + "/" +  month);
        },

        thisWeekReport: function () {
            var today = new Date();
            this.weekReport(today.getFullYear(), today.getWeek());
        },

        weekReport : function (year, week) {
            this.renderFrom( '/api/breakdown/week/' + year + '/' +  week);
        },

        yearReport : function (year) {
            this.renderFrom( '/api/breakdown/year/' + year );
        }

    });

});
