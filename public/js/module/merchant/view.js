Cash.Views.Merchants = Backbone.View.extend({

    initialize:function(){
        this.initTable();

        this.collection = new Cash.Models.Merchants();
        this.collection.fetch();
        this.collection.on('sync',this.render,this);
        this.accounts = new Cash.Models.Accounts();
        this.accounts.fetch({async:false});
    },

    render:function(){
        this.renderTable();
        this.addEditBehaviour();
        return this;
    },

    renderTable: function () {
        var data = this.collection.toJSON();
        var columns = d3.keys(data[0]);
        var that = this;

       this.thead
            .selectAll("th")
            .data(columns, function (d) {return d })
            .enter()
            .append("th")
            .text(function (column) {return column;});



        this.tbody.selectAll("tr")
            .data(data)
            .enter()
            .append("tr")
            .selectAll("td")
            .data(function (d) {
                return d3.values(d);
            })
            .enter()
            .append("td")
            .attr("class", function (d, i) {
                return 'merchant-' + columns[i]
            })
            .attr('data-type', function (d, i) {
                if (i == 3) {
                    return 'select';
                }
                return ''
            })
            .attr('data-pk', function (d, i) {
                return d;
            })
            .attr('data-value', function (d, i) {
                return d
            })
            .attr('data-name', function (d, i) {
                return columns[i];
            })
            .text(function (d, i) {
                if (i < 3) return d;
                return that.accounts.getNameFor(d)
            });
        },

        addEditBehaviour:function(){
            var that = this;
            $('.merchant-accountId').editable({
                source: this.accounts.map(function(account){
                    return {value:account.get('id'), text:account.get('name') };
                }),
                success: function(response, newValue) {
                    that.collection.merchant($(this).data('pk'))
                        .set('accountId',newValue)
                        .save();
                }
            });
        },

    initTable: function () {
        this.table = d3.select("body .merchants-container").insert("table", ":first-child")
            .attr("class", "table table-hover");

        this.thead = this.table.append('thead').append("tr");
        this.tbody = this.table.append('tbody');
    }


});