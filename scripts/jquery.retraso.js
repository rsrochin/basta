/**
 * jQuery Retraso - Una Función de Retraso
 * Copyright (c) 2009 Alfredo Juarez | http://asksoft.org
 *
 */


$.fn.retraso = function( time, name ) {

    return this.queue( ( name || "fx" ), function() {
        var self = this;
        setTimeout(function() { $.dequeue(self); } , time );
    } );

};
