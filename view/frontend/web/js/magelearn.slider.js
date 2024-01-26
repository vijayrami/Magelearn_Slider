define(
    [
    'jquery',
    'slick',
    'slickanimation',
    'jquery-ui-modules/widget',
    'matchMedia',
    'underscore'
    ], function ($) {
        "use strict";

        $.widget(
            'mage.magelearnSlider', {
                options: {},
				
				_create: function (options) {
                    this.initialize();
                },
                
				
                initialize: function () {
                    var slideWrapper = $('.main-slider'),
					    iframes = slideWrapper.find('.embed-player'),
					    lazyImages = slideWrapper.find('.slide-image'),
					    lazyCounter = 0,
					    self = this;

                    $(
                        function () {
							// event listener for detecting that video has ended so slider must go to next slide and
                            window.addEventListener(
                                'message', function (event) {
                                    // if not youtube then do nothing
                                    if ("https://www.youtube.com" !== event.origin || undefined === event.data) {
                                        return;
                                    }

                                    try {
                                        var data = JSON.parse(event.data);

                                        // we are not interested in events other than onStateChange
                                        if ('onStateChange' !== data.event) {
                                            return;
                                        }

                                        var playingFinished = 0;

                                        if (data.info === playingFinished) {
                                            var slick = slideWrapper.slick;

                                            // this check is for loop if there is only one slide (video slide)
                                            if (slick.getSlideCount() === 1) {
												var currentSlide = slick.find(".slick-current");
                                                this.playVideo(currentSlide, slick);
                                            }

                                            slick.slickPlay();
                                            slick.slickNext();
                                        }
                                    } catch(e) {
                                    }
                                }
                            );
                            // init is fired after first initialization (by slick library)
                            $(slideWrapper).on(
                                'init', function (event, slick) {
                                    slick = $(slick.$slider);
								    setTimeout(function(){
								      self.playPauseVideo(slick,"play");
								    }, 1000);
								    self.resizePlayer(iframes, 16/9);
                                }
                            );

                            $(slideWrapper).on(
                                'beforeChange', function (event, slick) {
									slick = $(slick.$slider);
    								self.playPauseVideo(slick,"pause");
                                }
                            );

                            $(slideWrapper).on(
                                'afterChange', function (event, slick) {
									slick = $(slick.$slider);
    								self.playPauseVideo(slick,"play");
                                }
                            );

                            $(slideWrapper).on(
                                'lazyLoaded', function (event, slick, image, imageSource) {
									lazyCounter++;
								    if (lazyCounter === lazyImages.length){
								      lazyImages.addClass('show');
								      // slideWrapper.slick("slickPlay");
								    }
								    $(event.currentTarget).find('source').each(function(i, source) {
							            var $source = $(source);
							            $source.attr('srcset', $source.data('lazy-srcset'));
							        });
                                }
                            );
                            
                            $(slideWrapper).on(
                                'click', '.slick-slide', function () {
                                    var url = $(this).data('url');

                                    if ('' !== url) {
                                        window.location.href = $(this).data('url');
                                    }
                                }
                            );
							
							$(window).on(
                                'resize.slickVideoPlayer', function () {
                                    self.resizePlayer(iframes, 16/9);
                                }
                            );

                            // this is main entry for running slick library
                           var slick = $(slideWrapper).not('.slick-initialized');

                           slick.slick(self.options).slickAnimation();
                           
                           // iframes must be loaded before we can play the video
                            slick.find('iframe').on(
                                'load', function () {
                                    // this timeout is here because even if iframe was loaded then I got error on sending command to the player
                                    // seems like there was still something to load - maybe from youtube library
                                    var iframe = $(this);

                                    setTimeout(
                                        function () {
                                            iframe.addClass('loaded');
                                        }, 100
                                    );
                                }
                            );
                           
                        }
                    );
                },

                /**
                 * Sends command to the player (youtube / vimeo)
                 *
                 * @param player
                 * @param command
                 */
                postMessageToPlayer: function (player, command) {
                    if (player === null || command === null) {
                        return;
                    }

                    player.contentWindow.postMessage(JSON.stringify(command), '*');
                },

                /**
                 * Checks if slide is video (by looking for iframe)
                 *
                 * @param slide
                 *
                 * @return {*}
                 */
                isVideo: function (slide) {
                    return slide.find('iframe').length;
                },

                /**
                 * Play video from provided slide. Slick object is needed to stop slider from changin slides.
                 *
                 * @param slide
                 * @param slick
                 */
                playVideo: function (slide, slick) {
                    slick.slickPause();

                    var iframe = slide.find('iframe');

                    if (iframe.hasClass('loaded')) {
                        // youtube api requires frame with id
                        iframe.prop('id', 'api_youtube_player');
                        this.sendPlayCommandToIframe(slide, iframe.get(0));
                    } else {
                        // wait till iframe is loaded, loading iframes is done by another piece of code (just after starting slick - bottom ot the code)
                        setTimeout(
                            function () {
                                this.playVideo(slide, slick);
                            }, 100
                        );
                    }
                },

                /**
                 * @param slide
                 * @param iframe
                 */
                sendPlayCommandToIframe: function (slide, iframe) {
                    // find kind of video
                    if (slide.hasClass('youtube')) {
                        // inform iframe with youtube that you want to listen messages from it
                        this.postMessageToPlayer(
                            iframe, {
                                event: "listening",
                                id: "api_youtube_player",
                                channel: "widget"
                            }
                        );

                        // enable more detailed event name than only "infoDelivery" (data.event)
                        this.postMessageToPlayer(
                            iframe, {
                                event: "command",
                                func: "addEventListener",
                                args: ["onStateChange"],
                                id: "api_youtube_player",
                                channel: "widget"
                            }
                        );

                        this.postMessageToPlayer(
                            iframe, {
                                'event': 'command',
                                'func': 'mute'
                            }
                        );

                        this.postMessageToPlayer(
                            iframe, {
                                'event': 'command',
                                'func': 'playVideo'
                            }
                        );
                    } else if (slide.hasClass('vimeo')) {
                        var startTime = slide.data('video-start');

                        if ((startTime != null && startTime > 0 ) && !slide.hasClass('started')) {
                            slide.addClass('started');
                            this.postMessageToPlayer(
                                iframe, {
                                    'method': 'setCurrentTime',
                                    'value' : startTime
                                }
                            );
                        }

                        this.postMessageToPlayer(
                            iframe, {
                                'method': 'play',
                                'value' : 1
                            }
                        );
                    } else if (slide.hasClass('video')) {
                        var video = slide.children('video').get(0);

                        if (video != null) {
                            video.play();
                        }
                    }
                },

				// When the slide is changing
				playPauseVideo: function (slick, control){
				  var currentSlide, slideType, startTime, player, video;
				
				  currentSlide = slick.find(".slick-current");
				  console.log(currentSlide.attr("class"));
				  slideType = currentSlide.attr("class").split(" ")[1];
				  player = currentSlide.find("iframe").get(0);
				  startTime = currentSlide.data("video-start");
				
				  if (slideType === "vimeo") {
				    switch (control) {
				      case "play":
				        if ((startTime != null && startTime > 0 ) && !currentSlide.hasClass('started')) {
				          currentSlide.addClass('started');
				          this.postMessageToPlayer(player, {
				            "method": "setCurrentTime",
				            "value" : startTime
				          });
				        }
				        this.postMessageToPlayer(player, {
				          "method": "play",
				          "value" : 1
				        });
				        break;
				      case "pause":
				        this.postMessageToPlayer(player, {
				          "method": "pause",
				          "value": 1
				        });
				        break;
				    }
				  } else if (slideType === "youtube-sound") {
				    switch (control) {
				      case "play":
				        this.postMessageToPlayer(player, {
				          "event": "command",
				          // "func": "mute"
				        });
				        this.postMessageToPlayer(player, {
				          "event": "command",
				          "func": "playVideo"
				        });
				        break;
				      case "pause":
				        this.postMessageToPlayer(player, {
				          "event": "command",
				          "func": "pauseVideo"
				        });
				        break;
				    }
				  }  else if (slideType === "youtube") {
				    switch (control) {
				      case "play":
				        this.postMessageToPlayer(player, {
				          "event": "command",
				          "func": "mute"
				        });
				        this.postMessageToPlayer(player, {
				          "event": "command",
				          "func": "playVideo"
				        });
				        break;
				      case "pause":
				        this.postMessageToPlayer(player, {
				          "event": "command",
				          "func": "pauseVideo"
				        });
				        break;
				    }
				  } else if (slideType === "video" || slideType === "html") {
				    video = currentSlide.children("video").get(0);
				    if (video != null) {
				      if (control === "play"){
				        video.play();
				      } else {
				        video.pause();
				      }
				    }
				  }
				},

               	// Resize player
				resizePlayer: function (iframes, ratio) {
				  if (!iframes[0]) return;
				  var win = $(".main-slider"),
				      width = win.width(),
				      playerWidth,
				      height = win.height(),
				      playerHeight,
				      ratio = ratio || 16/9;
				
				  iframes.each(function(){
				    var current = $(this);
				    if (width / ratio < height) {
				      playerWidth = Math.ceil(height * ratio);
				      current.width(playerWidth).height(height).css({
				        left: (width - playerWidth) / 2,
				         top: 0
				        });
				    } else {
				      playerHeight = Math.ceil(width / ratio);
				      current.width(width).height(playerHeight).css({
				        left: 0,
				        top: (height - playerHeight) / 2
				      });
				    }
				  });
				},
            }
        );

        return $.mage.magelearnSlider;
    }
);
