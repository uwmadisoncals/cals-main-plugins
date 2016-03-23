/* *********************************************************************************
 * Weaver X Theme Support JavaScript support Library
 *
 * Author: WeaverTheme - www.weavertheme.com
 * @version 1.0
 * @license GNU Lesser General Public License, http://www.gnu.org/copyleft/lesser.html
 * @author  Bruce Wampler
 *
 * Notes - this library requires jQuery to be loaded
 *  this library was cobbled together over a long period of time, so it contains a
 *  bit of a jumble of straight JavaScript and jQuery calls. So it goes. It works.
 *
 *
 ************************************************************************************* */


jQuery(document).ready(function($) {		// self-defining function
    // Tabs
	$('.wvr-tabs-nav').delegate('span:not(.wvr-tabs-current)', 'click', function() {
		$(this).addClass('wvr-tabs-current').siblings().removeClass('wvr-tabs-current')
		.parents('.wvr-tabs').find('.wvr-tabs-pane').hide().eq($(this).index()).show();
	});
	$('.wvr-tabs-pane').hide();
	$('.wvr-tabs-nav span:first-child').addClass('wvr-tabs-current');
	$('.wvr-tabs-panes .wvr-tabs-pane:first-child').show();

});
