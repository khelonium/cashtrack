define([
    'jquery',
    'underscore',
    'backbone',
    'text!templates/nav-meta.html',

    // Pull in the Collection module from above
], function($, _, Backbone, navTemplate){

    return Backbone.View.extend({

        events : {
            'click .evolution' : 'showEvolution'
        },
        initialize : function() {
            this.model = new Backbone.Model({title:"default"});
            this.model.bind('change', this.render, this);
            this.template = _.template(navTemplate);

        },
        title : function(text) {
            this.model.set({title:text});
        },
        render : function(){

            this.$el.html(this.template(this.model.attributes));
            this.buttons = this.$el.find('.control-buttons');
            this.buttons && this.buttons.hide();
        },

        actions: function(callbacks)
        {
            callbacks.evolution && this.buttons.show();
            this.evolutionCallback  = callbacks.evolution;

        },

        showEvolution : function() {
            console.log("Must show evolution");
            this.evolutionCallback && this.evolutionCallback();
        }


    });

});