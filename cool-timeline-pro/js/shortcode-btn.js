( function() {
   tinymce.PluginManager.add( 'cool_timeline', function( editor, url ) {
	   var layouts=[];
	   layouts.push({"text":'Default Layout',"value":'default'});
	   layouts.push({"text":'One Side Layout',"value":'one-side'});
 	   layouts.push({"text":'Compact Layout',"value":'compact'});
	   var skins=[];
	   skins.push({"text":"default","value":"default"});
	   skins.push({"text":"light","value":"light"});
	   skins.push({"text":"dark","value":"dark"});
	   var multi_items={
		   "items":[
			   {"text":"Select items","value":""},
			   {"text":"1","value":"1"},
			   {"text":"2","value":"2"},
			   {"text":"3","value":"3"},
			   {"text":"4","value":"4"},
				   ]
	   };

	   var animations_eff={
		   "animations":[

			   {"text":"bounceInUp","value":"bounceInUp"},
			   {"text":"bounceInDown","value":"bounceInDown"},
			   {"text":"bounceInLeft","value":"bounceInLeft"},
			   {"text":"bounceInRight","value":"bounceInRight"},
			   {"text":"slideInDown","value":"slideInDown"},
			   {"text":"slideInUp","value":"slideInUp"},
			   {"text":"bounceIn","value":"bounceIn"},
			   {"text":"slideInLeft","value":"slideInLeft"},
			   {"text":"slideInRight","value":"slideInRight"},
			   {"text":"shake","value":"shake"},
			   {"text":"wobble","value":"wobble"},
			   {"text":"swing","value":"swing"},
			   {"text":"jello","value":"jello"},
			   {"text":"flip","value":"flip"},
			   {"text":"fadein","value":"fadein"},
			   {"text":"rotatein","value":"rotatein"},

			   {"text":"zoomIn","value":"zoomIn"},
			   {"text":"None","value":"none"},
				 ]};
		var date_formats={
		   "formats":[
		  	   {"text":"Default","value":"default"},
			   {"text":"F j","value":"F j"},
			   {"text":"F j Y","value":"F j Y"},
			   {"text":"Y-m-d","value":"Y-m-d"},
			   {"text":"m/d/Y","value":"m/d/Y"},
			   {"text":"d/m/Y","value":"d/m/Y"},
			   {"text":"F j Y g:i A","value":"F j Y g:i A"},
			   {"text":"Y","value":"Y"},
			   {"text":"Custom","value":"custom"}
			   ]};


	   var timeline_designs={
		   "designs":[
			   {"text":"Default","value":"default"},
			   {"text":"Flat Design","value":"design-2"},
			   {"text":"Classic Design","value":"design-3"},
			   {"text":"Elegant Design","value":"design-4"},
			 ]
		 }
		 var compact_ele_pos=[];
	   compact_ele_pos.push({"text":"On top date/label below title","value":"main-date"});
	   compact_ele_pos.push({"text":"On top title below date/label","value":"main-title"});
	   var s_order=[];
	   s_order.push({"text":"DESC","value":"DESC"});
	   s_order.push({"text":"ASC","value":"ASC"});
	    var s_cont=[];
	   s_cont.push({"text":"Summary","value":"short"});
	   s_cont.push({"text":"Full Text","value":"full"});
	   var icons_options=[];
	   icons_options.push({"text":"NO","value":"NO"});
	   icons_options.push({"text":"YES","value":"YES"});

	    var timeline_based_on=[];
	   timeline_based_on.push({"text":"Default(Date Based)","value":"default"});
	   timeline_based_on.push({"text":"Label (Order Based)","value":"custom"});

	   var ctl_cats=JSON.parse(ctl_cat_obj.category);
	   var categories=[];

	   for( var cat in ctl_cats){
		   categories.push({"text":ctl_cats[cat],"value":cat});
	   }
	
	editor.addButton( 'cool_timeline_shortcode_button', {
		
				text: false,
				type: 'menubutton',
				image: url + '/cooltimeline.png',
		
				menu: [
                {
                    text: 'Vertical Timeline',
                    onclick: function() {

                        editor.windowManager.open( {
                            title: 'Add Cool Timeline Shortcode',
                            body: [

							{
                                type: 'listbox', 
                                name: 'category', 
                                label: 'Timeline categories',
                                'values':categories
                            },
								{
									type: 'listbox',
									name: 'designs',
									label: 'Timeline Designs',
									'values':timeline_designs.designs
								},
							{
								type: 'listbox',
								name: 'timeline_layout',
								label: 'Timeline Layouts',
								'values':layouts
							},
							{
								type: 'listbox',
								name: 'timeline_skin',
								label: 'Timeline skins',
								'values':skins
							},
							{
								type: 'listbox',
								name: 'stories_order',
								label: 'Story Order',
								'values':s_order
							},
							{
									type: 'listbox',
									name: 'date_format',
									label: 'Date formats',
									'values':date_formats.formats
								},
								{
									type: 'listbox',
									name: 'tm_bs_on',
									label: 'Timeline based on',
									'values':timeline_based_on
								},
								{
									type: 'textbox',
									name: 'number_of_posts',
									label: 'Show number of posts',
									value:20
								},
								{
										type: 'listbox',
										name: 'story_content',
										label: 'Stories Description?',
										'values':s_cont
								},
								{
									type: 'listbox',
									name: 'ctl_icons',
									label: 'Icon',
									'values':icons_options
								},
								{
									type: 'listbox',
									name: 'compact_ele_pos',
									label: 'Compact Layout Date&Title positon',
									values:compact_ele_pos
								},
								{
									type: 'listbox',
									name: 'animations',
									label: 'Animation Effects',
									values:animations_eff.animations
								}
							],
                            onsubmit: function( e ) {
                                editor.insertContent( '[cool-timeline layout="'+ e.data.timeline_layout+'"  designs="'+ e.data.designs +'" skin="'+ e.data.timeline_skin+'" category="' + e.data.category + '" show-posts="' + e.data.number_of_posts + '" order="' + e.data.stories_order + '" icons="' + e.data.ctl_icons + '" animations="' + e.data.animations + '" date-format="' + e.data.date_format + '" story-content="'+ e.data.story_content +'" based="'+ e.data.tm_bs_on +'" compact-ele-pos="'+ e.data.compact_ele_pos +'"]');
                            }
                        });
                    }
                },

				{
						text: 'Horizontal Timeline',
						onclick: function() {

							editor.windowManager.open( {
								title: 'Add Cool Timeline Shortcode',
								body: [
									{
										type: 'listbox',
										name: 'category',
										label: 'Timeline categoires',
										'values':categories
									},

									{
										type: 'listbox',
										name: 'designs',
										label: 'Timeline Designs',
										'values':timeline_designs.designs
									},
									{
										type   : 'container',
										name   : 'display-lbl',
										label  : '',
										html   : '<i>Display stories option is not for default design.</i>'
									},
									{
										type: 'listbox',
										name: 'items',
										label: 'Display stories',
										'values':multi_items.items
									},
									{
										type: 'listbox',
										name: 'timeline_skin',
										label: 'Timeline skins',
										'values':skins
									},
									{
										type: 'listbox',
										name: 'stories_order',
										label: 'Story Order',
										'values':s_order
									},
									{
									type: 'listbox',
									name: 'date_format',
									label: 'Date formats',
									'values':date_formats.formats
								},
								{
									type: 'listbox',
									name: 'tm_bs_on',
									label: 'Timeline based on',
									'values':timeline_based_on
								},
									{
										type: 'textbox',
										name: 'number_of_posts',
										label: 'Show number of posts',
										value:20
									},
									{
										type: 'listbox',
										name: 'story_content',
										label: 'Stories Description?',
										'values':s_cont
									},
									{
										type: 'listbox',
										name: 'ctl_icons',
										label: 'Icon',
										'values':icons_options
									}
								],
								onsubmit: function( e ) {
									editor.insertContent( '[cool-timeline type="horizontal" category="' + e.data.category + '" skin="'+ e.data.timeline_skin+'"  designs="'+ e.data.designs +'" show-posts="' + e.data.number_of_posts + '" order="' + e.data.stories_order + '" items="' + e.data.items + '"  icons="' + e.data.ctl_icons + '" story-content="'+ e.data.story_content +'"  date-format="' + e.data.date_format + '" based="'+ e.data.tm_bs_on +'"]');
								}
							});
						}
					},
					{
						text: 'Content Timeline(Blog)',
						onclick: function() {

							editor.windowManager.open( {
								title: 'Add Content Timeline Shortcode',
								body: [
									{
										type: 'textbox',
										name: 'post_type',
										label: 'Content Post type',
										value:'post'
									},
									{
										type: 'textbox',
										name: 'taxonomy_name',
										label: 'Taxonomy Name',
										value:'category'
									},
									{
										type: 'textbox',
										name: 'post_category',
										label: 'Specific category(s) (Add category(s) slug - comma separated)',
										value:''
									},
									{
										type: 'textbox',
										name: 'tags',
										label: 'Specific tags(add tags slug)',
										value:''
									},
									
									{
										type: 'listbox',
										name: 'timeline_layout',
										label: 'Timeline Layouts',
										'values':[{"text":'Default Layout',"value":'default'},
											{"text":'One Side Layout',"value":'one-side'},
											{"text":'Compact Layout',"value":'compact'},
											{"text":'Horizontal',"value":'horizontal'},
										]
									},
									{
										type: 'listbox',
										name: 'designs',
										label: 'Timeline Designs',
										'values':timeline_designs.designs
									},
								/*	{
										type   : 'container',
										name   : 'display-lbl',
										label  : '',
										html   : '<i>Display stories option is only for horizontal mode.This option is not for default design.</i>'
									}, */
									{
										type: 'listbox',
										name: 'items',
										label: 'Display Stories in Horizontal(This option is not for default design)',
										'values':multi_items.items
									},
									{
										type: 'listbox',
										name: 'timeline_skin',
										label: 'Timeline skins',
										'values':skins
									},
									{
										type: 'listbox',
										name: 'stories_order',
										label: 'Story Order',
										'values':s_order
									},
									{
									type: 'listbox',
									name: 'date_format',
									label: 'Date formats',
									'values':date_formats.formats
									},
									{
										type: 'textbox',
										name: 'number_of_posts',
										label: 'Show number of posts',
										value:20
									},
									{
										type: 'listbox',
										name: 'ctl_icons',
										label: 'Icon',
										'values':icons_options
									},
									{
										type: 'listbox',
										name: 'story_content',
										label: 'Content Description?',
										'values':s_cont
									},
								{
										type: 'listbox',
										name: 'animations',
										label: 'Animation Effects',
										'values':animations_eff.animations
									}


								],
								onsubmit: function( e ) {
									editor.insertContent( '[cool-content-timeline post-type="'+ e.data.post_type+'"  post-category="' + e.data.post_category + '" tags="' + e.data.tags + '" story-content="'+ e.data.story_content +'"  taxonomy="' + e.data.taxonomy_name + '" layout="'+ e.data.timeline_layout+'"  designs="'+ e.data.designs +'" skin="'+ e.data.timeline_skin+'" show-posts="' + e.data.number_of_posts + '" order="' + e.data.stories_order + '"  icons="' + e.data.ctl_icons + '" animations="' + e.data.animations + '"  items="' + e.data.items + '" date-format="' + e.data.date_format + '"]');
								}
							});
						}
					},
					{
						text: 'Facebook Page Timeline',
						onclick: function() {

							editor.windowManager.open( {
								title: 'Add Facebook page feed in shortcode',
								body: [
									{
										type   : 'container',
										name   : 'container',
										label  : '',
										html   : '<h1>You need to have Facebook App ID & Secret Key in order to create Facebook page timeline.<br> For this either watch our <i> <a target="_blank" href="http://www.cooltimeline.com/document/facebook-page-timeline/">Video Tutorial</a></i> or follow these steps:-</strong></h1><br>' +
										'<ol>'+
										'<li>Go to -> <a target="_blank" href="https://developers.facebook.com">https://developers.facebook.com</a> -> Register as a developer</li>'+
										'<li>Apps -> Create a new App.</li>'+
										'<li>Complete the wizard</li>'+
										'<li>Once done you will see the new App ID and Secret keys in the dashboard.</li></ol>'
									},
									{
										type: 'textbox',
										name: 'fb_app_id',
										label: 'Facebook App Id',
										value:''
									},
									{
										type: 'textbox',
										name: 'fb_app_secret',
										label: 'Facbook APP secret Key',
										value:''
									},
									{
										type: 'textbox',
										name: 'fb_page_name',
										label: 'Facbook Page name',
										value:''
									},
									{
										type: 'textbox',
										name: 'number_of_posts',
										label: 'Show number of posts',
										value:20
									},
									{
										type: 'listbox',
										name: 'timeline_skin',
										label: 'Timeline skin',
										'values':skins
									},
								],
								onsubmit: function( e ) {
									editor.insertContent( '[cool-social-timeline type="social" fb-app-id="'+ e.data.fb_app_id+'" fb-app-secret-key="'+ e.data.fb_app_secret+'" fb-page-name="'+ e.data.fb_page_name+'"  show-posts="' + e.data.number_of_posts + '"  skin="'+ e.data.timeline_skin+'"]');
								}
							});
						}
					}
           ]
			});
		editor.onSetContent.add(function(editor, o) {
			  if ( editor.getContent().indexOf( '[cool-timeline]' ) > -1) {
					editor.controlManager.setDisabled('cool_timeline_shortcode_button', true);
				}
		  });

	
	});
	

})();