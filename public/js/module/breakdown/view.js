
Cash.Views.BarChart = Backbone.View.extend({
    margin : {top: 20, right: 20, bottom: 70, left: 40},

    initialize:function() {
        this.width  = 960 - this.margin.left - this.margin.right;
        this.height = 500 - this.margin.top - this.margin.bottom;

        this.x =  d3.scale.ordinal()
            .rangeRoundBands([0, this.width], .1);

        this.y =  d3.scale.linear()
            .range([this.height, 0]);

        this.xAxis = d3.svg.axis()
            .scale(this.x)
            .orient("bottom");


        this.yAxis = d3.svg.axis()
            .scale(this.y)
            .orient("left")
            .ticks(10, "");


        this.svg = d3.select("body .bar-chart-container").append("svg")
            .attr("width", this.width + this.margin.left + this.margin.right)
            .attr("height", this.height + this.margin.top + this.margin.bottom)
            .append("g")
            .attr("transform", "translate(" + this.margin.left + "," + this.margin.top + ")");

        this.svg.append("g")
            .attr("class", "x axis")
            .attr("transform", "translate(0," + this.height + ")")
            .call(this.xAxis);


        this.svg.append("g")
            .attr("class", "y axis")
            .call(this.yAxis)
            .append("text")
            .attr("transform", "rotate(-90)")
            .attr("y", 6)
            .attr("dy", ".71em")
            .style("text-anchor", "end")
            .text("Total");

    }

});


Cash.Views.MonthBarChart = Cash.Views.BarChart.extend({



    breakdownAPI : '/report/month/',
    overviewAPI : "/api/overview/time/month/",



    render : function(year) {
        var url = this.overviewAPI + '' + year;
        var that = this;
        d3.json(url , function(error,data){

            that.x.domain(data.map(function(d) { return d.unit_nr; }));
            that.y.domain([0, d3.max(data, function(d) { return +d.amount; })]);



            var barChart =that.svg.selectAll(".bar")
                .data(data);


            var break_url = that.breakdownAPI;
            barChart.enter().append("rect")
                .on('click', function(d){
                    window.location.href = break_url + d.unit_nr;
                });


                barChart.transition()
                    .delay(function(d, i) {
                        return i / data.length * 1000;
                    })
                    .duration(500)
                .attr("class", function (d) {  if (d.amount<10000) return 'bar excelent';if (d.amount > 20000) return "bar excessive"; if (d.amount > 14000) return "bar over";return "bar";})
                .attr("x", function(d) { return that.x(d.unit_nr); })
                .attr("width", that.x.rangeBand())
                .attr("y", function(d) { return that.y(d.amount); })
                .attr("height", function(d) { return that.height - that.y(d.amount); });

            //Update X axis
            that.svg.select(".x.axis")
                .transition()
                .duration(1000)
                .call(that.xAxis);

            //Update Y axis
            that.svg.select(".y.axis")
                .transition()
                .duration(1000)
                .call(that.yAxis);


        });
    }


});

Cash.Views.WeekBarChart = Cash.Views.MonthBarChart.extend({
    breakdownAPI : '/report/week/',
    overviewAPI : "/api/overview/time/week/"

});

Cash.Views.YearBarChart = Cash.Views.MonthBarChart.extend({
    breakdownAPI : '/report/year/',
    overviewAPI : "/api/overview/time/year/"

});


Cash.Views.WeekCategoriesBarChart =  Cash.Views.BarChart.extend (({

    render : function(url){

        var that = this;
        d3.json(url , function(error, data) {
            that.x.domain(data.map(function(d) { return d.name; }));
            that.y.domain([0, d3.max(data, function(d) { return +d.amount; })]);



            var barChart =that.svg.selectAll(".bar")
                .data(data);


            barChart.enter().append("rect");


            barChart.transition()
                .delay(function(d, i) {
                    return i / data.length * 1000;
                })
                .duration(500)
                .attr("class", function (d) {  if (d.amount<10000) return 'bar excelent';if (d.amount > 20000) return "bar excessive"; if (d.amount > 14000) return "bar over";return "bar";})
                .attr("x", function(d) { return that.x(d.name); })
                .attr("width", that.x.rangeBand())
                .attr("y", function(d) { return that.y(d.amount); })
                .attr("height", function(d) { return that.height - that.y(d.amount); });


            that.svg.append("g")
                .attr("class", "x axis")
                .attr("transform", "translate(0," + that.height + ")")
                .call(that.xAxis)
                .selectAll("text")
                .style("text-anchor", "end")
                .attr("dx", "-.8em")
                .attr("dy", ".15em")
                .attr("transform", function(d) {
                    return "rotate(-55)"
                });

            //Update Y axis
            that.svg.select(".y.axis")
                .transition()
                .duration(1000)
                .call(that.yAxis);

        });

    }
}));