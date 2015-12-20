(function() {
    var lastTime = 0;
    var vendors = ['ms', 'moz', 'webkit', 'o'];
    for(var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
        window.requestAnimationFrame = window[vendors[x]+'RequestAnimationFrame'];
        window.cancelAnimationFrame = window[vendors[x]+'CancelAnimationFrame']
            || window[vendors[x]+'CancelRequestAnimationFrame'];
    }

    if (!window.requestAnimationFrame)
        window.requestAnimationFrame = function(callback, element) {
            var currTime = new Date().getTime();
            var timeToCall = Math.max(0, 16 - (currTime - lastTime));
            var id = window.setTimeout(function() { callback(currTime + timeToCall); },
                timeToCall);
            lastTime = currTime + timeToCall;
            return id;
        };

    if (!window.cancelAnimationFrame)
        window.cancelAnimationFrame = function(id) {
            clearTimeout(id);
        };
}());

(function () {
    var sprite = function (options) {

        var that = {},
            frameIndex = 0,
            tickCount = 0,
            ticksPerFrame = options.ticksPerFrame || 0,
            numberOfFrames = options.numberOfFrames || 1;

        that.frames = numberOfFrames;
        that.context = options.context;
        that.width = options.width;
        that.height = options.height;
        that.image = options.image;
        that.image.addEventListener("load", that.gameLoop);
        that.req = false;

        that.gameLoop = function () {
            that.req = window.requestAnimationFrame(that.gameLoop);

            that.context.canvas.width = that.image.naturalWidth / that.frames;
            that.context.canvas.height = that.image.naturalHeight;

            that.update();
            that.render();
        };

        that.stop = function() {
            cancelAnimationFrame(that.req);
        };

        that.update = function () {

            tickCount += 1;

            if (tickCount > ticksPerFrame) {

                tickCount = 0;

                // If the current frame index is in range
                if (frameIndex < numberOfFrames - 1) {
                    // Go to the next frame
                    frameIndex += 1;
                } else {
                    frameIndex = 0;
                }
            }
        };

        that.render = function () {

            // Clear the canvas
            that.context.clearRect(0, 0, that.image.naturalWidth, that.image.naturalHeight);

            // Draw the animation
            that.context.drawImage(
                that.image,
                frameIndex * that.image.naturalWidth / numberOfFrames,
                0,
                that.image.naturalWidth / numberOfFrames,
                that.image.naturalHeight,
                0,
                0,
                that.image.naturalWidth / numberOfFrames,
                that.image.naturalHeight);
        };

        return that;
    }

    window.createAnimation = sprite;
} ());