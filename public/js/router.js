
// Filename: router.js
define([
    'jquery',
    'underscore',
    'backbone',
    'forms/AddTransactionForm',
    'views/navigation/Year',
    'models/TransactionModel',
    'views/Cash/ComposedView'


], function($, _, Backbone , AddTransaction, YearView, TransactionModel, ComposedView){

    var CashRouter  =  Backbone.Router.extend({
        activeMonth: NaN,

        routes: {
            "": "index",
            "cashflow/:id": "cashflow",
            'addTransaction': 'showAddTransaction',
            'report/lastWeek': 'lastWeekReport',
            'transaction/:id/edit': 'editTransaction',
            'transactions': 'transactions',
            'accounts': 'accounts'
        },


        lastWeekReport : function ()
        {

        },

        initialize: function(){


            this.cashNavigation     = new YearView({el:$('.pagination')});
            this.addTransactionForm = new AddTransaction({model: this.emptyTransaction(), el:$('#addTransactionForm')});

            this.cashNavigation.render();

            this.cashView    =  new ComposedView({ el: $('#app')});


            this.members = [];
            this.members.push(this.cashView);
            this.members.push(this.cashNavigation);
            this.members.push(this.addTransactionForm);



            $('#addTransactionLink').bind('click', $.proxy(function(e) {
                e.preventDefault(); this.showAddTransaction()}, this));


            this.cashView.render();

        },

        transactions : function () {
            this.activeMonth || this.setActiveMonth(this.getFirstOfMonth());
            this.raise('view_mode','transaction');
        },

        accounts : function() {
            this.raise('view_mode','category');
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


            var transaction = new TransactionModel({id:id});

            this.addTransactionForm.switchModel(transaction);


            transaction.fetch();

            this.addTransactionForm.show();

        },

        index: function(){
            this.cashflow(this.getFirstOfMonth());
        },

        setActiveMonth : function(month) {
            this.raise('active_month', month);
            this.activeMonth = month;
        },

        cashflow: function(month){
            this.setActiveMonth(month);
        },

        showAddTransaction : function() {

            if (this.addTransactionForm.model.get('id')) {
                this.addTransactionForm.switchModel(this.emptyTransaction());
            }
            this.addTransactionForm.render();
            this.addTransactionForm.show();
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

        new CashRouter();

        Backbone.history.start();
    };
    return {
        initialize: initialize
    };
});