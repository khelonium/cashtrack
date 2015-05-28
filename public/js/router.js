
// Filename: router.js
define([
    'jquery',
    'underscore',
    'backbone',
    'forms/AddTransactionForm',
    'views/navigation/Year',
    'models/TransactionModel',
    'views/cash/TransactionView',
    'views/cash/CategoryView',
    'views/report/CategoryBarChart',
    'views/report/MonthBarChart',
    'views/report/WeekBarChart',
    'views/report/YearBarChart',





], function($, _, Backbone , AddTransaction, YearView, TransactionModel, TransactionView, CategoryView, CategoryBarChart, MonthBarChart, WeekBarChart , YearBarChart){

    var CashRouter  =  Backbone.Router.extend({
        activeMonth: NaN,
        app : NaN,
        view :NaN,

        routes: {
            "": "index",
            "cashflow/:id": "cashflow",
            'addTransaction': 'showAddTransaction',
            'report/lastweek': 'lastWeekReport',
            'report/thisweek': 'thisWeekReport',
            'report/thismonth': 'thisMonthReport',
            'report/month/:year/:month': 'monthReport',
            'report/year/:year': 'yearReport',
            'report/week/:year/:week': 'weekReport',
            'report/yearly': 'yearlyReport',
            'report/monthly': 'monthlyReport',
            'report/weekly': 'weeklyReport',
            'transaction/:id/edit': 'editTransaction',
            'transactions': 'transactions',
            'accounts': 'accounts'
        },

        yearReport : function (year) {
            var view = new CategoryBarChart({ el: $('#app')});
            view.yearReport(year);
        },

        yearlyReport : function() {
            var view = new YearBarChart({ el: $('#app')});

            var that = this;

            view.onClick(function(url){
                that.navigate(url, true);
            });

            view.render((new Date().getFullYear()));
        },

        weeklyReport:function(){
            var view = new WeekBarChart({ el: $('#app')});

            var that = this;

            view.onClick(function(url){
                that.navigate(url, true);
            });

            view.render((new Date().getFullYear()));
        },

        monthReport : function (year, month) {
            var view = new CategoryBarChart({ el: $('#app')});
            view.monthReport(year, month);
        },

        weekReport : function (year, week) {
            var view = new CategoryBarChart({ el: $('#app')});
            view.weekReport(year, week);
        },


        monthlyReport : function () {
            var view = new MonthBarChart({ el: $('#app')});

            var that = this;

            view.onClick(function(url){
                that.navigate(url, true);
            });

            view.render((new Date().getFullYear()));
        },

        lastWeekReport : function ()
        {
            var view = new CategoryBarChart({ el: $('#app')});
            view.lastWeekReport();
        },


        thisWeekReport : function ()
        {
            var view = new CategoryBarChart({ el: $('#app')});
            view.thisWeekReport();
        },

        thisMonthReport : function ()
        {
            var view = new CategoryBarChart({ el: $('#app')});
            view.thisMonthReport();
        },


        initialize: function(){


            this.cashNavigation     = new YearView({el:$('.pagination')});
            this.addTransactionForm = new AddTransaction({model: this.emptyTransaction(), el:$('#addTransactionForm')});

            this.cashNavigation.render();


            this.app = $('#app');


            this.members = [];
            this.members.push(this.cashNavigation);
            this.members.push(this.addTransactionForm);



            $('#addTransactionLink').bind('click', $.proxy(function(e) {
                e.preventDefault(); this.showAddTransaction()}, this));

            this.accounts();


        },


        transactions : function () {


            this.activeMonth || this.setActiveMonth(this.getFirstOfMonth());

            this.app.html('<table class="table table-hover"></table>');

            this.view = new TransactionView({el:this.app.find('table')});

            this.view.collection.fetchMonth(this.activeMonth);


        },

        accounts : function() {
            this.activeMonth || this.setActiveMonth(this.getFirstOfMonth());

            this.app.html('<table class="table table-hover"></table>');

            this.view = new CategoryView({el:this.app.find('table')});

            this.view.collection.fetchMonth(this.activeMonth);
        },

        emptyTransaction : function () {
            return new TransactionModel(
                {description:"Completeaza-ma!",fromAccount:49,date:  this.getToday(),reference:'added from ui', amount:0}
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
            this.view && this.view.collection && this.view.collection.fetchMonth && this.view.collection.fetchMonth(month);
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