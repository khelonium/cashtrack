Cash.Models.Accounts = Backbone.Collection.extend({
    url:"/api/account",

    getNameFor: function(idAccount){
        var wantedAccount =this.filter(function(account){
            return account.get('id') == idAccount;
        });

        if(wantedAccount.length > 0) return wantedAccount[0].get('name');
    }

});
