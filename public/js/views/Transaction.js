define([
    'jquery',
    'underscore',
    'backbone',

], function ($, _, Backbone) {

    return Backbone.View.extend({

        tagName: 'tr',
        template: _.template('<td><%= description %><td class="text-right"><%= amount %></td><td class="text-right"><a href="/#transaction/<%= id %>/edit">Edit</a></td>'),

        initialize: function () {
            this.listenTo(this.model, 'change', this.render);
        },
        render: function () {
            this.$el.html(this.template(this.model.attributes));
        }
    });

});
