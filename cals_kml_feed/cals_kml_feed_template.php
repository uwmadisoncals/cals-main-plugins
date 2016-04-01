<?php
/**
 * KML Feed Template for displaying KML Posts feed.
 *
 */

header('Content-Type:application/vnd.google-earth.kml+xml; charset=UTF-8');
$more = 1;

echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<kml xmlns="http://www.opengis.net/kml/2.2"
 xmlns:gx="http://www.google.com/kml/ext/2.2">
	<Document>
		<?php do_action('kml_style');?>
		<?php while( have_posts()) : the_post(); ?>
        <Placemark >
        	<name><?php the_title() ?></name>
            <description><![CDATA[<?php the_excerpt() ?>]]></description>
            <?php do_action('kml_placemark');?>
        </Placemark>
        <?php endwhile; ?>
    </Document>
</kml>