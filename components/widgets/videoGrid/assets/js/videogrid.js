(function ($, Handlebars, moment){
  'use strict';
  $(function (){

    /**
     * Paginator Class
     */
    var Paginator = function (total, active){
      var that = this;
      if (total < 0){
        total = 0;
      }
      if (active < 0){
        active = 0;
      }
      if (active > total - 1){
        active = total - 1;
      }
      /* generators */
      this.total = total;
      this.active = active;
      this.middlePagesAmount = 3;
      this.firstPagesAmount = 3;
      this.lastPagesAmount = 3;
      /* derivatives */
      this.firstPages = [];
      this.middlePages = [];
      this.lastPages = [];

      this.hasPrev = function(){
        return that.active !== 0;
      }
      this.hasPrevSpacer = function(){
        if (that.firstPages.length === 0 || that.lastPages.length === 0){
          return false;
        }
        if (that.lastPages[0].index - that.firstPages[that.firstPages.length-1].index <= 1){
          return false;
        }
        if (that.middlePages.length === 0){
          return that.lastPages[0].index - that.firstPages[that.firstPages.length-1].index > 1;
        }
        return that.middlePages[0].index - that.firstPages[that.firstPages.length-1].index > 1;
      }
      this.hasNext = function(){
        return that.active !== that.total - 1;
      }
      this.hasNextSpacer = function(){
        if (that.firstPages.length === 0 || that.lastPages.length === 0){
          return false;
        }
        if (that.lastPages[0].index - that.firstPages[that.firstPages.length-1].index <= 1){
          return false;
        }
        if (that.middlePages.length === 0){
          return false;
        }
        return that.lastPages[0].index - that.middlePages[that.middlePages.length-1].index > 1;
      }
      this.getPrev = function(){
        return that.active - 1;
      }
      this.getNext = function(){
        return that.active + 1;
      }

      var buildItem = function(page){
        return {
          index: page,
          title: page + 1,
          active: page === that.active
        };
      }

      this.build = function(){
        that.firstPages = [];
        that.middlePages = [];
        that.lastPages = [];

        var i, amount = Math.min(that.firstPagesAmount, that.total);
        for (i = 0; i < amount; i++){
          that.firstPages.push(buildItem(i));
        }
        amount = Math.min(that.lastPagesAmount, Math.max(that.total - that.firstPages.length, 0));
        for (i = amount - 1; i >= 0; i--){
          that.lastPages.push(buildItem(that.total - 1 - i));
        }
        var middleStart = that.active - Math.floor((that.middlePagesAmount - 1) / 2);
        middleStart = Math.max(middleStart, that.firstPages.length);
        var middleEnd = that.active + Math.ceil((that.middlePagesAmount - 1) / 2);
        middleEnd = Math.min(middleEnd, that.total - 1 - that.lastPages.length);
        if (middleEnd >= middleStart){
          for (i = middleStart; i <= middleEnd; i++){
            that.middlePages.push(buildItem(i));
          }
        }
      }
      this.build();
    };

    /**
     * ModeManager Class
     */
    var ModeManager = function (mode, direction, pageSize) {
      var that = this;
      if (mode !== 'views' && mode !== 'added_at'){
        mode = 'views'
      }
      if (direction !== 'asc' && direction !== 'desc'){
        direction = 'desc'
      }
      if (pageSize < 0){
        pageSize = 0;
      }
      /* generators */
      this.mode = mode;
      this.direction = direction;
      this.pageSize = pageSize;
      this.modes = [];
      this.pageSizes = [];

      this.build = function() {
        that.modes = [];
        that.pageSizes = [];
        var modes = [
          ['views', 'views'],
          ['added_at', 'added']
        ];
        var directions = [
          ['desc', '<span class="glyphicon glyphicon-sort-by-attributes-alt"></span>'],
          ['asc', '<span class="glyphicon glyphicon-sort-by-attributes"></span>']
        ];
        modes.forEach(function (mode) {
          var hide = false;
          directions.forEach(function (direction) {
            that.modes.push({
              mode: mode[0],
              direction: direction[0],
              title: (hide ? '' : mode[1] + ' ') + direction[1],
              active: mode[0] === that.mode && direction[0] === that.direction
            });
            hide = true;
          });
        });
        [12, 24, 48].forEach(function (pageSize) {
          that.pageSizes.push({
            pageSize: pageSize,
            title: pageSize,
            active: pageSize === that.pageSize
          });
        });
      };
      this.build();
    };

    Handlebars.registerHelper('dateTimeFormatFrom', function (dateTimeInput) {
      return moment(dateTimeInput).from();
    });
    Handlebars.registerHelper('dateTimeFormatCustom', function (dateTimeInput, format) {
      return moment(dateTimeInput).format(format);
    });
    Handlebars.registerHelper('durationFormat', function (durationInput) {
      var duration = moment.duration(durationInput, 'seconds');
      var output = duration.seconds().toString();
      if (output.length === 1){
        output = "0" + output;
      }
      output = duration.minutes().toString() + ":" + output;
      if (output.length === 4){
        output = "0" + output;
      }
      var hours = duration.hours();
      if (hours > 0) {
        output = hours.toString() + ":" + output;
      }
      return output;
    });



    $('.js--video-grid').each(function (){
      var $container = $(this);
      var $navigationNode = $container.find('.js--navigation-node');
      var $videoGridNode = $container.find('.js--grid-node');
      var navigationTemplate = Handlebars.compile($container.find('.js--navigation-tpl').html());
      var gridTemplate = Handlebars.compile($container.find('.js--grid-tpl').html());


      var app = {
        core: {
          xhr: null,
          paginator: null,
          modeManager: null
        },
        data: {
          pageCount: 0,
          pageSize: 0,
          mode: '',
          direction: '',
          page: 0,
          videos: []
        },
        requestData: {
          pageSize: 0,
          mode: '',
          direction: '',
          page: 0
        },
        dipatcher: {
          run: function (){},
          refresh: function (){},
          renderNavigation: function (){},
          renderGrid: function (){},
          fetchData: function (){}
        }
      };

      app.data.pageCount = $container.data('page-count');
      app.data.pageSize = $container.data('page-size');
      app.data.mode = $container.data('mode');
      app.data.direction = $container.data('direction');
      app.data.page = $container.data('page');
      app.core.paginator = new Paginator(app.data.pageCount, app.data.page);
      app.core.modeManager = new ModeManager(app.data.mode, app.data.direction, app.data.pageSize);
      app.requestData.pageSize = app.data.pageSize;
      app.requestData.mode = app.data.mode;
      app.requestData.direction = app.data.direction;
      app.requestData.page = app.data.page;

      app.dipatcher.run = function (){
        app.dipatcher.renderNavigation();
        app.dipatcher.fetchData();
      };

      app.dipatcher.renderNavigation = function (){
        app.core.paginator.active = app.data.page;
        app.core.paginator.build();
        app.core.modeManager.pageSize = app.data.pageSize;
        app.core.modeManager.mode = app.data.mode;
        app.core.modeManager.direction = app.data.direction;
        app.core.modeManager.build();
        $navigationNode.html(navigationTemplate({
          paginator: app.core.paginator,
          modeManager: app.core.modeManager
        }));
        $navigationNode.find('a').click(function (){
          var action = $(this).data('action');
          if (!action){
            return;
          }
          switch (action){
            case 'page':
              app.requestData.page = $(this).data('page');
              break;
            case 'mode':
              app.requestData.mode = $(this).data('mode');
              app.requestData.direction = $(this).data('direction');
              break;
            // case 'page-size':
            //   app.requestData.pageSize = $(this).data('page-size');
            //   break;
          }
          app.dipatcher.fetchData();
        });
      };

      app.dipatcher.renderGrid = function (){
        $videoGridNode.html(gridTemplate({
          videos: app.data.videos
        }));
      };

      app.dipatcher.fetchData = function (){
        var data = {
          mode: app.requestData.mode,
          direction: app.requestData.direction,
          page_size: app.requestData.pageSize,
          page: app.requestData.page
        };
        if (app.requestData.page - app.data.page === 1 && app.data.videos.length){
          data.offset_id = app.data.videos[app.data.videos.length - 1].id;
        }
        // if (app.requestData.page - app.data.page === -1 && app.data.videos.length){
        //   data.offset_id = app.data.videos[0].id;
        //   if (data.direction === 'asc'){
        //     data.direction = 'desc';
        //   }else{
        //     data.direction = 'asc';
        //   }
        // }
        if (app.core.xhr !== null && app.core.xhr.readyState !== 0 && app.core.xhr.readyState !== 4){
          app.core.xhr.abort();
        }
        app.core.xhr = $.ajax({
          url: '/api/video',
          type: "get",
          dataType: 'json',
          data: data,
          success: function (videos){
            app.data.videos = videos;
            app.data.pageSize = app.requestData.pageSize;
            app.data.mode = app.requestData.mode;
            app.data.direction = app.requestData.direction;
            app.data.page = app.requestData.page;
            app.dipatcher.renderNavigation();
            app.dipatcher.renderGrid();
          },
          error: function (jqXHR, textStatus, errorThrown){
            if (jqXHR.statusText !== "abort"){
              alert("error fetching data. " + textStatus + ": " + JSON.stringify(errorThrown));
            }
          }
        });
      };

      app.dipatcher.run();
    });
  });
})(jQuery, Handlebars, moment);
