define([
    'jquery',
    'underscore',
    'backbone',
    'collections/AccountsCollection',
    'text!templates/transaction/addTransaction.html',
    'forms/AddTransactionForm/AccountSelect',
    'vendor/jquery-ui-1.10.3.custom.min'
], function($, _, Backbone, AccountsCollection, transactionTemplate, AccountSelect){

    var AddTransaction = Backbone.View.extend({
        events : {
            'submit' : 'save'
        },

        initialize:function(){


            this.accountList = new  AccountsCollection();
            this.listenTo(this.model, 'change',this.render);
            this.listenTo(this.model, 'canHide',this.hide);
            this.initModelListeners(this.model);
            this.on('working_month',this.setDate,this);
            this.accountList.fetch();

        },

        setDate : function(month) {
            this.model.set({date: month});
        },

        save : function($e) {


            $e.preventDefault();

            var description  = this.$('textarea[name=description]').val();
            var amount       = this.$('input[name=amount]').val();
            var date         = this.$('input[name=datepicker]').val();
            var idAccount    = this.$('select[name=idAccount]').val();


            this.model.save({description:description, amount:amount, date:date, toAccount:idAccount},{success :function(model,response,options){
                model.trigger('canHide');

            }});
        },

        switchModel: function(model) {
            this.stopListening(this.model);
            this.initModelListeners(model);
            this.model = model;

            this.render();
        },

        initModelListeners: function(model) {
            this.listenTo(model, 'change',this.render);
            this.listenTo(model, 'canHide',this.hide);
        },

        show: function() {
            console.log("Must show");
            this.$el.show();
        },

        hide: function() {
            console.log("Must hide");
            this.$el.hide();
        },

        render:function(){

            var template = _.template(transactionTemplate);

            this.$el.html(template(this.model.attributes));


            this.calendar =  $("#datepicker").datepicker({ dateFormat: 'yy-mm-dd' });
            this.select = new AccountSelect({collection:this.accountList,el:$('#idAccount')});

            this.select.render();

            $("#idAccount").val(this.model.get('toAccount'));

        }
    });

    return AddTransaction;
});