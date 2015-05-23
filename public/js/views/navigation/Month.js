define([
    'jquery',
    'underscore',
    'backbone',
    // Pull in the Collection module from above
], function($, _, Backbone){

    var MonthView = Backbone.View.extend({
        tagName:'li',
        template: _.template('<a class="month-view" data-cashmonth="<%= date %>" href="#/cashflow/<%= date %>"><%= name %></a>'),

        events:  {
            'click a':'toggleActive'
        },

        initialize:function(options) {
            this.on("active_month",this.activateMonth, this);

        },
        activateMonth: function(month) {
            var date = new Date(month);

            month = date.getMonth() + 1;
            if(month <= 9) {
                month = '0'+month;
            }
            var month_start = date.getFullYear() + '-' +  month + '-01';
            if (this.model.get('date') === month_start) {
                this.toggleActive();
            }

        },


        toggleActive:function(){
            this.$el.parent().find('li.active').removeClass('active');
            this.$el.toggleClass('active');
            this.model.collection.trigger("activatedMonth",this.model.get('date'));
        },
        render:function(){
            this.$el.html(this.template(this.model.attributes));
        }
    }, Backbone.Events);

    // Returning instantiated views can be quite useful for having "state"
    return MonthView;
});