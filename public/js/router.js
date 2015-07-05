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
    'views/navigation/Meta',


], function (
    $, _, Backbone, AddTransaction,
    YearView, TransactionModel, TransactionView,
    CategoryView, CategoryBarChart, MonthBarChart, WeekBarChart, YearBarChart,
    Meta) {

    var CashRouter = Backbone.Router.extend({
        activeMonth: NaN,
        navMeta:NaN,

        app: NaN,
        view: NaN,

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

        yearReport: function (year) {
            this.navMeta.title("Year " + year);
            var view = new CategoryBarChart({el: $('#app')});

            view.yearReport(year);

        },



        weeklyReport: function () {
            this.navMeta.title("Weekly report");

            this.timeReport(new WeekBarChart({el: $('#app')}));
        },

        monthlyReport: function (account) {

            this.navMeta.title("Monthly report");

            account && this.navMeta.title("Monthly report for " + account.name);

            var accountId = null;

            if (account) {
                accountId = account.accountId;
            }

            this.timeReport(new MonthBarChart({el: $('#app')}), accountId);
        },

        yearlyReport: function () {
            this.navMeta.title("Yearly report");

            var view = new YearBarChart({el: $('#app')});
            var that = this;

            view.onClick(function (url) {
                that.navigate(url, true);
            });

            view.render((new Date().getFullYear()));
        },

        timeReport: function (view, accountId) {

            var that = this;

            view.onClick(function (url) {
                that.navigate(url, true);
            });

            this.cashNavigation.doOnYearChange = function (anotherYear) {
                view.render(anotherYear);
            };

            view.render((new Date().getFullYear()), accountId);
        },

        monthReport: function (year, month) {
            this.navMeta.title("Month breakdown" +' '+ year + '/' +  month);
            var view = new CategoryBarChart({el: $('#app')});
            view.monthReport(year, month);
            view.on("barAction",this.categoryOverMonth, this);
        },

        categoryOverMonth: function(event) {
            this.navMeta.title(event.barChart.amount + " on " + event.barChart.name  + " in " + event.barChart.month);

            var evolution = function () {
                this.monthlyReport(event.barChart);
            }.bind(this);


            this.navMeta.actions({evolution:evolution});
        },

        weekReport: function (year, week) {
            this.navMeta.title("Week breakdown" +' '+ year + '/' +  week);

            var view = new CategoryBarChart({el: $('#app')});
            view.weekReport(year, week);
        },

        lastWeekReport: function () {
            this.navMeta.title("Last Week");

            var view = new CategoryBarChart({el: $('#app')});
            view.lastWeekReport();
        },


        thisWeekReport: function () {
            this.navMeta.title("This Week");

            var view = new CategoryBarChart({el: $('#app')});
            view.thisWeekReport();
        },

        thisMonthReport: function () {
            this.navMeta.title("This Month");

            var view = new CategoryBarChart({el: $('#app')});
            view.thisMonthReport();
        },


        initialize: function () {


            this.navMeta = new Meta({el: $('#nav-meta')});


            this.cashNavigation = new YearView({el: $('.pagination')});
            this.addTransactionForm = new AddTransaction({
                model: this.emptyTransaction(),
                el: $('#addTransactionForm')
            });

            this.cashNavigation.render();


            this.app = $('#app');


            this.members = [];
            this.members.push(this.cashNavigation);
            this.members.push(this.addTransactionForm);


            $('#addTransactionLink').bind('click', $.proxy(function (e) {
                e.preventDefault();
                this.showAddTransaction()
            }, this));

            this.accounts();


        },


        transactions: function () {

            this.navMeta.title("Transactions on " +  this.activeMonth);

            this.activeMonth || this.setActiveMonth(this.getFirstOfMonth());

            this.app.html('<table class="table table-hover"></table>');

            this.view = new TransactionView({el: this.app.find('table')});

            this.view.collection.fetchMonth(this.activeMonth);


        },

        accounts: function () {
            this.activeMonth || this.setActiveMonth(this.getFirstOfMonth());

            this.app.html('<table class="table table-hover"></table>');

            this.view = new CategoryView({el: this.app.find('table')});

            this.view.collection.fetchMonth(this.activeMonth);
        },

        emptyTransaction: function () {
            return new TransactionModel(
                {
                    description: "Completeaza-ma!",
                    fromAccount: 49,
                    date: this.getToday(),
                    reference: 'added from ui',
                    amount: 0
                }
            );
        },

        getToday: function () {
            var myDate = new Date();
            return myDate.getFullYear() + '-' + (myDate.getMonth() + 1) + '-' + myDate.getDate();
        },


        editTransaction: function (id) {


            var transaction = new TransactionModel({id: id});

            this.addTransactionForm.switchModel(transaction);


            transaction.fetch();

            this.addTransactionForm.show();

        },

        index: function () {
            this.cashflow(this.getFirstOfMonth());
        },

        setActiveMonth: function (month) {
            this.raise('active_month', month);
            this.activeMonth = month;

            this.accounts();

        },

        cashflow: function (month) {
            this.navMeta.title("Accounts on " + month);

            this.setActiveMonth(month);
        },

        showAddTransaction: function () {

            if (this.addTransactionForm.model.get('id')) {
                this.addTransactionForm.switchModel(this.emptyTransaction());
            }
            this.addTransactionForm.render();
            this.addTransactionForm.show();
        },


        raise: function (eventName, eventData) {
            _.each(this.members, function (item) {
                item.trigger(eventName, eventData);
            }, this);
        },

        getFirstOfMonth: function () {
            var myDate = new Date();
            return myDate.getFullYear() + '-' + (myDate.getMonth() + 1) + '-01';
        }


    });


    var initialize = function () {

        new CashRouter();

        Backbone.history.start();
    };

    return {
        initialize: initialize
    };
});