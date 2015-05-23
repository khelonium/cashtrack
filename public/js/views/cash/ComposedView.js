define([
    'jquery',
    'underscore',
    'backbone',
    'views/AbstractTransaction',
    'views/cash/TransactionView',
    'views/cash/CategoryView',

], function($, _, Backbone, AbstractTransaction, TransactionView, CategoryView){

    return AbstractTransaction.extend({
        mode : "category",
        transactionMode : NaN,
        categoryMode: NaN,
        activeView : NaN,

        initialize : function(options) {
            this.$el.html('<table class="table table-hover"></table>');

            this.categoryMode       = new CategoryView({ el: this.$el.find('table')});
            this.transactionMode    = new TransactionView({collection:options.transactions, el: this.$el.find('table')});

            this.activeView = this.categoryMode;

            this.on('view_mode', this.setMode, this);
            this.on('active_month', this.setMonth, this);

        },

        setMonth : function (month) {
            console.log("Set Month");
            this.activeMonth = month;
            this.categoryMode.setMonth(month);
            this.transactionMode.setMonth(month);

            this.categoryMode.collection.fetchMonth(month);

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
});