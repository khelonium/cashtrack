define([
    'jquery',
    'underscore',
    'backbone',
    'views/AbstractTransaction',
    'views/Category',
    'collections/CategoryCollection',


], function($, _, Backbone, AbstractTransaction, Category, CategoryCollection){

    return  AbstractTransaction.extend({


        initialize:function () {
          this.collection = new CategoryCollection();
          this.collection.on('reset', this.render, this);

        },
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
            var categoryView = new Category({model: categoryItem});
            categoryView.render();
            this.$el.append(categoryView.el);
        }
    });

});