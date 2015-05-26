define([
    'jquery',
    'underscore',
    'backbone',
    'd3',
    'views/report/BaseBarChart'


], function ($, _, Backbone, d3, BaseBarChart) {

    return BaseBarChart.extend({

        lastWeekReport: function () {
            var today = new Date();
            this.renderFrom( '/api/breakdown/week/' + today.getFullYear() + '/' +  (today.getWeek() -1));

        },

        thisMonthReport: function () {
            var today = new Date();
            var url = "/api/breakdown/month/" + today.getFullYear()  + "/" + (today.getMonth() + 1);
            this.renderFrom(url);
        },


        thisWeekReport: function () {
            var today = new Date();
            this.renderFrom( '/api/breakdown/week/' + today.getFullYear() + '/' +  (today.getWeek() -1));
        }


    });

});
