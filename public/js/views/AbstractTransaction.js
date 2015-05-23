define([
    'jquery',
    'underscore',
    'backbone',
], function($, _, Backbone){

    return Backbone.View.extend({
        tagName:'table',
        className:'table table-hover',
        activeMonth: NaN,

        initialize:function() {
            this.collection.on('reset', this.render, this);
        },

        setMonth : function (month) {
            this.activeMonth = month;
        },


        render: function() {

            this.$el.html('');
            this.addAll();

        }

    });
});