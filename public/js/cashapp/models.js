
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



cashCode.Models.Transaction  = Backbone.Model.extend({
    urlRoot:"/api/transaction"
});

cashCode.Collections.Transaction = Backbone.Collection.extend({
    model:cashCode.Models.Transaction,
    url:"/api/transaction"
});


cashCode.Models.Buffer  = Backbone.Model.extend({
    urlRoot:"/api/balance",
    idAttribute: "start",

    'buffers' :function() {
        if (!this.attributes.accounts) {
            return [];
        }
        return this.attributes.accounts.filter(function(account) {
                return account.type === 'buffer';
            }
        );
    }
});

cashCode.Collections.Buffers = Backbone.Collection.extend({
    model:cashCode.Models.Buffer,
    url:"/api/balance",
    parse: function(options) {
        return options.accounts;
    }
});
