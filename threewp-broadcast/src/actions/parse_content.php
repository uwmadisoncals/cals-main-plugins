<?php

namespace threewp_broadcast\actions;

/**
	@brief		Tells Broadcast to parse a content string with the use of the Broadcasting data.
	@details	Will currently replace image attachments in captions and image guids.
	@since		2016-03-30 17:47:15
**/
class parse_content
	extends action
{
	/**
		@brief		IN: The broadcasting_data object to use.
		@since		2016-03-30 17:48:10
	**/
	public $broadcasting_data;

	/**
		@brief		IN/OUT: The string to parse.
		@since		2016-03-30 17:48:21
	**/
	public $content;
}
