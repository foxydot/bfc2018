<?php
/*
 * Widget override codes
 */


/**
 * Function render view Plugin. this is for the relevant plugin.
 * @param $slug, $post_title_tag string
 */
if ( ! function_exists( 'rltdpstsplgn_render_view' ) ) {
    function rltdpstsplgn_render_view( $slug = '', $post_title_tag = 'h3' ) {
        global $rltdpstsplgn_options, $post;
        ?>

        <article class="post type-post format-standard">
            <?php if ( $rltdpstsplgn_options[ $slug . '_show_thumbnail' ]) { ?>
                    <?php if ( 1 == $rltdpstsplgn_options[ $slug . '_show_thumbnail'] ) {
                        $genesis_get_image_args = array( 'post_id' => $post->ID, 'size' => array( 80, 80 ) );
                        printf( '<a href="%s" title="%s">%s</a>', get_permalink(), the_title_attribute('echo=0'), genesis_get_image($genesis_get_image_args) );
                    } ?>
            <?php } ?>
            <header class="entry-header">
                <?php echo "<{$post_title_tag} class=\"rltdpstsplgn_posts_title\">"; ?>
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                <?php echo "</{$post_title_tag}>";
                if ( $rltdpstsplgn_options[ $slug . '_show_date' ] || $rltdpstsplgn_options[ $slug . '_show_author' ] || $rltdpstsplgn_options[ $slug . '_show_comments' ] || $rltdpstsplgn_options[ $slug . '_show_reading_time' ] ) { ?>
                    <div class="entry-meta">
                        <?php if ( 1 == $rltdpstsplgn_options[ $slug . '_show_date' ] ) { ?>
                            <span class="rltdpstsplgn_date entry-date">
										<?php echo get_the_date(); ?>
									</span>
                        <?php }
                        if ( 1 == $rltdpstsplgn_options[ $slug . '_show_author' ] ) { ?>
                            <span class="rltdpstsplgn-author"><?php _e( 'by', 'relevant' ) ?>
                                <span class="author vcard">
												<a class="url fn n" rel="author" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo get_the_author(); ?></a>
											</span>
										</span>
                        <?php }
                        if ( 1 == $rltdpstsplgn_options[ $slug . '_show_reading_time' ] ) {
                            $word	= str_word_count( strip_tags( get_the_content() ) );
                            $min	= floor( $word / 200 );
                            $sec	= floor( $word % 200 / ( 200 / 60 ) ); ?>
                            <span class="rltdpstsplgn-reading-time">
											<?php if ( 0 == $min && 30 >= $sec ) {
                                                echo __( 'less than 1 min read', 'relevant' );
                                            } elseif ( 60 < $min ) {
                                                echo __( 'more than 1 hour read', 'relevant' );
                                            } else {
                                                if ( 0 != $sec ) {
                                                    $min ++;
                                                }
                                                printf( __( '%s min read', 'relevant' ), $min );
                                            } ?>
										</span>
                        <?php }
                        if ( 1 == $rltdpstsplgn_options[ $slug . '_show_comments' ] ) { ?>
                            <span class="rltdpstsplgn-comments-count">
											<?php comments_number( __( 'No comments', 'relevant' ), __( '1 Comment', 'relevant' ), __( '% Comments', 'relevant' ) ); ?>
										</span>
                        <?php }
                        if ( 'popular' == $slug && 1 == $rltdpstsplgn_options[ $slug . '_show_views'] ) {
                            $views_count = get_post_meta( $post->ID, 'pplrpsts_post_views_count' );
                            $views_count = $views_count ? $views_count[0] : 0; ?>
                            <span class="rltdpstsplgn-post-count"><?php printf( _n( '%s view', '%s views', $views_count, 'relevant' ), $views_count ); ?></span>
                        <?php } ?>
                    </div><!-- .entry-meta -->
                <?php } ?>
            </header>
            <?php if ( $rltdpstsplgn_options[ $slug . '_show_excerpt' ] ) { ?>
                <div class="entry-content">
                    <?php
                    if ( 1 == $rltdpstsplgn_options[ $slug . '_show_excerpt' ] ) {
                        the_excerpt();
                    } ?>
                    <div class="clear"></div>
                </div><!-- .entry-content -->
            <?php } ?>
        </article><!-- .post -->
    <?php }
}