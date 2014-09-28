function Year(year) {
    this.year = year;

    var months = [
        {name:"Ianuarie"  , month:0},
        {name:"Februarie" , month:1},
        {name:"Martie"    , month:2},
        {name:"Aprilie"   , month:3},
        {name:"Mai"       , month:4},
        {name:"Iunie"     , month:5},
        {name:"Iulie"     , month:6},
        {name:"August"    , month:7},
        {name:"Septembrie", month:8},
        {name:"Octombrie" , month:9},
        {name:"Noiembrie" , month:10},
        {name:"Decembrie" , month:11}
    ];




    this.initYear = function(year) {
        for (var i=0;i<12;i++) {
            months[i].date = year + "-" + (i+1) + '-' + '01';
        }
    };


    this.initYear(year);


    this.getMonths = function() {
        return months;
    };

    this.nextYear = function() {
        this.year +=1;
        this.initYear(this.year);

        return this;
    };

    this.prevYear = function() {
        this.year -=1;
        this.initYear(this.year);

        return this;
    };

};