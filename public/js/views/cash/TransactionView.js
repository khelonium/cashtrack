define([
    'jquery',
    'underscore',
    'backbone',
    'views/AbstractTransaction',
    'views/Transaction',
    'collections/TransactionCollection',
], function($, _, Backbone, AbstractTransaction, SingleTransactionView, TransactionCollection){

    return AbstractTransaction.extend({

        initialize:function() {
            this.collection = new TransactionCollection();
            this.collection.on('reset', this.render, this);
        },

        addAll: function () {
            this.$el.append('<tr><th colspan="3">Transactions</th></tr>');
            this.collection.forEach(this.addOne, this);
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