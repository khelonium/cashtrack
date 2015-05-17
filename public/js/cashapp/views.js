
cashCode.Views.Item = Backbone.View.extend({
    template: _.template('<option value ="<%= id %>"><%= name %></option>'),
    tagName: 'option',
    render:function(){
        this.$el.html(this.template(this.model.attributes));
        return this;
    }
});

cashCode.Views.AccountSelect = Backbone.View.extend({
    initialize:function(){
        this.collection.on('change',this.render,this);
        this.collection.on('sync',this.render,this);
    },
    render:function(){
        _.each(this.collection.models,function( item ){
            this.$el.append(new cashCode.Views.Item({model:item}).render().$el.html() );
        },this);

        return this;
    }
});


cashCode.Views.Transaction = Backbone.View.extend({

    tagName: 'tr',
    template: _.template('<td><%= description %><td class="text-right"><%= amount %></td><td class="text-right"><a href="/#transaction/<%= id %>/edit">Edit</a></td>'),

    initialize : function() {
        this.listenTo(this.model, 'change', this.render);
    },
    render: function() {
        this.$el.html(this.template(this.model.attributes));
    }
});



cashCode.Views.Category = Backbone.View.extend({

    tagName: 'tr',
    template: _.template('<td><span class="glyphicon glyphicon-chevron-right"</span> <%= name %><td class="text-right"><%= amount %></td>'),
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


        var new_el = $("<tr><td  colspan='2'><table class='transaction-container table '></table></td></tr>");

        this.$el.after(new_el);




        var transactions       = new cashCode.Collections.Transaction();
        this.expandedView  = new cashCode.Views.Transactions({collection:transactions, el:new_el.find('.transaction-container')});
        transactions.fetch({data:{month:this.model.get('month'), accountId:this.model.get('accountId')}, reset:true});

    },

    render: function() {
        this.$el.html(this.template(this.model.attributes));
    }
});


 cashCode.Forms.AddTransaction = Backbone.View.extend({
    events : {
      'submit' : 'save'
    },

    initialize:function(){

        this.accountList = new  Cash.Models.Accounts();
        this.listenTo(this.model, 'change',this.render);
        this.listenTo(this.model, 'canHide',this.hide);
        this.initModelListeners(this.model);
        this.on('working_month',this.setDate,this);
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
        this.select = new cashCode.Views.AccountSelect({collection:this.accountList,el:$('#idAccount')});

        this.select.render();

        $("#idAccount").val(this.model.get('toAccount'));

    }
});

cashCode.Views.AbstractTransaction = Backbone.View.extend({
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


cashCode.Views.CashView = cashCode.Views.AbstractTransaction.extend({
    mode : "category",
    transactionMode : NaN,
    categoryMode: NaN,
    activeView : NaN,

    initialize : function(options) {
        this.categoryMode       = new cashCode.Views.CategoryList({collection:options.categories, el: this.el});
        this.transactionMode    = new cashCode.Views.Transactions({collection:options.transactions, el: this.el});

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

cashCode.Views.Transactions = cashCode.Views.AbstractTransaction.extend({



    addAll: function() {
        this.$el.append('<tr><th colspan="3">Transactions</th></tr>');
        this.collection.forEach(this.addOne,this);
    },

    addOne : function(transaction) {
        var categoryView = new cashCode.Views.Transaction({model: transaction});
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


cashCode.Views.CategoryList = cashCode.Views.AbstractTransaction.extend({


    addAll: function() {
        this.addIncomes();
        this.addExpenses();
    },

    addExpenses:function(){
        var expenses = this.expenses();

        var total =0;

        for (var i =0; i< expenses.length; i++ ) {
            total = total +  +expenses[i].get('amount').valueOf();
        }

        var round = new Number(total);

        this.$el.append('<tr class="danger" class="text-right"><th>Expense</th><th class="text-right">'+  total.toFixed(2) + '</th></tr>');
        expenses.forEach(this.addOne,this);
    },


    addIncomes:function() {
        this.$el.append('<tr><th>Income</th><th class="text-right">Value</th></tr>');
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
        var categoryView = new cashCode.Views.Category({model: categoryItem});
        categoryView.render();
        this.$el.append(categoryView.el);
    }
});





cashCode.Views.Buffer = Backbone.View.extend({

    tagName : 'tr',
    template : _.template('<th data-buffer-id=<%= idAccount%>> <%= name %> <%=balance %></th>'),
    render : function() {
        this.$el.html(this.template(this.model.attributes));
        return this;
    }


});


cashCode.Views.BufferList = Backbone.View.extend({

   'tagName'    : 'table',
    className   :'table table-hover',



    render : function() {
        this.$el.html('');


        accounts = this.model.buffers();
        accounts.forEach(this.addBuffer,this);
        return this;

    },

    addBuffer : function(model)
    {
        var bModel = new Backbone.Model(model);
        var view = new cashCode.Views.Buffer({model:bModel});

        this.$el.append(view.render().el);


    }
});

cashCode.Views.MonthOperation = Backbone.View.extend({

    activeMonth : NaN,
    bufferList : NaN,
    addTransaction:NaN,
    endMonth:NaN,

    events: {
        'click .end-month button':  'endOfMonthAction'
    },

    setMonth : function (month) {
        this.model.set({start:month});
        this.model.fetch();
    },


    endOfMonthAction: function() {

        var callback = function(model, xhr, options) {
            this.setError(xhr.responseJSON.content);
        };

        var call = callback.bind(this);

        this.model.save({month:this.activeMonth} , {error:call} );
    },

    initialize:function() {
        this.model.on('change',this.render,this);
        this.model.on('sync',this.render,this);
        this.on('active_month', this.setMonth, this);

        this.bufferList = new cashCode.Views.BufferList({model:this.model, el:this.$el.find('#bufferList')});
        this.addTransaction = this.$el.find('#addTransactionLink');
        this.endMonth       = this.$el.find('.end-month');

    },

    setError: function(message) {

        $error = $("<div class='text-danger'>"+ message + "</div>");
        this.$el.append($error);
        $error.fadeOut(5000, function() {$(this).remove()});
    },
    render : function() {

        this.$el.html('');

        if (this.model.get('status') === 'closed') {
            this.$el.html('-Closed-');

        }

        if (this.model.get('status') === 'open') {
            this.$el.append(this.addTransaction);
        }

        this.$el.append(this.bufferList.render().el);

        if (this.model.get('status') === 'open') {
            this.$el.append(this.endMonth);
        }
    }


});

