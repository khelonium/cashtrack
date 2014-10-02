Cash.Models.Merchants = Backbone.Collection.extend({
    url: '/api/merchant',
    merchant:function(id){
        console.log("Searching for " + id);
        return this.filter(function(merchant){
           return merchant.get('id') == id;
        })[0];
    }
});
