<?php
/*
 * Notes to self: Make this more like the chapters type from PNHP? I will eventually need area code matching. I wonder how best to do that.
 */
if (!class_exists('MSDTeamCPT')) {
    class MSDTeamCPT {
        //Properties
        var $cpt = 'team_member';
        //Methods
        /**
         * PHP 4 Compatible Constructor
         */
        public function MSDTeamCPT(){$this->__construct();}

        /**
         * PHP 5 Constructor
         */
        function __construct(){
            global $current_screen;
            //"Constants" setup
            $this->plugin_url = plugin_dir_url('msd-custom-cpt/msd-custom-cpt.php');
            $this->plugin_path = plugin_dir_path('msd-custom-cpt/msd-custom-cpt.php');
            //Actions
            add_action( 'init', array(&$this,'register_taxonomies') );
            add_action( 'init', array(&$this,'register_cpt') );
            add_action( 'init', array(&$this,'register_metaboxes') );
            //add_action('admin_head', array(&$this,'plugin_header'));
            add_action('admin_print_scripts', array(&$this,'add_admin_scripts') );
            add_action('admin_print_styles', array(&$this,'add_admin_styles') );
            add_action('admin_footer',array(&$this,'info_footer_hook') );
            // important: note the priority of 99, the js needs to be placed after tinymce loads
            add_action('admin_print_footer_scripts',array(&$this,'print_footer_scripts'),99);
            //add_action('template_redirect', array(&$this,'my_theme_redirect'));
            //add_action('admin_head', array(&$this,'codex_custom_help_tab'));

            //Filters
            //add_filter( 'pre_get_posts', array(&$this,'custom_query') );
            add_filter( 'enter_title_here', array(&$this,'change_default_title') );
            add_filter( 'genesis_attr_team_member', array(&$this,'custom_add_team_member_attr') );

            //Shortcodes
            add_shortcode('teammembers', array(&$this,'msdlab_team_member_special_loop_shortcode_handler'));
            add_shortcode('team-members', array(&$this,'msdlab_team_member_special_loop_shortcode_handler'));
            add_shortcode('team',array(&$this,'msdlab_team_member_special_loop_shortcode_handler'));

            add_image_size('team-headshot',360,360, array('center','top'));

        }


        function register_taxonomies(){

            $labels = array(
                'name' => _x( 'Team categories', 'team-category' ),
                'singular_name' => _x( 'Team category', 'team-category' ),
                'search_items' => _x( 'Search team categories', 'team-category' ),
                'popular_items' => _x( 'Popular team categories', 'team-category' ),
                'all_items' => _x( 'All team categories', 'team-category' ),
                'parent_item' => _x( 'Parent team category', 'team-category' ),
                'parent_item_colon' => _x( 'Parent team category:', 'team-category' ),
                'edit_item' => _x( 'Edit team category', 'team-category' ),
                'update_item' => _x( 'Update team category', 'team-category' ),
                'add_new_item' => _x( 'Add new team category', 'team-category' ),
                'new_item_name' => _x( 'New team category name', 'team-category' ),
                'separate_items_with_commas' => _x( 'Separate team categories with commas', 'team-category' ),
                'add_or_remove_items' => _x( 'Add or remove team categories', 'team-category' ),
                'choose_from_most_used' => _x( 'Choose from the most used team categories', 'team-category' ),
                'menu_name' => _x( 'Team categories', 'team-category' ),
            );

            $args = array(
                'labels' => $labels,
                'public' => true,
                'show_in_nav_menus' => true,
                'show_ui' => true,
                'show_tagcloud' => false,
                'hierarchical' => true, //we want a "category" style taxonomy, but may have to restrict selection via a dropdown or something.

                'rewrite' => array('slug'=>'team-category','with_front'=>false),
                'query_var' => true
            );

            register_taxonomy( 'team_category', array($this->cpt), $args );


            $labels = array(
                'name' => _x( 'Team tags', 'team-tag' ),
                'singular_name' => _x( 'Team tag', 'team-tag' ),
                'search_items' => _x( 'Search team tags', 'team-tag' ),
                'popular_items' => _x( 'Popular team tags', 'team-tag' ),
                'all_items' => _x( 'All team tags', 'team-tag' ),
                'parent_item' => _x( 'Parent team tag', 'team-tag' ),
                'parent_item_colon' => _x( 'Parent team tag:', 'team-tag' ),
                'edit_item' => _x( 'Edit team tag', 'team-tag' ),
                'update_item' => _x( 'Update team tag', 'team-tag' ),
                'add_new_item' => _x( 'Add new team tag', 'team-tag' ),
                'new_item_name' => _x( 'New team tag name', 'team-tag' ),
                'separate_items_with_commas' => _x( 'Separate team tags with commas', 'team-tag' ),
                'add_or_remove_items' => _x( 'Add or remove team tags', 'team-tag' ),
                'choose_from_most_used' => _x( 'Choose from the most used team tags', 'team-tag' ),
                'menu_name' => _x( 'Team tags', 'team-tag' ),
            );

            $args = array(
                'labels' => $labels,
                'public' => true,
                'show_in_nav_menus' => true,
                'show_ui' => true,
                'show_tagcloud' => false,
                'hierarchical' => false, //we want a "tag" style taxonomy, but may have to restrict selection via a dropdown or something.

                'rewrite' => array('slug'=>'team-tag','with_front'=>false),
                'query_var' => true
            );

            register_taxonomy( 'team_tag', array($this->cpt), $args );
        }

        function register_cpt() {

            $labels = array(
                'name' => _x( 'Team Members', 'team' ),
                'singular_name' => _x( 'Team Member', 'team' ),
                'add_new' => _x( 'Add New', 'team' ),
                'add_new_item' => _x( 'Add New Team Member', 'team' ),
                'edit_item' => _x( 'Edit Team Member', 'team' ),
                'new_item' => _x( 'New Team Member', 'team' ),
                'view_item' => _x( 'View Team Member', 'team' ),
                'search_items' => _x( 'Search Team Members', 'team' ),
                'not_found' => _x( 'No team members found', 'team' ),
                'not_found_in_trash' => _x( 'No team members found in Trash', 'team' ),
                'parent_item_colon' => _x( 'Parent Team:', 'team' ),
                'menu_name' => _x( 'Team Members', 'team' ),
            );

            $args = array(
                'labels' => $labels,
                'hierarchical' => false,
                'description' => 'Team',
                'supports' => array( 'title', 'editor', 'thumbnail', 'genesis-cpt-archives-settings' ),
                'taxonomies' => array( 'team_category' ),
                'public' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'menu_position' => 20,

                'show_in_nav_menus' => true,
                'publicly_queryable' => true,
                'exclude_from_search' => true,
                'has_archive' => true,
                'query_var' => true,
                'can_export' => true,
                'rewrite' => array('slug'=>'team','with_front'=>false),
                'capability_type' => 'post',
                'menu_icon' => 'dashicons-groups',
            );

            register_post_type( $this->cpt, $args );
        }


        function register_metaboxes()
        {
            global $contact_info;
            $contact_info = new WPAlchemy_MetaBox(array
            (
                'id' => '_contact_info',
                'title' => 'Contact Info',
                'types' => array('location', 'team_member'), // added only for pages and to custom post type "events"
                'context' => 'normal', // same as above, defaults to "normal"
                'priority' => 'high', // same as above, defaults to "high"
                'template' => plugin_dir_path(dirname(__FILE__)).'/template/contact-info.php',
                'autosave' => TRUE,
                'mode' => WPALCHEMY_MODE_EXTRACT, // defaults to WPALCHEMY_MODE_ARRAY
                'prefix' => '_team_member_' // defaults to NULL
            ));

        }


        function add_admin_scripts() {
            global $current_screen;
            if($current_screen->post_type == $this->cpt){
            }
        }

        function add_admin_styles() {
            global $current_screen;
            if($current_screen->post_type == $this->cpt){
                wp_enqueue_style('custom_meta_css',plugin_dir_url(dirname(__FILE__)).'/css/meta.css');
            }
        }

        function print_footer_scripts()
        {
            global $current_screen;
            if($current_screen->post_type == $this->cpt){
                print '<script type="text/javascript">/* <![CDATA[ */
					jQuery(function($)
					{
						var i=1;
						$(\'.customEditor textarea\').each(function(e)
						{
							var id = $(this).attr(\'id\');
			 
							if (!id)
							{
								id = \'customEditor-\' + i++;
								$(this).attr(\'id\',id);
							}
			 
							tinyMCE.execCommand(\'mceAddControl\', false, id);
			 
						});
					});
				/* ]]> */</script>';
            }
        }

        function info_footer_hook()
        {
            global $current_screen;
            if($current_screen->post_type == $this->cpt){
                ?><script type="text/javascript">
                    jQuery('#postdivrich').before(jQuery('#_contact_information_metabox'));
                    jQuery('#_contact_information_metabox').before(jQuery('#_team_information_metabox'));
                </script><?php
            }
        }


        function my_theme_redirect() {
            global $wp;

            //A Specific Custom Post Type
            if ($wp->query_vars["post_type"] == $this->cpt) {
                if(is_single()){
                    $templatefilename = 'single-'.$this->cpt.'.php';
                    if (file_exists(STYLESHEETPATH . '/' . $templatefilename)) {
                        $return_template = STYLESHEETPATH . '/' . $templatefilename;
                    } else {
                        $return_template = plugin_dir_path(dirname(__FILE__)). 'template/' . $templatefilename;
                    }
                    do_theme_redirect($return_template);

                    //A Custom Taxonomy Page
                } elseif ($wp->query_vars["taxonomy"] == 'team_category') {
                    $templatefilename = 'taxonomy-team_category.php';
                    if (file_exists(STYLESHEETPATH . '/' . $templatefilename)) {
                        $return_template = STYLESHEETPATH . '/' . $templatefilename;
                    } else {
                        $return_template = plugin_dir_path(dirname(__FILE__)) . 'template/' . $templatefilename;
                    }
                    do_theme_redirect($return_template);
                }
            }
        }

        function codex_custom_help_tab() {
            global $current_screen;
            if($current_screen->post_type != $this->cpt)
                return;

            // Setup help tab args.
            $args = array(
                'id'      => 'title', //unique id for the tab
                'title'   => 'Title', //unique visible title for the tab
                'content' => '<h3>The Event Title</h3>
                          <p>The title of the event.</p>
                          <h3>The Permalink</h3>
                          <p>The permalink is created by the title, but it doesn\'t change automatically if you change the title. To change the permalink when editing an event, click the [Edit] button next to the permalink. 
                          Remove the text that becomes editable and click [OK]. The permalink will repopulate with the new Location and date!</p>
                          ',  //actual help text
            );

            // Add the help tab.
            $current_screen->add_help_tab( $args );

            // Setup help tab args.
            $args = array(
                'id'      => 'event_info', //unique id for the tab
                'title'   => 'Event Info', //unique visible title for the tab
                'content' => '<h3>Event URL</h3>
                          <p>The link to the page describing the event</p>
                          <h3>The Event Date</h3>
                          <p>The Event Date is the date of the event. This value is restrained to dates (chooseable via a datepicker module). This value is also used to sort events for the calendars, upcoming events, etc.</p>
                          <p>For single day events, set start and end date to the same date.',  //actual help text
            );

            // Add the help tab.
            $current_screen->add_help_tab( $args );

        }


        function custom_query( $query ) {
            if(!is_admin()){
                if(is_page()){
                    return $query;
                }
                if($query->is_main_query()) {
                    $post_types = $query->get('post_type');             // Get the currnet post types in the query

                    if(!is_array($post_types) && !empty($post_types))   // Check that the current posts types are stored as an array
                        $post_types = explode(',', $post_types);

                    if(empty($post_types))
                        $post_types = array('post'); // If there are no post types defined, be sure to include posts so that they are not ignored

                    if ($query->is_search) {
                        $searchterm = $query->query_vars['s'];
                        // we have to remove the "s" parameter from the query, because it will prevent the posts from being found
                        $query->query_vars['s'] = "";

                        if ($searchterm != "") {
                            $query->set('meta_value', $searchterm);
                            $query->set('meta_compare', 'LIKE');
                        };
                        $post_types[] = $this->cpt;                         // Add your custom post type

                    } elseif ($query->is_archive) {
                        $post_types[] = $this->cpt;                         // Add your custom post type
                    }

                    $post_types = array_map('trim', $post_types);       // Trim every element, just in case
                    $post_types = array_filter($post_types);            // Remove any empty elements, just in case

                    $query->set('post_type', $post_types);              // Add the updated list of post types to your query
                }
            }
        }

        function change_default_title( $title ){
            global $current_screen;
            if  ( $current_screen->post_type == $this->cpt ) {
                return __('Team Member Name','team');
            } else {
                return $title;
            }
        }

        function cpt_display(){
            global $post;
            if(is_cpt($this->cpt)) {
                if (is_single()){
                    //display content here
                } else {
                    //display for aggregate here
                }
            }
        }

        function get_all_team_members(){
            $args = array(
                'posts_per_page'   => -1,
                'orderby'          => 'title',
                'order'            => 'ASC',
                'post_type'        => $this->cpt,
            );
            $posts = get_posts($args);
            $i = 0;
            foreach($posts AS $post){
                $posts[$i]->lastname = get_post_meta($post->ID,'_team_alpha',TRUE);
                $i++;
            }
            usort($posts,array(&$this,'sort_by_lastname'));
            return $posts;
        }

        function msdlab_team_member_special_loop_shortcode_handler($atts){
            $args = shortcode_atts( array(
            ), $atts );
            remove_filter('the_content','wpautop',12);
            return $this->msdlab_team_member_special($args);
        }
        function msdlab_team_member_special($args){
            global $post,$contact_info;
            $origpost = $post;
            $defaults = array(
                'posts_per_page' => -1,
                'post_type' => 'team_member',
                'orderby' => 'meta_value',
                'meta_key' => '_team_member__team_last_name',
                'order' => 'ASC'
            );
            $args = array_merge($defaults,$args);
            //set up result array
            $results = array();
            $results = get_posts($args);
            //format result
            $i = 0;
            foreach($results AS $result){
                $post = $result;
                $i++;
                $titlearray = explode(" ",$post->post_title);
                $firstname = $titlearray[0];
                $firstname = (substr($firstname, -1) == 's')?$firstname."'":$firstname."'s";
                $contact_info->the_meta($result->ID);
                $ret[] = genesis_markup( array(
                    'html5'   => '<article %s>',
                    'xhtml'   => '<div class="team_member type-team_member status-publish has-post-thumbnail entry">',
                    'context' => 'team_member',
                    'echo' => false,
                ) );
                $ret[] = genesis_markup( array(
                    'html5' => '<div class="wrap">',
                    'xhtml' => '<div class="wrap">',
                    'echo' => false,
                ) );

                $ret[] = genesis_markup( array(
                    'html5' => '<main>',
                    'xhtml' => '<div class="main">',
                    'echo' => false,
                ) );
                $ret[] = get_the_post_thumbnail($result->ID,'team-headshot',array('itemprop'=>'image'));

                $ret[] = genesis_markup( array(
                    'html5' => '<header>',
                    'xhtml' => '<div class="header">',
                    'echo' => false,
                ) );
                $ret[] = '<h3 class="entry-title" itemprop="name">'.$post->post_title.'</h3>
                            <h4 class="team-title" itemprop="jobTitle">'.$contact_info->get_the_value('_team_title').'</h4>';

                $ret[] = genesis_markup( array(
                    'html5' => '</header>',
                    'xhtml' => '</div>',
                    'echo' => false,
                ) );
                $ret[] = genesis_markup( array(
                    'html5' => '<content>',
                    'xhtml' => '<div class="content">',
                    'echo' => false,
                ) );
                $ret[] = msdlab_get_excerpt($post->ID,40,'');
                $ret[] = genesis_markup( array(
                    'html5' => '</content>',
                    'xhtml' => '</div>',
                    'echo' => false,
                ) );
                $ret[] = genesis_markup( array(
                    'html5' => '<footer>',
                    'xhtml' => '<div class="footer">',
                    'echo' => false,
                ) );
                $ret[] = '
                                <ul>';
                if($contact_info->get_the_value('_team_linked_in')){
                    $ret[] = '<li class="linkedin"><a href="'.$contact_info->get_the_value('_team_linked_in').'" target="_linkedin">
          <i class="fa fa-linkedin"><span class="screen-reader-text">LinkedIn</span></i>
        </a></li>';
                }
                if($contact_info->get_the_value('_team_user_id')!=0){
                    $ret[] = '<li class="insights-header"><a href="'.get_permalink($result->ID).'#insights">
          <i class="fa fa-rss"><span class="screen-reader-text">'.$firstname.' Insights</span></i>
        </a></li>';
                }

                $ret[] = '</ul>';

                if($contact_info->get_the_value('_team_position')=='true'){ $ret[] = '
                           <a href="'.get_permalink($post->ID).'" class="readmore button">Read More ></a>';
                }
                $ret[] = genesis_markup( array(
                    'html5' => '</footer>',
                    'xhtml' => '</div>',
                    'echo' => false,
                ) );
                $ret[] = genesis_markup( array(
                    'html5' => '</main>',
                    'xhtml' => '</div>',
                    'echo' => false,
                ) );
                $ret[] = genesis_markup( array(
                    'html5' => '</div>',
                    'xhtml' => '</div>',
                    'echo' => false,
                ) );
                $ret[] = genesis_markup( array(
                    'html5' => '</article>',
                    'xhtml' => '</div>',
                    'context' => 'team_member',
                    'echo' => false,
                ) );
            }
            //return
            $post = $origpost;
            return implode("\n",$ret);
        }

        function sort_by_lastname( $a, $b ) {
            return $a->lastname == $b->lastname ? 0 : ( $a->lastname < $b->lastname ) ? -1 : 1;
        }

        function print_shortcode_handler(){
            print $this->shortcode_handler(array());
        }

        /**
         * Callback for dynamic Genesis 'genesis_attr_$context' filter.
         *
         * Add custom attributes for the custom filter.
         *
         * @param array $attributes The element attributes
         * @return array $attributes The element attributes
         */
        function custom_add_team_member_attr( $attributes ){
            $attributes['class'] .= ' equalize col-xs-12 col-sm-6 col-md-4';
            $attributes['itemtype']  = 'http://schema.org/Person';
            // return the attributes
            return $attributes;
        }
    } //End Class
} //End if class exists statement