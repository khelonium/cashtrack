define([
    'underscore',
    'backbone'
], function(_, Backbone){
    return  Backbone.Model.extend({
        urlRoot:"/api/transaction"
    });

});