<!doctype html>
<html <?php language_attributes() ?>>
<head>
	<title><?php wp_title('') ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- WPH -->
	<?php wp_head() ?>
	v<!-- /WPH -->
	<?php bundle('head-styles, head-scripts'); ?>
</head>
<body>
	<div class="header-wrap">
		<header class="header">
			<nav class="main-nav">
				<?php wp_nav_menu( array(
					'theme_location'  => '',
					'menu'            => '',
					'container'       => 'div',
					'container_class' => 'menu-{menu-slug}-container',
					'container_id'    => '',
					'menu_class'      => 'main-menu',
					'menu_id'         => '',
					'echo'            => true,
					'fallback_cb'     => 'wp_page_menu',
					'before'          => '',
					'after'           => '',
					'link_before'     => '',
					'link_after'      => '',
					'items_wrap'      => '<ul id = "%1$s" class = "%2$s">%3$s</ul>',
					'depth'           => 0,
					'walker'          => '',
				) ); ?>
			</nav>
		</header>
	</div>
	<div class="body-wrap">
