

require.config({
    urlArgs: "bust=v2",
    paths: {
        jquery: 'vendor/jquery.min',
        underscore: 'vendor/underscore-min',
        backbone: 'vendor/backbone-min',
        d3: 'vendor/d3.v3.min'
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