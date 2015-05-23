define([
    'jquery',
    'underscore',
    'backbone',
], function($, _, Backbone){

    var AccountItem =  Backbone.View.extend({
        template: _.template('<option value ="<%= id %>"><%= name %></option>'),
        tagName: 'option',
        render:function(){
            this.$el.html(this.template(this.model.attributes));
            return this;
        }
    });
    return  Backbone.View.extend({

        initialize:function(){
            this.collection.on('change',this.render,this);
            this.collection.on('sync',this.render,this);
        },
        render:function(){
            _.each(this.collection.models,function( item ){
                this.$el.append(new AccountItem({model:item}).render().$el.html() );
            },this);

            return this;
        }


    });



});