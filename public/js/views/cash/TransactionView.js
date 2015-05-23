define([
    'jquery',
    'underscore',
    'backbone',
    'views/AbstractTransaction',
    'views/Transaction'
], function($, _, Backbone, AbstractTransaction, SingleTransactionView){

    return AbstractTransaction.extend({

        addAll: function () {
            console.log("Add all");
            this.$el.append('<tr><th colspan="3">Transactions</th></tr>');
            this.collection.forEach(this.addOne, this);
        },

        addOne: function (transaction) {
            console.log("Add one");
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