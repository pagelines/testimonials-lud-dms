/*
ver: 0.3
*/
jQuery(window).load(function(){
	var ludLoop = function(selectors, options){
		this.selectors = selectors;
		this.options = options;
	};
	ludLoop.prototype = {
		defaults: {
			enable_animation: false,
			use_link: false
		},
		init: function() {
			self = this;
			self.options = $.extend({},self.defaults,self.options);
			//grid
			(self.options.enable_animation === false) ? self.masonryGrid() : self.fredGrid();
		},
		masonryGrid: function(){
			self.itemHeight();
			self.selectors.wraper.masonry({
				itemSelector: self.selectors.ludItem.selector,
				isAnimated: true,
				isFitWidth: true,
				columnWidth: self.options.itemWidth + 1
			});
		},
		fredGrid: function(){
			//fred item width
			defItemWidth 	= 465;
			fredWidth = (600 > self.selectors.container.width()) ? 600 : defItemWidth;
			//fredobj for section loop
			defFredObj = {
				responsive: true,
				//height: "variable",
				items:{
					visible: {
						'min'         : 1,
						'max'         : self.options.numslides
					},
					width: fredWidth,
					//height: "variable"
				},
				scroll:{
					items: 1,
					fx: self.options.mode,
					duration: self.options.speed,
					pauseOnHover: self.options.pause_on_hover
				},
				auto:{
					play: self.options.auto,
					timeoutDuration: self.options.pause
				},
				pagination: '',
				prev : {	button  : ''},
				next : { button  : ''}
			}
			//pager
			if(self.options.pager === true) {
				defFredObj.pagination = self.selectors.pager.selector;
				self.selectors.pager.show(0);
				self.selectors.next.css('margin-top', '-=20px');
				self.selectors.prev.css('margin-top', '-=20px');
			}
			//controls (arrows)
			if(self.options.controls === true) {
				defFredObj.next.button = self.selectors.next;
				defFredObj.prev.button = self.selectors.prev;
				self.selectors.next.show(0);
				self.selectors.prev.show(0);
			}
			//initiate fred
			self.selectors.wraper.delay(500, 'carouFredSel').carouFredSel(defFredObj);
			self.itemHeight();
			self.selectors.wraper.trigger('updateSizes');
		},
		itemHeight: function(){
			if(self.options.equal_height === true) {
				var itemHeight = Math.max.apply(null, self.selectors.inner.map(function (){
					return $(this).height();
				}).get());
				self.selectors.inner.css({'min-height' : itemHeight});
			}
		}
	}
	//local
	$.fn.ludLoop = function(selectors, options) {
		return new ludLoop(selectors, options).init();
	};
});