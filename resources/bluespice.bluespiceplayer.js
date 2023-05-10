(function( mw, $, d, undefined ) {
    $( function() {
      $( '.bs-video' ).each( function() {
          this.autoplay = mw.user.options.get( 'bs-showtime-pref-autostart' );
          new Plyr( this );
      } );
    } );
})( mediaWiki, jQuery, document );