define([
    'jquery',
    'underscore',
    'backbone',
    'd3',
    'views/report/BaseBarChart'


], function ($, _, Backbone, d3, BaseBarChart) {

    return BaseBarChart.extend({


        breakdownAPI : '/report/month/',
        overviewAPI : "/api/overview/time/month/",
        click : NaN,

        onClick : function(callback) {
            this.click = callback;
        },

        getBreakdownUrl: function (year) {
            return this.breakdownAPI + year + '/';
        },

        render : function(year) {


            var that = this;

            that.break_url = this.getBreakdownUrl(year);


            d3.json(this.overviewAPI  + year , function(error,data){

                that.x.domain(data.map(function(d) { return d.unit_nr; }));
                that.y.domain([0, d3.max(data, function(d) { return +d.amount; })]);

                var barChart =that.svg.selectAll(".bar")
                    .data(data);


                barChart.enter().append("rect")
                    .on('click',function(d){
                        that.click(that.break_url + d.unit_nr);
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

});
