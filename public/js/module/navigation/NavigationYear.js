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

Cash.Models.NavigationYear = Backbone.Collection.extend();


Cash.Views.NavigationYearView = Backbone.View.extend({


    activatedMonth:function(date){
        this.doOnClick && this.doOnClick(date);
    },

    yearChanged: function(year) {
        console.log("Year has changed");
        this.doOnYearChange && this.doOnYearChange(year);
    },
    initialize:function(options) {


        var date = new Date();
        this.year       = new Year(date.getFullYear());
        this.collection = new Cash.Models.NavigationYear(this.year.getMonths());

        this.collection.on('reset',this.render,this);
        this.collection.on('activatedMonth',this.activatedMonth, this);

        options.doOnClick && (this.doOnClick = options.doOnClick);
        options.doOnYearChange && (this.doOnYearChange = options.doOnYearChange);


        this.$el.find('.prev-year a').bind('click', $.proxy(function(e) {
            e.preventDefault();
            this.collection.reset( this.year.prevYear().getMonths());
            $('.current-year a').html(this.year.year);
            this.yearChanged(this.year.year);

        },this));

        this.$el.find('.next-year a').bind('click', $.proxy(function(e) {
            e.preventDefault();
            this.collection.reset( this.year.nextYear().getMonths());
            $('.current-year a').html(this.year.year);
            this.yearChanged(this.year.year);
        },this));
    },
    render : function() {
        this.$el.find('.month-view').remove();
        this.collection.forEach(this.addMonth,this);
    },

    addMonth: function(month){
        var navigationMonthView = new Cash.Views.NavigationMonth({model:month});
        navigationMonthView.render();
        this.$el.append(navigationMonthView.el);
    }

});


Cash.Views.NavigationMonth = Backbone.View.extend({
    tagName:'li',
    template: _.template('<a class="month-view" data-cashmonth="<%= date %>" href="#/cashflow/<%= date %>"><%= name %></a>'),

    events:  {
        'click a':'toggleActive'
    },

    initialize:function(options) {
        this.on("active_month",this.activateMonth, this);

    },
    activateMonth: function(month) {
        var date = new Date(month);

        month = date.getMonth() + 1;
        if(month <= 9) {
            month = '0'+month;
        }
        var month_start = date.getFullYear() + '-' +  month + '-01';
        if (this.model.get('date') === month_start) {
            this.toggleActive();
        }

    },


    toggleActive:function(){
        this.$el.parent().find('li.active').removeClass('active');
        this.$el.toggleClass('active');
        this.model.collection.trigger("activatedMonth",this.model.get('date'));
    },
    render:function(){
        this.$el.html(this.template(this.model.attributes));
    }
}, Backbone.Events);
