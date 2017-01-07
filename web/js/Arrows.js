var Arrows = function(ctx, width, height) {

	var self = this;

	this.ctx       = ctx;
	this.timeStart = null;
	this.direction = null;
	this.congruent = null;

	this.imgWidth  = width;
	this.imgHeight = height;

	this.x = Math.round(width/4);
	this.yTop = 0;
	this.yBottom = Math.round(height/2);

	this.results = (localStorage.results) ? JSON.parse(localStorage.results) : [];

	this.getImage = function(src) {
		var image = new Image();
		image.onload = function () {
			self.ctx.drawImage(image, 0, 0);
			self.ctx.clearRect(0, 0, image.width, image.height);
		};
		image.src = src;
		return image;
	};

	this.feedback = function (e)
	{
		var timeEnd = new Date().getTime();
		var keycode = null; if (window.event) { keycode = window.event.keyCode; } else if (e) { keycode = e.which; }

	    if (keycode === 37 || keycode === 39)
	    {
	    	var response = {
	    		"congruent": self.congruent,
	    		"ok"       : ((keycode === 37 && self.direction === "left") || (keycode === 39 && self.direction === "right")),
	    		"time"     : (timeEnd - self.timeStart) 
	    	};
	    	self.addResult(response);

	    	alert(((response.ok) ? "right":"wrong") +  " (" + ((response.congruent)?"congruent":"not congruent") + ")" + ": " + response.time + "ms.");

	    	self.timeStart = null;
	    	self.direction = null;
	    	self.congruent = null;
	    	document.removeEventListener("keydown", self.feedback);

			setTimeout(function(){
		    	self.nextSequence();
		    }, 1000);
	    }
	};

	this.sequence = function(first, second, t1, t2, t3, t4, position) {

		var x = this.x;
		var y = (position === 'top') ? this.yTop : this.yBottom;

		var width  = Math.round(this.imgWidth / 2);
		var height = Math.round(this.imgHeight / 2);

		var imgFirst  = (first  === "left") ? this.smLeft  : this.smRight;
		var imgSecond = (second === "left") ? this.bigLeft : this.bigRight;
		
		this.direction = second;
		this.congruent = (first === second);

		setTimeout(function(){
			self.ctx.drawImage(imgFirst, 0, 0, self.imgWidth, self.imgHeight, x, y, width, height);
			setTimeout(function() {
				self.ctx.clearRect(0, 0, self.imgWidth, self.imgHeight);
				setTimeout(function(){
					self.ctx.drawImage(imgSecond, 0, 0, self.imgWidth, self.imgHeight, x, y, width, height);
					setTimeout(function() {
						self.ctx.clearRect(0, 0, self.imgWidth, self.imgHeight);
						self.timeStart = new Date().getTime();
						document.addEventListener("keydown", self.feedback);
					}, t4);
				}, t3);
			}, t2);
		}, t1);
	};

	this.leftOrRight = function() {
		return (Math.round(Math.random()) === 1)?"left":"right";
	};

    this.topOrBottom = function() {
        return (Math.round(Math.random()) === 1)?"top":"bottom";
    };

	this.nextSequence = function() {
		var first    = this.leftOrRight();
		var second   = this.leftOrRight();
		var position = this.topOrBottom();

		this.sequence(first, second, 700, 17, 67, 150, position);
	};

	this.addResult = function(result) {
		this.results.push(result);
	    localStorage.results = JSON.stringify(this.results);
	};

	this.saveResults = function() {
		var content = "result;congruent;time\n";

		for(var key in self.results) {
			var result = self.results[key];
			content += result.ok+";"+result.congruent+";"+result.time+"\n";
		}

		var dd = document.createElement('a');
		dd.setAttribute('href', 'data:application/octet-stream;charset=utf-8,' + escape(content));
		dd.setAttribute('download', 'results.csv');
		dd.click();
	};

	this.reset = function() {
		this.results = [];
		localStorage.results = JSON.stringify(this.results);
	};

	this.smRight  = this.getImage("./img/sm-right.png");
	this.smLeft   = this.getImage("./img/sm-left.png");
	this.bigRight = this.getImage("./img/big-right.png");
	this.bigLeft  = this.getImage("./img/big-left.png");
};