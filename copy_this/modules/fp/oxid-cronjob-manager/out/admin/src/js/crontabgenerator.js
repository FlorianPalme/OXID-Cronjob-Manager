/*
 *  Crontab-Generator
 *
 *  Made by Florian Palme
 *  Under MIT License
 */
;( function( $, window, document, undefined ) {

    "use strict";

    // Create the defaults once
    var pluginName = "crontabGenerator",
        defaults = {};

    // The actual plugin constructor
    function Plugin ( element, options ) {
        this.element = element;

        this.settings = $.extend( {}, defaults, options );
        this._defaults = defaults;
        this._name = pluginName;
        this.html = {};
        this.init();
    }

    // Avoid Plugin.prototype conflicts
    $.extend( Plugin.prototype, {
        init: function() {
            var self = this;

            self.buildHTML();
        },

        buildHTML: function(){
            var self = this;

            var $cgWrapper = self.html.cgWrapper =
                $( self.element ).wrap( '<div class="crontabgenerator_wrapper" />' ).parent();

            $( self.element ).hide();

            /********
             * MINUTE
             *******/
            var $cgMinuteWrapper = $( '<div class="box minute" />' ),

                // every minute
                $cgMinuteEvery = $cgMinuteWrapper.append( '<div class="every">' +
                    '<input type="radio" name="minute[every]" />' +
                    '</div>').find( '[input]' );

        }
    } );

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn[ pluginName ] = function( options ) {
        return this.each( function() {
            if ( !$.data( this, "plugin_" + pluginName ) ) {
                $.data( this, "plugin_" +
                    pluginName, new Plugin( this, options ) );
            }
        } );
    };

} )( jQuery, window, document );
