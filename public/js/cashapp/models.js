
cashCode.Models.Category = Backbone.Model.extend({
    urlRoot:"/api/cashflow"
});


cashCode.Collections.CategoryList = Backbone.Collection.extend({
    model:cashCode.Models.Category,
    url:"/api/cashflow",
    initialize:function() {
        this.on('active_month', this.fetchMonth, this)
    },
    fetchMonth:function(month) {
        this.fetch({data:{month:month},reset:true});
    }

});


cashCode.Collections.NavigationYear = Backbone.Collection.extend();

cashCode.Models.Account = Backbone.Model.extend({
    urlRoot:"/api/account"
});

cashCode.Collections.AccountList = Backbone.Collection.extend({
    model:cashCode.Models.Account,
    url:"/api/account"
});


cashCode.Models.Transaction  = Backbone.Model.extend({
    urlRoot:"/api/transaction"
});

cashCode.Collections.Transaction = Backbone.Collection.extend({
    model:cashCode.Models.Transaction,
    url:"/api/transaction"
});


cashCode.Models.Buffer  = Backbone.Model.extend({
    urlRoot:"/api/balance"
});

cashCode.Collections.Buffers = Backbone.Collection.extend({
    model:cashCode.Models.Buffer,
    url:"/api/balance"
});
