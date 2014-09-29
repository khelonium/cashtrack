var MerchantsView = Backbone.View.extend({
    initialize:function(){

        this.table  = d3.select("body .merchants-container").insert("table",":first-child")
            .attr("class","table table-hover");

        this.thead = this.table.append('thead');
        this.tbody = this.table.append('tbody');

        this.collection = new Merchants();
        this.collection.fetch();
        this.collection.on('change',this.render,this);
        this.collection.on('sync',this.render,this);

    },

    render:function(){

        var data = this.collection.toJSON();

        console.log("Rendering dta");
        console.log(data);
        this.thead.append("tr")
            .selectAll("th")
            .data(d3.keys(data[0]))
            .enter()
            .append("th")
            .text(function(column) { return column; });

        var rows = this.tbody.selectAll("tr")
            .data(data)
            .enter()
            .append("tr");

        var cells = rows.selectAll("td")
            .data(function(d){return d3.values(d);})
            .enter()
            .append("td")
            .text(function(d) {return d});

        return this;
    }
});/**
 * Created by cdordea on 29/09/14.
 */
