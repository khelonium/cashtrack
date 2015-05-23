define([
    'jquery',
    'underscore',
    'backbone',
    'models/CategoryModel'
], function($, _, Backbone, CategoryModel){


    return Backbone.Collection.extend({
        model:CategoryModel,
        url:"/api/cashflow",
        initialize:function() {
            this.on('active_month', this.fetchMonth, this)
        },
        fetchMonth:function(month) {
            this.fetch({data:{month:month},reset:true});
        }

    });

});