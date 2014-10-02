var ChartView = function(chart) {


    this.circle   = chart.chart.selectAll("circle");
    this.text     = chart.chart.selectAll("text");
    this.isUpdating = false;

    var that    = this;



    this.add = function(nodes) {

        this.isUpdating =  false;
        that.leads = new Leads(nodes, chart.getConfig());

        nodes.forEach(function(d){
            d.radius = that.leads.scaleR(d.total);
        });

        this.circle = chart.chart.selectAll("circle")
            .data(nodes, function(d) {return d.identifier});


        this.text = chart.chart.selectAll("text")
            .data(nodes, function(d) { return d.identifier});


        enterTransition();

        updateTransition();

        exitTransition();

        //enter


        // EXIT

    };

    function updateTransition() {

        //update
        that.circle.transition()
            .attr("r", function(d) { return that.leads.scaleR(d.total)})
            .attr('cx',function(d){return that.leads.scaleX(d.total)})
            .attr('cy',function(d){return that.leads.scaleY(d.total)});

        ;

        that.text.transition()
            .duration(1000)
            .text(function(d){return d.name});
    };

    function enterTransition() {
        that.circle.enter().append("circle")
            .style("fill", function(d) { return that.leads.color(d.cluster); })
            .attr("r", function(d) { return that.leads.scaleR(d.total)})
            .attr('cx',function(d){return that.leads.scaleX(d.total)})
            .attr('cy',function(d){return that.leads.scaleY(d.total)})
            .on('click',function(d){
                load(month, d.id_category);
            })
           ;


        that.text.enter().append("text")
            .attr("dx", 12)
            .attr("dy", ".35em")
            .text(function(d){return d.name});

    };

    function exitTransition() {
        that.text.exit()
            .attr("fill", "red")
            .transition()
            .duration(750)
            .attr("y", 60)
            .style("fill-opacity", 1e-6)
            .remove();


        that.circle.exit()
            .transition()
            .style("fill-opacity",1e-6)
            .remove();
    };
} ;

var ChartContainer = function (config) {

    this.body = d3.select("body .container").insert("svg",":first-child")
        .attr("width", config.width)
        .attr("height", config.height);

    this.header = this.body.append('g')
        .attr('height', config.topMargin);

    this.chart = this.body.append('g')
        .attr('y', config.topMargin);

    this.chart.append('rect')
        .attr('width',config.width)
        .attr('height',config.height - config.topMargin)
        .attr('fill','lightgray')
        .attr('y',config.topMargin);

    this.force = d3.layout.force()
        .size([config.width, config.height])
        .gravity(0);


    this.getConfig = function() {
        return config;
    };

};
