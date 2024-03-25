var config = {
    "map": {
        "*": {
            "slick": "Magelearn_Slider/js/vendor/slick.min",
            "slickanimation": "Magelearn_Slider/js/vendor/slick-animation.min",
            "magelearn.slider": "Magelearn_Slider/js/magelearn.slider",
            "countdownTimer": "Magelearn_Slider/js/jquery.countdownTimer",
            "countdownTimerInit": "Magelearn_Slider/js/countdownTimerInit"
        }
    },
    "shim": {
        "magelearn.slider"    : ["slick","slickanimation"],
        "countdownTimer" : ["jquery"],
        "countdownTimerInit" : ["jquery","countdownTimer"]
    }
};
