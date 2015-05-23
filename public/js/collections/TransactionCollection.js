define([
    'jquery',
    'underscore',
    'backbone',
    'models/TransactionModel'
], function($, _, Backbone, TransactionModel){


    return Backbone.Collection.extend({
        model:TransactionModel,
        url:"/api/transaction"
    });

});