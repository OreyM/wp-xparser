//TO DELETE
document.addEventListener('DOMContentLoaded', function(){
    console.log('lets go');
});
//END TO DELETE

$(document).ready(function(){

    //Test jQuery
    console.log('jQuery work! ' + jQuery.fn.jquery);

    //Init and settings owl carousel
    /*
    Owl offpage
    https://owlcarousel2.github.io/

    Options
    https://owlcarousel2.github.io/OwlCarousel2/docs/api-options.html
    Classes
    https://owlcarousel2.github.io/OwlCarousel2/docs/api-classes.html
    Events
    https://owlcarousel2.github.io/OwlCarousel2/docs/api-events.html
     */
    $('.owl-carousel').owlCarousel({
        margin: 10, // Type: Number. margin-right(px) on item
        loop: true, // Infinity loop. Duplicate last and first items to get loop illusion
        center: false,  // Center item. Works well with even an odd number of items
        mouseDrag: true, // Mouse drag enabled
        touchDrag: true, // Touch drag enabled
        pullDrag: true, // Stage pull to edge
        freeDrag: false, // Item pull to edge
        stagePadding: 0, // Type: Number. Padding left and right on stage (can see neighbours)
        merge: false, // Merge items. Looking for data-merge='{number}' inside item
        mergeFit: true, // Fit merged items if screen is smaller than items value
        autoWidth: false, // Set non grid content. Try using width style on divs
        startPosition: 0, // Type: Number/String. Start position or URL Hash string like '#id'
        URLhashListener: false, // Listen to url hash changes. data-hash on items is required
        nav: true, // Show next/prev buttons
        rewind: true, // Go backwards when the boundary has reached.
        navText : ['&#x27;next&#x27;','&#x27;prev&#x27;'], // Type: Array. HTML allowed.
        slideBy: 1, // Type: Number/String
                    // Navigation slide by x. 'page' string can be set to slide by page
        slideTransition: '', // Type: String.
                            // You can define the transition for the stage
                            // you want to use eg. linear
        dots : true, // Show dots navigation
        dotsEach : false, // Type: Number/Boolean. Show dots each x item
        dotData : false, // Used by data-dot content
        lazyLoad: false, // Lazy load images. data-src and data-src-retina for highres.
                        // Also load images into background inline style if element is not <img>
        lazyLoadEager: 0, // Type: Number. Eagerly pre-loads images to the right (and left
                          // when loop is enabled) based on how many items you want to preload
        autoplay : true, // Autoplay
        autoplayTimeout: 5000, // Type: Number. Autoplay interval timeout, ms.
        autoplayHoverPause : false, // Pause on mouse hover.
        smartSpeed: 250, // Type: Number. Speed Calculate. More info to come..
        fluidSpeed : 600, // Type: Number. Speed Calculate. More info to come..
        autoplaySpeed : false, // Type: Number/Boolean. Autoplay speed.
        navSpeed : false, // Type: Number/Boolean. Navigation speed.
        dotsSpeed : false, // Type: Number/Boolean. Pagination speed.
        dragEndSpeed : false, // Type: Number/Boolean. Drag end speed.
        callbacks: true, // Enable callback events.
        responsive: {       // Type: Object
                            // Object containing responsive options. Can be set to false to remove
                            // responsive capabilities.
                            // the number of items you want to see on the screen.
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            768: {
                items: 3
            },
            1000: {
                items: 4
            },
            1280: {
                items: 5
            }
        },
        responsiveRefreshRate: 200, // Type: Number. Responsive refresh rate.
        responsiveBaseElement: window, // Type: DOM element. Set on any DOM element.
                                      // If you care about non responsive browser (like ie8) then
                                      // use it on main wrapper. This will prevent from crazy resizing.
        video: false, // Enable fetching YouTube/Vimeo/Vzaar videos.
        videoHeight: false, // Type: Number/Boolean. Set height for videos.
        videoWidth: false, // Type: Number/Boolean. Set width for videos.
        animateOut: false, // Type: String/Boolean. Class for CSS3 animation out.
        animateIn: false, // Type: String/Boolean. Class for CSS3 animation in.
        fallbackEasing: 'swing', // Type: String. Easing for CSS2 $.animate
        info: false, // Type: Function. Callback to retrieve basic information (current item/pages/widths).
                     // Info function second parameter is Owl DOM object reference.
        nestedItemSelector: false, // Type: String/Class. Use it if owl items are deep nested inside
                                   // some generated content. E.g 'youritem'. Dont use dot before class name.
        itemElement: 'div', // Type: String. DOM element type for owl-item.
        stageElement: 'div', // Type: String. DOM element type for owl-stage.
        navContainer: false, // Type: String/Class/ID/Boolean. Set your own container for nav.
        dotsContainer: false, // Type: String/Class/ID/Boolean. Set your own container for nav.
        checkVisible: true, // If you know the carousel will always be visible you can set
                            // `checkVisibility` to `false` to prevent the expensive browser layout
                            // forced reflow the $element.is(':visible') does.
    });
});

/*
CUSTOM JS SCRIPTS
 */