
CashApp.CashRouter  =  Backbone.Router.extend({
    routes: {
        "": "index",
        "cashflow/:id"         : "cashflow" ,
        'addTransaction'       : 'showAddTransaction' ,
//        'transactionView'      : 'showTransactions',
//        'accountView'          : 'showAccounts',
        'transaction/:id/edit' : 'editTransaction'

    },
    initialize: function(){


        var currentYear =[
            {name:"Ianuarie", date:"2013-01-01"},
            {name:"Februarie", date:"2013-02-01"},
            {name:"Martie", date:"2013-03-01"},
            {name:"Aprilie", date:"2013-04-01"},
            {name:"Mai", date:"2013-05-01"},
            {name:"Iunie", date:"2013-06-01"},
            {name:"Iulie", date:"2013-07-01"},
            {name:"August", date:"2013-08-01"},
            {name:"Septembrie", date:"2013-09-01"},
            {name:"Octombrie", date:"2013-10-01"},
            {name:"Noiembrie", date:"2013-11-01"}

        ];

        this.viewMode = 'category';

        this.categoryList     = new CashApp.Collections.CategoryList();
        this.transactions     = new CashApp.Collections.Transaction();
        this.navigationYear   = new CashApp.Collections.NavigationYear(currentYear);

        this.cashNavigation     = new CashApp.Views.NavigationYearView({collection:this.navigationYear, el:$('.pagination')});
        this.addTransactionForm = new CashApp.Forms.AddTransaction({model: this.emptyTransaction(), el:$('#addTransactionForm')});

        this.cashNavigation.render();


        this.cashView =  new CashApp.Views.CashView({categories:this.categoryList,transactions:this.transactions , el: $('#app')});

        this.buffersView = new CashApp.Views.Buffers({collection: new CashApp.Collections.Buffers, el:$('#bufferList')});


        this.members = [];
        this.members.push(this.cashView);
        this.members.push(this.cashNavigation);
        this.members.push(this.addTransactionForm);
        this.members.push(this.buffersView);


        $('#accountView').bind('click', $.proxy(function(e) {e.preventDefault(); $('.panel-title').html('Category View'); this.raise('view_mode','category');}, this));
        $('#transactionView').bind('click', $.proxy(function(e) {e.preventDefault();$('.panel-title').html('Transaction View'); this.raise('view_mode','transaction');}, this));

        this.renderMoney();


    },

    editTransaction:function(id) {

        this.addTransactionForm.switchModel(this.transactions.get(id));
        this.addTransactionForm.render();
        this.addTransactionForm.show();

    },

    renderMoney:function() {
        this.cashView.render();
        return;
        var toRender = this.categoryListView;

        if (this.viewMode == 'transaction') {
            toRender = this.transactionsView;
        }

        toRender.render();
    },

    index: function(){
        var month = this.getFirstOfMonth();
        this.setActiveMonth(month);
    },

    setActiveMonth : function(month) {
        CashApp.trigger("active_month", month);
        this.raise('active_month', month);
        this.activeMonth = month;
    },

    start: function(){
       Backbone.history.start();

    },
    cashflow: function(month){
       this.setActiveMonth(month);
    },
    showAddTransaction : function() {

        if (this.addTransactionForm.model.get('id')) {
            console.log("I found an id so i must re-init");
            this.addTransactionForm.switchModel(this.emptyTransaction());
        }

        this.addTransactionForm.render();
        this.addTransactionForm.show();


    },

    emptyTransaction : function () {
        return new CashApp.Models.Transaction(
            {description:"Completeaza-ma!",fromAccount:49,date:this.getToday(),reference:'added from ui', amount:0}
        );
    },

    showTransactions : function () {
        this.viewMode = 'transaction';
        this.raise('view_mode','transaction');



    },

    showAccounts : function () {
        this.viewMode = 'category';
        this.raise('view_mode','category');

    },

    raise : function (eventName, eventData) {
        _.each(this.members,function( item ){
            item.trigger(eventName, eventData);
        },this);
    },
    getFirstOfMonth:function() {
        var myDate = new Date();
        return myDate.getFullYear()  + '-' + (myDate.getMonth()+1) + '-01';
    },
    getToday: function(){
        var myDate = new Date();
        return myDate.getFullYear()  + '-' + (myDate.getMonth()+1) + '-' + myDate.getDate() ;
    }
});

$(document).ready ( function (){
    CashApp.start();

});


