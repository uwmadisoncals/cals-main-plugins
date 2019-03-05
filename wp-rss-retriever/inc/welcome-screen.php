<?php

add_action( 'admin_init', 'wp_rss_retriever_do_activation_redirect' );
function wp_rss_retriever_do_activation_redirect() {
  // Bail if no activation redirect
    if ( ! get_transient( '_wp_rss_retriever_activation_redirect' ) ) {
    return;
  }

  // Delete the redirect transient
  delete_transient( '_wp_rss_retriever_activation_redirect' );

  // Bail if activating from network, or bulk
  if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
    return;
  }

  // Redirect to plugin welcome page
  wp_safe_redirect( add_query_arg( array( 'page' => 'wp-rss-retriever-welcome' ), admin_url( 'index.php' ) ) );

}

add_action('admin_menu', 'wp_rss_retriever_pages');

function wp_rss_retriever_pages() {
  add_dashboard_page(
    'Welcome To RSS Feed Retriever',
    'WordPress RSS Feed Retriever',
    'read',
    'wp-rss-retriever-welcome',
    'wp_rss_retriever_welcome'
  );
  add_dashboard_page(
    'Welcome To RSS Feed Retriever',
    'WordPress RSS Feed Retriever',
    'read',
    'wp-rss-retriever-examples',
    'wp_rss_retriever_examples'
  );
}

function wp_rss_retriever_welcome() {
    wp_rss_retriever_welcome_header();
    ?>

    <div class="full-width">
        <h2>Video Tutorial</h2>
      <iframe width="100%" height="600px" src="https://www.youtube.com/embed/2EPdD65zS5U" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>
  </div>
  <?php
}

function wp_rss_retriever_examples() {
    wp_rss_retriever_welcome_header();
    ?>

    <div class="feature-section one-col">
      <div class="col">
        <h2>Example Shortcode</h2>
        <p>[wp_rss_retriever url="https://wordpress.org/news/feed/" items="10" excerpt="50" read_more="true" credits="true" new_window="true" thumbnail="200" cache="7200"]</p>
      </div>
    </div>

    <div class="feature-section one-col">
      <div class="col">
        <h3>Features:</h3>
        <ul>
          <li>Fetch as many RSS feeds as you want</li>
          <li>Display the RSS feed wherever you want using shortcode, including text widgets</li>
          <li>Control whether to display the entire RSS feeds content or just an excerpt</li>
          <li>Control how many words display in the excerpt</li>
          <li>Control whether it has a Read more link or not</li>
          <li>Control whether links open in a new window or not</li>
          <li>Simple, lightweight, and fast</li>
          <li>Easy to setup</li>
          <li>Fetch thumbnail or first image</li>
          <li>Control size of thumbnail (width and height)</li>
          <li>Set cache time (in seconds)</li>
          <li>Control order of items</li>
          <li>Aggregate multiple feeds into one list</li>
          <li>Dofollow or nofollow options</li>
        </ul>
      </div>
  </div>
  <?php
}


function wp_rss_retriever_welcome_header() {
  $screen = get_current_screen();
  ?>
  <div class="wrap about-wrap full-width-layout">
    <h1>Welcome to RSS Feed Retriever v<?php echo WP_RSS_RETRIEVER_VER; ?></h1>

    
    <p class="about-text">
      A lightweight RSS feed plugin which uses shortcode to fetch and display an RSS feed including thumbnails and an excerpt. <a href="https://thememason.com/plugins/rss-retriever/" title="WordPress RSS Feed Retriever">Learn more</a>
    </p>
    <div class="wp-badge" style="background-color: #282828; background-image:url(<?php echo plugin_dir_url( __FILE__ ) . 'imgs/rss-icon.svg'; ?>)">Version <?php echo WP_RSS_RETRIEVER_VER; ?></div>
    
    <h2 class="nav-tab-wrapper wp-clearfix">
      <a href="<?php echo admin_url( 'index.php?page=wp-rss-retriever-welcome') ?>" class="nav-tab<?php echo ($screen->id == 'dashboard_page_wp-rss-retriever-welcome' ? ' nav-tab-active' : ''); ?>">Get Started</a>
      <a href="<?php echo admin_url( 'index.php?page=wp-rss-retriever-examples') ?>" class="nav-tab<?php echo ($screen->id == 'dashboard_page_wp-rss-retriever-examples' ? ' nav-tab-active' : ''); ?>">Examples</a>
    </h2>
  <?php
}

add_action( 'admin_head', 'wp_rss_retriever_remove_menus' );
function wp_rss_retriever_remove_menus() {
    remove_submenu_page( 'index.php', 'wp-rss-retriever-welcome' );
    remove_submenu_page( 'index.php', 'wp-rss-retriever-examples' );
}
