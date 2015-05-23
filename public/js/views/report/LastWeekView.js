define([
    'jquery',
    'underscore',
    'backbone',

], function ($, _, Backbone) {

    return Backbone.View.extend({


        initialize:function() {
          this.$el.html('');
        },

        render: function () {
            this.$el.html('I am the report');
        }
    });

});
