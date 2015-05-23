var cashCode = {
    Models:{},
    Views:{},
    Forms:{},
    Collections:{}
};


// Filename: router.js
define([
    'jquery',
    'underscore',
    'backbone',
    'cashapp/views',
    'forms/AddTransactionForm',
    'views/navigation/Year',
    'collections/CategoryCollection',
    'collections/TransactionCollection',
    'models/TransactionModel',
    'views/Cash/ComposedView'


], function($, _, Backbone , views, AddTransaction, YearView, CategoryCollection, TransactionCollection, TransactionModel, ComposedView){

    var CashRouter  =  Backbone.Router.extend({
        activeMonth: NaN,

        routes: {
            "": "index",
            "cashflow/:id": "cashflow",
            'addTransaction': 'showAddTransaction',
            'transaction/:id/edit': 'editTransaction'
        },

        initialize: function(){


            this.categoryList       = new CategoryCollection();
            this.transactions       = new TransactionCollection();
            this.cashNavigation     = new YearView({el:$('.pagination')});
            this.addTransactionForm = new AddTransaction({model: this.emptyTransaction(), el:$('#addTransactionForm')});

            this.cashNavigation.render();

            this.cashView    =  new ComposedView({categories:this.categoryList,transactions:this.transactions , el: $('#app')});


            this.members = [];
            this.members.push(this.cashView);
            this.members.push(this.cashNavigation);
            this.members.push(this.addTransactionForm);
            this.members.push(this.categoryList);
            this.members.push(this.transactions);


            $('#accountView').bind('click', $.proxy(function(e) {e.preventDefault(); $('.panel-title').html('Category View'); this.raise('view_mode','category');}, this));
            $('#transactionView').bind('click', $.proxy(function(e) {e.preventDefault();$('.panel-title').html('Transaction View'); this.raise('view_mode','transaction');}, this));

            $('#addTransactionLink').bind('click', $.proxy(function(e) {
                e.preventDefault(); this.showAddTransaction()}, this));


            this.cashView.render();

        },

        emptyTransaction : function () {
            return new TransactionModel(
                {description:"Completeaza-ma!",fromAccount:49,date:this.getToday(),reference:'added from ui', amount:0}
            );
        },

        getToday: function(){
            var myDate = new Date();
            return myDate.getFullYear()  + '-' + (myDate.getMonth()+1) + '-' + myDate.getDate() ;
        },



        editTransaction:function(id) {

            this.addTransactionForm.switchModel(this.transactions.get(id));
            this.addTransactionForm.render();
            this.addTransactionForm.show();

        },

        index: function(){
            var month = this.getFirstOfMonth();
            this.setActiveMonth(month);
        },

        setActiveMonth : function(month) {
            this.raise('active_month', month);
            this.activeMonth = month;
        },

        cashflow: function(month){
            this.setActiveMonth(month);
        },

        showAddTransaction : function() {

            console.log("Adding transaction ");
            if (this.addTransactionForm.model.get('id')) {
                console.log(" Empty transaction ");
                this.addTransactionForm.switchModel(this.emptyTransaction());
            }

            this.addTransactionForm.render();
            this.addTransactionForm.show();


        },



        showTransactions : function () {
            this.raise('view_mode','transaction');

        },

        showAccounts : function () {
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
        }


    });



    var initialize = function(){

        var app_router = new CashRouter();

        Backbone.history.start();
    };
    return {
        initialize: initialize
    };
});