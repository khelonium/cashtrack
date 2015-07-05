define([
    'jquery',
    'underscore',
    'backbone',
    'collections/TransactionCollection',
    'views/cash/TransactionView'

], function ($, _, Backbone, TransactionCollection, TransactionView) {

    return Backbone.View.extend({

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


            this.expandedView  = new TransactionView({ el:new_el.find('.transaction-container')});

            this.expandedView.collection.fetch({data:{month:this.model.get('month'), accountId:this.model.get('accountId')}, reset:true});

        },

        render: function() {
            this.$el.html(this.template(this.model.attributes));
        }
    });



});
