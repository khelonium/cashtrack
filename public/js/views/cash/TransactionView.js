define([
    'jquery',
    'underscore',
    'backbone',
    'views/AbstractTransaction',
    'views/Transaction',
    'collections/TransactionCollection',
    'd3'
], function ($, _, Backbone, AbstractTransaction, SingleTransactionView, TransactionCollection, d3) {

    return AbstractTransaction.extend({

        initialize: function () {
            this.collection = new TransactionCollection();
            this.collection.on('reset', this.render, this);
        },

        addAll: function () {
            this.$el.append('<tr><th colspan="3">Transactions</th></tr>');

            var groupped = d3.nest().key(function (d) {
                var day = new Date(d.attributes.date).getDate();
                return (day - day % 7) / 7
            }).entries(this.collection.models);

            groupped.forEach(this.addGroup, this);
        },

        addGroup: function (group) {

            var sum = d3.sum(group.values, function (d) {
                return d.get('amount')
            });

            this.$el.append('<tr class="active"><th colspan="1"> Week ' + (+group.key + 1) + '</th><th class="text-right">' + sum + '</th><th></th></tr>');

            group.values.forEach(this.addOne, this);
        },

        addOne: function (transaction) {
            var categoryView = new SingleTransactionView({model: transaction});
            categoryView.render();
            if (categoryView.model.get('toAccount') == '') {
                categoryView.$el.addClass('danger');

            }
            if (categoryView.model.get('toAccount') == 31) {
                categoryView.$el.addClass('danger');
            }
            this.$el.append(categoryView.el);
        }
    });


});