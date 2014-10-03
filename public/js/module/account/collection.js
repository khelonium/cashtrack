Cash.Models.Account = Backbone.Model.extend({
    urlRoot:"/api/account"
});

Cash.Models.Accounts = Backbone.Collection.extend({
    url:"/api/account",
    model: Cash.Models.Account,

    getNameFor: function(idAccount){
        var wantedAccount =this.filter(function(account){
            return account.get('id') == idAccount;
        });

        return wantedAccount[0].get('name');
    }

});
