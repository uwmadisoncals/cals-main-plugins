var portfolio_slideshow = window.portfolio_slideshow || {};

(function( window, $, undefined ) {

	var file_frame;
	
	portfolio_slideshow = {

		i18n : portfolio_slideshow_admin_i18n,

		order : '',

		el : {
			meta_box    : $( '#portfolio_slideshow_uploader_meta_box' ),
			slides_list : ''
		},

		reindex : function() {
			this.el.meta_box.find( 'li.portfolio-slideshow-draggable-item' ).each( function ( i, el ) {
				$(el).find( '.portfolio-slideshow-slide-footer .slide-index' ).text( ( i + 1 ) )
			})
		},

		update : function() {
			this.order = this.el.slides_list.sortable( 'toArray', { attribute : 'data-attachment-id' } );
			this.reindex()
		},

		delete : function( slide ) {

			slide.css( 'background', 'rgba(250, 0, 0, 0.55)' ).fadeOut( 600, function() {
				slide.remove()
				portfolio_slideshow.update()
				portfolio_slideshow.toggle_prompt()
			})
		},

		toggle_prompt : function() {
			if ( portfolio_slideshow.el.slides_list.children( '.portfolio-slideshow-draggable-item' ).length ) {
				this.el.meta_box.find( '.portfolio-slideshow-no-slides-prompt' ).hide()
			} else {
				this.el.meta_box.find( '.portfolio-slideshow-no-slides-prompt' ).show()
			}
		},

		setup_sortable : function() {

			this.el.slides_list.sortable({
				items       : '> li.portfolio-slideshow-draggable-item',
				containment : this.el.meta_box,
				cursor      : 'move',
				opacity     : 0.7,
				update      : function( e, ui ) {
					portfolio_slideshow.update()
				}
			})
		},

		init : function() {
			if ( this.el.meta_box.length ) {
				this.el.slides_list = this.el.meta_box.find( 'ol.portfolio-slideshow-metabox-slides' );
				this.el.order_input = this.el.meta_box.find( '#portfolio-slideshow-metabox-slides-order' );

				this.setup_sortable();
				this.update();

				return this;
			}
		}

	}.init();
	
	$( document ).on( 'click', '#portfolio-slideshow-add-slides .button-primary', function(e) {
		
		e.preventDefault()

		if ( undefined !== file_frame ) {
			file_frame.open()
			return;
		}

		file_frame = wp.media.frames.file_frame = wp.media({
			title    : portfolio_slideshow.i18n.strings.add_plural,
			button   : { text : portfolio_slideshow.i18n.strings.add_plural },
			multiple : true
		})

		file_frame.on( 'select', function() {
			var uploaded = file_frame.state().get( 'selection' );

			var template = wp.template( 'portfolio-slideshow-add-slides' );

		 	var data = {
		 		attachments : uploaded.toJSON(),
		 		edit_text   : portfolio_slideshow.i18n.strings.edit_singular,
		 		delete_text : portfolio_slideshow.i18n.strings.delete_singular,
		 	};

		 	$.map( data.attachments, function( attachment, i ) {
		 		attachment.alt = portfolio_slideshow.i18n.strings.slide_singular + ' ' + ( i + 1 );
		 		return attachment;
		 	})

		 	portfolio_slideshow.el.slides_list.append( template( data ) )
		 	portfolio_slideshow.update()
		 	portfolio_slideshow.toggle_prompt()
		})

		file_frame.open()
	})
	
	if ( undefined !== portfolio_slideshow && portfolio_slideshow.el.meta_box.length ) {
		portfolio_slideshow.el.meta_box.on( 'click', 'a.portfolio-slideshow-quick-trash', function(e) {
			e.preventDefault()
			portfolio_slideshow.delete( $(this).parents( '.portfolio-slideshow-draggable-item' ).first() )
		})
	}

	if ( $( '.portfolio-slideshow-tooltip' ).length ) {
		$( '.portfolio-slideshow-tooltip' ).tooltip()
	}

})( window, jQuery );