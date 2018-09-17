( function() {
	tinymce.PluginManager.add( 'trippy_autocomplete', function( editor, url ) {
		
		var trippyBaseUrl = "https://www.trippy.com";
		
		function triggerAlert(editor, e) {
			if (String.fromCharCode(e.charCode || e.keyCode) === "@") {
				e.preventDefault();
				renderAutocompleterView();
			}
			
		}

		editor.onKeyPress.add(function(editor, e) {
			triggerAlert(editor, e);

		}) ;

			jQuery(function ($) {
				$("#trippy-admin-show-map-details").click(function() {
					$(".trippy-map-list-ol").toggleClass("trippy-admin-display-none");
					
				});
					
			});

			
			// Remove Autocompleter Tool if user presses "ESCAPE"
			jQuery(function ($) {
			$(document).keyup(function(e) {

				  if (e.keyCode == 27) {
					  removeAutocompleterContainerView(true);
				  } 
				});
			});
			

		function renderAutocompleterView() {

			jQuery(function($) {
				
				
				// Find Cursor Position:
				var tinymcePosition = $(editor.getContainer()).position();
				var toolbarPosition = $(editor.getContainer()).find(".mce-toolbar").first();
				
				var nodePosition = $(editor.selection.getNode()).position();
				var textareaTop = 0;
				var textareaLeft = 0;
				
				if (editor.selection.getRng().getClientRects().length > 0) {
				    textareaTop = editor.selection.getRng().getClientRects()[0].top + editor.selection.getRng().getClientRects()[0].height;
				    textareaLeft = editor.selection.getRng().getClientRects()[0].left;
				} else {
				    textareaTop = parseInt($(editor.selection.getNode()).css("font-size")) * 1.3 + nodePosition.top;
				    textareaLeft = nodePosition.left;
				}
				
				var position = $(editor.getContainer()).offset();
				var caretPosition = {
				    top:  tinymcePosition.top + toolbarPosition.innerHeight() + textareaTop,
				    left: tinymcePosition.left + textareaLeft + position.left
				}
				var acPosition = {
						left: caretPosition.left - position.left,
						top: caretPosition.top -8//+ position.top
						
				}
				
				
				
				$("#wp-content-editor-container").append("<div id=\"trippy-autocompleter-container\" style=\" left:" + acPosition.left +"px; top:" + acPosition.top + "px; \">" +
						"<input name=\"placeName\" id=\"trippy-autocompleter-query\" placeholder=\"Enter Place Name\">&nbsp;" +
						"<button class=\"wp-core-ui button-primary\" id=\"trippy-autocompleter-cancel-button\" ><i class=\"fa fa-times-circle-o\"></i></button>" +
				"</div>"
						
				);

				$("#trippy-autocompleter-query").focus();

				$("#trippy-autocompleter-cancel-button").click(function() {
					  removeAutocompleterContainerView(true);
				});

				
				$("#trippy-autocompleter-go-button").click(function() {

					if ($( "#trippy-autocompleter-query" ).val().length > 2) {
						submitAutocompleteForm();
					}

				});


				
				$( "#trippy-autocompleter-query" ).autocomplete({
				      source: function( request, response ) {

				        $.ajax({
				            dataType: "json",
				            type : 'Get',
				            url: trippyBaseUrl + "/api/v2/places/suggest?query=" + $("#trippy-autocompleter-query").val(),
				            success: function(data) {
				              $('#trippy-autocompleter-query').removeClass('ui-autocomplete-loading');  // hide loading image
				              
				              
				            response( $.map( data.response.places, function(item) {
				            	
				            	
				            	return {
				            		label: item.name,
				            		data: item.id
				            	}
				            }));
				          },
				          error: function(data) {
				              $('#trippy-autocompleter-query').removeClass('ui-autocomplete-loading');  
				          }
				        });
				      },
				      minLength: 3,
				      open: function() {
				    	  
				      },
				      close: function() {

				      },
				      focus:function(event,ui) {

				      },
				      select: function( event, ui ) {
							getGooglePlace(ui.item.data);
				      }
				    });

			});
		}

		function removeAutocompleterContainerView(cancelled) {
			if (cancelled) {
				  editor.execCommand('mceInsertContent', false, "@");
			}
			
			jQuery(function ($) {
			$("#trippy-autocompleter-container").remove();
			  editor.focus();
			});
		}
		
		function submitAutocompleteForm() {
			jQuery(function ($) {
				var url = trippyBaseUrl + "/api/v2/places/suggest?query=" + $("#trippy-autocompleter-query").val();

				$("#trippy-autocompleter-cancel-button").html("<i class=\"fa fa-spinner fa-spin\"></i>");
				
				
				$.get( url, function( data ) {
					$( ".result" ).html( data );
					var places = $.parseJSON(data).response.places;

					$("#trippy-autocompleter-results-container").empty();
					for (var i=0; i < places.length; i++) {
						
						if (places[i] && places[i].name) {
							var recordItem = "<div class=\"trippy-autocompleter-result\" data-index=\"" + i + "\" data-source=\"" + places[i].provider + "\" data-id=\"" + places[i].id + "\">" +places[i].name;

							if (places[i].description && places[i].description != "null") {
								recordItem += "<br/>"+ places[i].description;
							}
							
							recordItem += "</div>";

							$("#trippy-autocompleter-results-container").append(recordItem);
							$("#trippy-autocompleter-cancel-button").html("<i class=\"fa fa-times-circle-o\"></i>");
							
						} else {
							alert("Something went wrong -- place has no name?");
						}

					}
					
					$(".trippy-autocompleter-result").click(function() {
						
						if ($(this).attr("data-source") === "SEARCH") {
							getGooglePlace($(this).attr("data-id"));
						} else {
							addPlaceToContent(places[$(this).attr("data-index")]);
						}
						
					} );


				});

			});

		}
		
		function getGooglePlace(gpId) {

			jQuery(function ($) {
				var url = trippyBaseUrl + "/api/v1/place/gPlaceIngest?gPlaceId=" + gpId;

				$.post( url, function( data ) {
					$( ".result" ).html( data );
					var place = $.parseJSON(data).response.place;
					addPlaceToContent(place);
				});

			});
			
		}
		
		function addPlaceToContent(place) {
			
			if (editor.insertContent) {
				editor.insertContent("<a target=\"_blank\" class=\"trippy-place-element\" data-trippy-description=\"" + place.shortDescription + "\" data-trippy-website=\"" + place.website + "\" data-trippy-city-country=\"" + place.smartCityCountry + "\" data-trippy-address=\"" + place.address + "\" data-trippy-phone=\"" + place.phone + "\" data-trippy-name=\"" + place.name +"\" data-trippy-place-id=\"" + place.id +"\" data-coords=\"" +place.latitude +","+ place.longitude + "\" href=\""+ trippyBaseUrl + "/place/" + place.id +"?utm_campaign=EASY_MAPS&utm_source=" + document.domain + "&utm_medium=blog\">" +place.name + "</a>");
			} else {
				editor.selection.setContent("<a target=\"_blank\" class=\"trippy-place-element\" data-trippy-description=\"" + place.shortDescription + "\" data-trippy-website=\"" + place.website + "\" data-trippy-city-country=\"" + place.smartCityCountry + "\" data-trippy-address=\"" + place.address + "\" data-trippy-phone=\"" + place.phone + "\" data-trippy-name=\"" + place.name +"\" data-trippy-place-id=\"" + place.id +"\" data-coords=\"" +place.latitude +","+ place.longitude + "\" href=\""+ trippyBaseUrl + "/place/" + place.id +"?utm_campaign=EASY_MAPS&utm_source=" + document.domain + "&utm_medium=blog\">" +place.name + "</a>");
			}
			removeAutocompleterContainerView();
			
			
			// Update Preview
			jQuery(function ($) {
				
				var data = {
						'action': 'my_plugin_function',
						'contentBody': editor.getContent()
					};
				
				$.post(ajaxurl, data, function(response) {
					
					$(".trippy-admin-panel-map-container").html(response);
					
					if ($(".trippy-admin-panel-map-container img")) {
						$(".trippy-map-panel-preview-instructions").css("display","none");
						
					} 
					
					jQuery(function ($) {
						$("#trippy-admin-show-map-details").click(function() {
							$(".trippy-map-list-ol").toggleClass("trippy-admin-display-none");
							
						});
							
					});

					
				});

			});
			
			// End Update Preview
			
		}


		// Add a button that opens a window
		editor.addButton( 'trippy_autocomplete_button_key', {

			text: 'Add Place',
			icon: false,
			onclick: 

				function() {
				// Open window

				renderAutocompleterView();
	}


		} );

	} );

} )();
