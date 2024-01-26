define([
	'jquery',
	'countdownTimer'
	], function ($) {
    	"use strict";
        return function (config) {
            $(".future_date").countdowntimer({
				startDate : config.startDate,
				dateAndTime : config.dateAndTime,
				size : "lg",
				regexpMatchFormat: "([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})",
	// 			regexpReplaceWith: "$1 days $2 hours $3 minutes $4 seconds REMAINING"        
				regexpReplaceWith: "$1<sup>days</sup> / $2<sup>hours</sup> / $3<sup>minutes</sup> / $4<sup>seconds</sup>"
		    }); 
		}
});