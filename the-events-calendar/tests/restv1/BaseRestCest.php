<?php

use Codeception\Configuration;

class BaseRestCest {

	/**
	 * @var string
	 */
	protected $rest_disable_option = 'rest-v1-disabled';
	/**
	 * @var string The site full URL to the homepage.
	 */
	protected $site_url;
	/**
	 * @var string
	 */
	protected $tec_option = 'tribe_events_calendar_options';

	/**
	 * @var string The site full URL to the REST API root.
	 */
	protected $rest_url;

	/**
	 * @var string
	 */
	protected $events_url;

	/**
	 * @var string
	 */
	protected $documentation_url;

	public function _before( Restv1Tester $I ) {
		$this->site_url = $I->grabSiteUrl();
		$this->rest_url = $this->site_url . '/wp-json/tribe/events/v1/';
		$this->events_url = $this->rest_url . 'events';
		$this->documentation_url = $this->rest_url . 'doc';
	}
}