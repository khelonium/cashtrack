
CashApp.Views.Item = Backbone.View.extend({
    template: _.template('<option value ="<%= id %>"><%= name %></option>'),
    tagName: 'option',
    render:function(){
        this.$el.html(this.template(this.model.attributes));
        return this;
    }
});

CashApp.Views.AccountSelect = Backbone.View.extend({
    initialize:function(){
        this.collection.on('change',this.render,this);
        this.collection.on('sync',this.render,this);
    },
    render:function(){
        _.each(this.collection.models,function( item ){
            this.$el.append(new CashApp.Views.Item({model:item}).render().$el.html() );
        },this);

        return this;
    }
});


CashApp.Views.Transaction = Backbone.View.extend({

    tagName: 'tr',
    template: _.template('<td><%= description %><td><%= amount %></td><td><a href="/#transaction/<%= id %>/edit">Edit</a></td>'),

    initialize : function() {
        this.listenTo(this.model, 'change', this.render);
    },
    render: function() {
        this.$el.html(this.template(this.model.attributes));
    }
});



CashApp.Views.Category = Backbone.View.extend({

    tagName: 'tr',
    template: _.template('<td><span class="glyphicon glyphicon-chevron-right"</span> <%= name %><td><%= amount %></td>'),
    expanded:false,
    expandedView:NaN,

    events: {
        'click span':'toggleTransactions'

    },

    toggleTransactions:function() {
        if (this.expanded) {
            this.expandedView.$el.hide();
            this.expanded = false;
            this.$el.find('span').addClass('glyphicon-chevron-right');
            this.$el.find('span').removeClass('glyphicon-chevron-down');
            return;
        }

        this.expanded = true;


        this.$el.find('span').addClass('glyphicon-chevron-down');
        this.$el.find('span').removeClass('glyphicon-chevron-right');

        if (this.expandedView) {
            this.expandedView.$el.show();
            return;

        }


        var new_el = $("<tr colspan='2'><td><table class='table table-hover'></table></td></tr>");

        this.$el.after(new_el);

        transactions       = new CashApp.Collections.Transaction();
        this.expandedView  = new CashApp.Views.Transactions({collection:transactions, el:new_el});
        console.log(this.model.attributes);
        transactions.fetch({data:{month:this.model.get('month'), accountId:this.model.get('accountId')}, reset:true});

    },

    render: function() {
        this.$el.html(this.template(this.model.attributes));
    }
});


 CashApp.Forms.AddTransaction = Backbone.View.extend({
    events : {
      'submit' : 'save'
    },
    initialize:function(){

        this.accountList = new CashApp.Collections.AccountList();
        this.listenTo(this.model, 'change',this.render);
        this.listenTo(this.model, 'canHide',this.hide);
        this.initModelListeners(this.model);
        CashApp.on('working_month',this.setDate,this);
        this.accountList.fetch();



    },

    setDate : function(month) {
        this.model.set({date: month});
    },
    save : function($e) {


      $e.preventDefault();

      var description  = this.$('textarea[name=description]').val();
      var amount       = this.$('input[name=amount]').val();
      var date         = this.$('input[name=datepicker]').val();
      var idAccount    = this.$('select[name=idAccount]').val();


      this.model.save({description:description, amount:amount, date:date, toAccount:idAccount},{success :function(model,response,options){
          //how to properly do this?
          model.trigger('canHide');

      }});
    },

     switchModel: function(model) {
        this.stopListening(this.model);
        this.initModelListeners(model);
        this.model = model;

        this.render();
     },

     initModelListeners: function(model) {
         this.listenTo(model, 'change',this.render);
         this.listenTo(model, 'canHide',this.hide);
     },

    show: function() {
        this.$el.show();
    },

    hide: function() {
        this.$el.hide();
    },

    render:function(){
        var template = _.template($('#add-transaction-template').html(), this.model.attributes);
        this.$el.html(template);
        this.calendar =  $("#datepicker").datepicker({ dateFormat: 'yy-mm-dd' });
        this.select = new CashApp.Views.AccountSelect({collection:this.accountList,el:$('#idAccount')});

        this.select.render();

        $("#idAccount").val(this.model.get('toAccount'));

    }
});

CashApp.Views.AbstractTransaction = Backbone.View.extend({
    tagName:'table',
    className:'table table-hover',
    activeMonth: NaN,

    initialize:function() {
        this.collection.on('reset', this.render, this);
    },

    setMonth : function (month) {
        this.activeMonth = month;
    },


    render: function() {

        this.$el.html('');
        this.addAll();

    }

});


