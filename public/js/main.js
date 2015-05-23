

require.config({
    paths: {
        jquery: 'vendor/jquery.min',
        underscore: 'vendor/underscore-min',
        backbone: 'vendor/backbone-min'
    }

});

require([

    'app',

], function(App){




    window.template = function(id){
        return _.template( $('#'+ id).html());
    };

   App.initialize();
});