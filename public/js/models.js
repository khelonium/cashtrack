
CashApp.Models.Category = Backbone.Model.extend({
    urlRoot:"/api/cashflow"
});


CashApp.Collections.CategoryList = Backbone.Collection.extend({
    model:CashApp.Models.Category,
    url:"/api/cashflow",
    initialize:function() {
        CashApp.on('active_month', this.fetchMonth, this)
    },
    fetchMonth:function(month) {
        this.fetch({data:{month:month},reset:true});
    }

});


CashApp.Collections.NavigationYear = Backbone.Collection.extend();

CashApp.Models.Account = Backbone.Model.extend({
    urlRoot:"/api/account"
});

CashApp.Collections.AccountList = Backbone.Collection.extend({
    model:CashApp.Models.Account,
    url:"/api/account"
});


CashApp.Models.Transaction  = Backbone.Model.extend({
    urlRoot:"/api/transaction"
});

CashApp.Collections.Transaction = Backbone.Collection.extend({
    model:CashApp.Models.Transaction,
    url:"/api/transaction"
});


CashApp.Models.Buffer  = Backbone.Model.extend({
    urlRoot:"/api/balance"
});

CashApp.Collections.Buffers = Backbone.Collection.extend({
    model:CashApp.Models.Buffer,
    url:"/api/balance"
});
