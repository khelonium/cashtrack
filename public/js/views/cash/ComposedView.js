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
            this.categoryMode       = new CategoryView({collection:options.categories, el: this.el});
            this.transactionMode    = new TransactionView({collection:options.transactions, el: this.el});

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
                    console.log("Category view");
                    this.activeView = this.categoryMode;
                    break;
                case 'transaction':
                    console.log("Transaction view");
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