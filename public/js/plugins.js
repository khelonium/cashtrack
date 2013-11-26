// Avoid `console` errors in browsers that lack a console.
(function() {
    var method;
    var noop = function () {};
    var methods = [
        'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
        'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
        'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
        'timeStamp', 'trace', 'warn'
    ];
    var length = methods.length;
    var console = (window.console = window.console || {});

    while (length--) {
        method = methods[length];

        // Only stub undefined methods.
        if (!console[method]) {
            console[method] = noop;
        }
    }
}());

// Place any jQuery/helper plugins in here.

var CashApp = new (Backbone.View.extend({

    events: {
        'click a[data-backbone]':  function(e){
            e.preventDefault();
           console.log("I got this mofo event");

        },
        'click a':  function(e){
            e.preventDefault();
            console.log("I got this mofo event 2");

        }
    },

    Models:{},
    Views:{},
    Forms:{},
    Collections:{},

    start:function(){
        this.router = new CashApp.CashRouter();
        this.router.start();
    }
}))({el:document.body});