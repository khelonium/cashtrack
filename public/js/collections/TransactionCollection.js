define([
    'jquery',
    'underscore',
    'backbone',
    'models/TransactionModel'
], function($, _, Backbone, TransactionModel){


    return Backbone.Collection.extend({
        model:TransactionModel,
        url:"/api/transaction",
        fetchMonth:function(month) {
            this.fetch({data:{month:month},reset:true});
        }

    });

});