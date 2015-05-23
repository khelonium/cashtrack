/**
 * Created with JetBrains PhpStorm.
 * User: logo
 * Date: 11/30/13
 * Time: 2:39 PM
 * To change this template use File | Settings | File Templates.
 */

var cashCode = {
    Models:{},
    Views:{},
    Forms:{},
    Collections:{}
};


cashCode.CashRouter  =  Backbone.Router.extend({


});


var CashApp =Backbone.View.extend({

    initialize:function(options){
        this.Models  = options.code.Views;
        this.Models  = options.code.Models;
        this.Forms   = options.code.Forms;
        this.router  = options.code.CashRouter;
    },

    events: {
        'click a[data-backbone]':  function(e){

        }
    },

    Models:{},
    Views:{},
    Forms:{},
    Collections:{},

    start:function(){
        this.router = new this.router();
        this.router.start();
    }

});


$(document).ready ( function (){
   var cashApp = new CashApp(({el:document.body, code:cashCode}));
    cashApp.start();

});


