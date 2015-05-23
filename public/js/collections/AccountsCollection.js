define([
    'jquery',
    'underscore',
    'backbone',
    'models/AccountModel'
], function($, _, Backbone, AccountModel){
    var AccountsCollection =Backbone.Collection.extend({
        url:"/api/account",
        model: AccountModel,

        getNameFor: function(idAccount){
            var wantedAccount =this.filter(function(account){
                return account.get('id') == idAccount;
            });

            return wantedAccount[0].get('name');
        }

    });


    return AccountsCollection;
});