CashApp.Views.CashView = CashApp.Views.AbstractTransaction.extend({
    mode : "category",
    transactionMode : NaN,
    categoryMode: NaN,
    activeView : NaN,

    initialize : function(options) {
        this.categoryMode       = new CashApp.Views.CategoryList({collection:options.categories, el: this.el});
        this.transactionMode    = new CashApp.Views.Transactions({collection:options.transactions, el: this.el});

        this.activeView = this.categoryMode;

        this.on('view_mode', this.setMode, this);
        this.on('active_month', this.setMonth, this);



    },


    setMonth : function (month) {
        this.activeMonth = month;
        this.categoryMode.setMonth(month);
        this.transactionMode.setMonth(month);
    },



    setMode:function(mode){
        switch (mode) {
            case 'category':
                this.activeView = this.categoryMode;
                break;
            case 'transaction':
                this.activeView = this.transactionMode;
                break;
            default:
                break;
        }

        this.activeView.collection.fetch({data:{month:this.activeMonth},reset:true});
    },

    render : function () {
        this.activeView.render();
    }

});

CashApp.Views.Transactions = CashApp.Views.AbstractTransaction.extend({



    addAll: function() {
        var total = 0;
        this.$el.append('<tr class="warning"><th>Transactions</th><th>'+  total.toFixed(2) + '</th><th>#</th></tr>');
        this.collection.forEach(this.addOne,this);
    },

    addOne : function(transaction) {
        var categoryView = new CashApp.Views.Transaction({model: transaction});
        categoryView.render();
        if (categoryView.model.get('toAccount') == '') {
            categoryView.$el.addClass('danger');

        }
            if (categoryView.model.get('toAccount') == 31) {
            categoryView.$el.addClass('danger');
        }
        this.$el.append(categoryView.el);
    }
});


CashApp.Views.CategoryList = CashApp.Views.AbstractTransaction.extend({


    addAll: function() {
        this.addIncomes();
        this.addExpenses();
    },

    addExpenses:function(){
        var expenses = this.expenses();

        var total =0;

        for (i =0; i< expenses.length; i++ ) {
            total = total +  +expenses[i].get('amount').valueOf();
        }

        var round = new Number(total);

        this.$el.append('<tr class="danger"><th>Expense</th><th>'+  total.toFixed(2) + '</th></tr>');
        expenses.forEach(this.addOne,this);
    },


    addIncomes:function() {
        this.$el.append('<tr><th>Income</th><th>Value</th></tr>');
        this.incomes().forEach(this.addOne,this);

    },
    expenses:function() {
        return this.collection.filter(function(category) {
                return category.get('type') === 'expense';
            }
        );
    },

    incomes:function() {
        //todo refactor and move at init in separate collections
        return this.collection.filter(function(category) {
                return category.get('type') === 'income';
            }
        );
    },
    addOne : function(categoryItem) {
        var categoryView = new CashApp.Views.Category({model: categoryItem});
        categoryView.render();
        this.$el.append(categoryView.el);
    }
});


var NavigationMonthView = Backbone.View.extend({
    tagName:'li',
    template: _.template('<a data-cashmonth="<%= date %>" href="#/cashflow/<%= date %>"><%= name %></a>'),

    events:  {
        'click a':'toggleActive'
    },

    initialize:function() {
        CashApp.on("active_month",this.activateMonth, this);
    },
    activateMonth: function(month) {
        var date = new Date(month);

        month = date.getMonth() + 1;
        if(month <= 9) {
            month = '0'+month;
        }
        var month_start = date.getFullYear() + '-' +  month + '-01';
        console.log(month_start);
        if (this.model.get('date') === month_start) {
            this.toggleActive();
        }

    },


    toggleActive:function(){
        this.$el.parent().find('li.active').removeClass('active');
        this.$el.toggleClass('active');
    },
    render:function(){
        this.$el.html(this.template(this.model.attributes));
    }
});

CashApp.Views.NavigationYearView = Backbone.View.extend({

    render : function() {
        this.collection.forEach(this.addMonth,this);
    },

    addMonth: function(month){
        var navigationMonthView = new NavigationMonthView({model:month});
        navigationMonthView.render();
        this.$el.append(navigationMonthView.el);
    }

});





CashApp.Views.Buffer = Backbone.View.extend({

    tagName : 'tr',
    template : _.template('<th data-buffer-id=<%= idAccount%>> <%= name %> <%=balance %></th>'),
    render : function() {
        console.log(this.model.attributes);
        this.$el.html(this.template(this.model.attributes));
        return this;
    }


});



CashApp.Views.Buffers = Backbone.View.extend({

   'tagName'    : 'table',
    className   :'table table-hover',
    activeMonth : NaN,

    initialize:function() {
        this.collection.on('change',this.render,this);
        this.collection.on('sync',this.render,this);
        this.on('active_month', this.setMonth, this);


    },

    setMonth : function (month) {
        this.activeMonth = month;
        this.collection.fetch({data:{month:month},reset:true});
    },


    render : function() {
        this.$el.html('');
        this.collection.forEach(this.addBuffer,this);

    },

    addBuffer : function(model)
    {
        var view = new CashApp.Views.Buffer({model:model});
        this.$el.append(view.render().el);
    }


});

