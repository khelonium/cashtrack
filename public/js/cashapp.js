/**
 * Created with JetBrains PhpStorm.
 * User: logo
 * Date: 11/30/13
 * Time: 2:39 PM
 * To change this template use File | Settings | File Templates.
 */

var cashCode = {
    Models:{},
    Views:{},
    Forms:{},
    Collections:{}
};


cashCode.CashRouter  =  Backbone.Router.extend({
    activeMonth : NaN,

    routes: {
        "": "index",
        "cashflow/:id"         : "cashflow" ,
        'addTransaction'       : 'showAddTransaction' ,
        'transaction/:id/edit' : 'editTransaction'
    },
    initialize: function(){

        this.categoryList     = new cashCode.Collections.CategoryList();
        this.transactions     = new cashCode.Collections.Transaction();

        this.cashNavigation     = new Cash.Views.NavigationYearView({el:$('.pagination')});
        this.addTransactionForm = new cashCode.Forms.AddTransaction({model: this.emptyTransaction(), el:$('#addTransactionForm')});

        this.cashNavigation.render();

        this.cashView    =  new cashCode.Views.CashView({categories:this.categoryList,transactions:this.transactions , el: $('#app')});


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

    start: function(){
        Backbone.history.start();

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

    emptyTransaction : function () {
        return new cashCode.Models.Transaction(
            {description:"Completeaza-ma!",fromAccount:49,date:this.getToday(),reference:'added from ui', amount:0}
        );
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
    },
    getToday: function(){
        var myDate = new Date();
        return myDate.getFullYear()  + '-' + (myDate.getMonth()+1) + '-' + myDate.getDate() ;
    }
});


var CashApp =Backbone.View.extend({

    initialize:function(options){
        this.Models  = options.code.Views;
        this.Models  = options.code.Models;
        this.Forms   = options.code.Forms;
        this.router  = options.code.CashRouter;
    },

    events: {
        'click a[data-backbone]':  function(e){

        }
    },

    Models:{},
    Views:{},
    Forms:{},
    Collections:{},

    start:function(){
        this.router = new this.router();
        this.router.start();
    }

});


$(document).ready ( function (){
    var cashApp = new CashApp(({el:document.body, code:cashCode}));
    cashApp.start();

});


