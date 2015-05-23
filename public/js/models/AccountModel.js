// Filename: models/project
define([
    'underscore',
    'backbone'
], function(_, Backbone){
    var AccountModel = Backbone.Model.extend({
        urlRoot:"/api/account"
    });

    return AccountModel;
});