define([
    'jquery',
    'underscore',
    'backbone',
], function($, _, Backbone){

    return Backbone.View.extend({
        tagName:'table',
        className:'table table-hover',
        activeMonth: NaN,


        setMonth : function (month) {
            this.activeMonth = month;
        },


        render: function() {

            this.$el.html('');
            this.addAll();

        }

    });
});