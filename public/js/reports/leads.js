
var Leads = function(nodes, config) {

    var that          = this;
    this.clusterLeads = [];


    var xScale = d3.scale.linear();
    var yScale = d3.scale.linear();
    var rScale = d3.scale.linear();

    var colorScale = d3.scale.category20();



    this.xAxis = d3.svg.axis()
        .scale(xScale)
        .orient("bottom")
        .ticks(10);


    this.load = function (nodes) {



        nodes.forEach(function(cluster) {
            if (
                !that.clusterLeads[cluster.cluster]
                || (cluster.radius > that.clusterLeads[cluster.cluster].radius)
            )
                that.clusterLeads[cluster.cluster] = cluster;
        });




        xScale.domain([0, d3.max(nodes, function(d) { return +d.total; })])
            .range([config.width - config.maxRadius, config.maxRadius]);

        yScale.domain([0, d3.max(nodes, function(d) { return +d.total; })])
            .range([config.height - config.maxRadius,  config.maxRadius]);


        rScale.domain([0, d3.max(nodes, function(d) { return +d.total; })])
            .range([0, config.maxRadius]);

        colorScale.domain(d3.range(that.clusterLeads.length));

        this.xAxis = d3.svg.axis()
            .scale(xScale)
            .orient("bottom")
            .ticks(10);


        this.yAxis = d3.svg.axis()
            .scale(yScale)
            .orient("left")
            .ticks(10);


        nodes.forEach(function(d){
            d.x =  that.scaleX(d.total);
            d.y =  that.scaleY(d.total);
        });



    };

    this.color = function(v){
        return   colorScale(v);
    } ;
    this.scaleX = function(x) {
        return xScale(+x);
    };

    this.scaleY = function(y) {
        return  yScale(+y) + config.topMargin;
    };

    this.scaleR = function(r) {
        return rScale(+r);
    };

    this.getFoci =  function() {

        var foci = [];

        nodes.forEach(function(node){
            if (!foci[node.cluster]) {
                foci[node.cluster] =
                {
                    'cluster':node.cluster,
                    'total':+node.total
                };
            } else {
                foci[node.cluster].total += +node.total;
            };


        });

        var maxFunc = function(d) {return +d.total; };

        max = d3.max(d3.values(foci),maxFunc);


        xScale.domain([0, max])
            .range([config.width - config.maxRadius, config.maxRadius]);

        yScale.domain([0,max])
            .range([config.height - config.maxRadius, config.topMargin]);


        return foci.map( function(lead) {
            lead.x = that.scaleX(lead.total);
            lead.y = that.scaleY(lead.total);

            return lead;
        });

    };


    this.load(nodes);

};