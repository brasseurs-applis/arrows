var Arrows = function(ctx, width, height, callback) {

    var self = this;

    this.ctx       = ctx;
    this.timeStart = null;

    this.imgWidth  = width;
    this.imgHeight = height;

    this.callback = callback;

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

    this.getKeyCode = function(e) {
        var keyCode = null;
        if (window.event) {
            keyCode = window.event.keyCode;
        } else if (e) {
            keyCode = e.which;
        }
        return keyCode;
    };

    this.feedback = function (e)
    {
        var timeEnd = new Date().getTime();
        var keyCode = self.getKeyCode(e);

        if (keyCode === 37 || keyCode === 39)
        {
            document.removeEventListener("keydown", self.feedback);

            self.callback((keyCode === 37) ? 'left' : 'right', self.timeStart, timeEnd);
            self.timeStart = null;
        }
    };

    this.sequence = function(first, second, t1, t2, t3, t4, position) {

        var x = Math.round(this.imgWidth / 4);
        var y = (position === 'top') ? 0 : Math.round(this.imgHeight / 2);

        var width  = Math.round(this.imgWidth / 2);
        var height = Math.round(this.imgHeight / 2);

        var imgFirst  = (first  === "left") ? this.smLeft  : this.smRight;
        var imgSecond = (second === "left") ? this.bigLeft : this.bigRight;

        setTimeout(function(){
            if (first !== null) {
                self.ctx.drawImage(imgFirst, 0, 0, self.imgWidth, self.imgHeight, x, y, width, height);
            }
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

    this.smRight  = this.getImage("/img/sm-right.png");
    this.smLeft   = this.getImage("/img/sm-left.png");
    this.bigRight = this.getImage("/img/big-right.png");
    this.bigLeft  = this.getImage("/img/big-left.png");
};