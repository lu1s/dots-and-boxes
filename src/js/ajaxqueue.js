/*
 * jQuery.ajaxQueue - A queue for ajax requests
 * 
 * (c) 2011 Corey Frang
 * Dual licensed under the MIT and GPL licenses.
 *
 * Requires jQuery 1.5+
 */
(function(a){var b=a({});a.ajaxQueue=function(c){function g(b){d=a.ajax(c).then(b,b).done(e.resolve).fail(e.reject)}var d,e=a.Deferred(),f=e.promise();b.queue(g),f.abort=function(h){if(d)return d.abort(h);var i=b.queue(),j=a.inArray(g,i);j>-1&&i.splice(j,1),e.rejectWith(c.context||c,[f,h,""]);return f};return f}})(jQuery)