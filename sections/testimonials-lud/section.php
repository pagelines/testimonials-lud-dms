<?php
/*
	Section: Testimonials Lud
	Author: bestrag
	Version: 3.2.3
	Author URI: http://bestrag.net
	Demo: http://bestrag.net/testimonials-lud/demo/
	Description: Testimonials Lud is going to help users manage everything they need when it comes to testimonials management. It is offering custom templating so literally users can make it as they wish. It comes with several built in templates that can be used anywhere on the page.
	Class Name: TestimonialsLud
	Filter: component
*/

class TestimonialsLud extends PageLinesSection {
	var $lud_opts		= array();
	var $multiple_up	= 'Testimonials';
	var $multiple		= 'testimonials';
	var $single_up		= 'Testimonial';
	var $single		= 'testimonial';
	var $prefix		= 'tls';
	var $taxID		= 'testimonial-sets';
	var $section_id		= 'testimonials-lud';
	var $default_template	= 'sabrine';

	/* section_styles */
	function section_scripts() {
		wp_enqueue_script( 'jquery-fred', $this->base_url.'/min.caroufredsel.js', array( 'jquery' ), true );
		wp_enqueue_script( 'jquery-masonry', array( 'jquery' ) );
		wp_enqueue_script( 'jquery-ludloop', $this->base_url.'/jquery.ludloop.js', array( 'jquery' ), true );
	}

	/* section_head */
	function section_head() {
		$this->lud_opts['template_name']	= ( $this->opt( 'template_name' ) ) ? $this->opt( 'template_name' ) : $this->default_template;
		//text style and weight
		$this->lud_opts['text_italic']	= ( $this->opt( 'text_italic' ) ) ? 'italic' : 'normal' ;
		$this->lud_opts['text_bold']	= ( $this->opt( 'text_bold' ) ) ? 'bold' : 'normal' ;
		//jQuery CarouFredCel variables
		$this->lud_opts['pause']		= ( $this->opt( 'pause' ) ) ? intval($this->opt( 'pause' )) : 4000 ;
		$this->lud_opts['pause_on_hover']	= ( $this->opt( 'pause_on_hover' ) ) ? true : false ;
		$this->lud_opts['auto']		= ( $this->opt( 'auto' ) ) ? false : true ;
		$this->lud_opts['speed']		= ( $this->opt( 'speed' ) ) ? intval($this->opt( 'speed' )) : 500 ;
		$this->lud_opts['mode']		= ( $this->opt( 'mode' ) ) ? $this->opt( 'mode' ) : 'scroll' ;
		//controls/pager
		$this->lud_opts['pager']		= ( $this->opt( 'pager') ) ? true : false ;
		$this->lud_opts['controls']	= ( $this->opt( 'controls') ) ? true : false ;
		//fred vs masonry
		$this->lud_opts['enable_animation']	= ( $this->opt( 'animation' ) )  ? false : true;
		$this->lud_opts['use_link']	= false;
		//layout
		$this->lud_opts['numslides']	= ( $this->opt( 'col_num' ) )  ? intval($this->opt( 'col_num' )) : 1;
		$this->lud_opts['slide_gutter']	= ( $this->opt( 'slide_gutter' ) ) ? $this->opt( 'slide_gutter' ).'px' : '0';
		$this->lud_opts['equal_height']		= ( $this->opt( 'equal_height') ) ? false : true ;
		//all you need is json
		$lud_opts	= json_encode($this->lud_opts);
		?>
		<script type="text/javascript">
			/* <![CDATA[ */
			//var $ = jQuery;
			var ludOpts 	= {};
			var ludSelectors	= {};
			jQuery(document).ready(function(){
				//selectors
				var cloneID 		= '<?php echo $this->meta['clone']; ?>';
				var sectionPrefix	= '<?php echo $this->prefix; ?>';
				var sectionClone	= jQuery('section#'+'<?php echo $this->section_id; ?>' + cloneID);
				ludSelectors[cloneID] = {
					'sectionPrefix'	: sectionPrefix,
					'sectionClone'	: sectionClone,
					'container'	: jQuery('.'+sectionPrefix+'-container', sectionClone),
					'wraper'		: jQuery('.'+sectionPrefix+'-wraper', sectionClone),
					'ludItem'	: jQuery('.'+sectionPrefix+'-item', sectionClone),
					'inner'		: jQuery('.'+sectionPrefix+'-item-inner', sectionClone),
					'pager' 		: jQuery('.'+sectionPrefix+'-pager', sectionClone),
					'prev'		: jQuery('.'+sectionPrefix+'-prev', sectionClone),
					'next'		: jQuery('.'+sectionPrefix+'-next', sectionClone)
				};
				//get options
				ludOpts[cloneID]	= <?php echo $lud_opts; ?>;
				//style and classes
				ItemStyle();
				responsiveClasses();
				//functions
				function ItemStyle (){
					ludSelectors[cloneID]['ludItem'].css({
						'padding-left'	: ludOpts[cloneID]['slide_gutter'],
						'padding-right'	: ludOpts[cloneID]['slide_gutter'],
						'font-style'	: ludOpts[cloneID]['text_italic'],
						'font-weight'	: ludOpts[cloneID]['text_bold']
					});
				}
				function responsiveClasses (){
					if(960 > ludSelectors[cloneID]['container'].width()){
						if(ludOpts[cloneID]['numslides'] === 3) ludOpts[cloneID]['numslides'] = 2;
					}
					if(600 > ludSelectors[cloneID]['container'].width()){
						ludOpts[cloneID]['numslides'] = 1;
					}

					var calcItemWidth = (ludSelectors[cloneID]['container'].width()/ludOpts[cloneID]['numslides']) - 1;
					ludSelectors[cloneID]['ludItem'].css({
						'width' :	calcItemWidth
					});
					ludOpts[cloneID]['itemWidth'] = calcItemWidth;
					if (400 < calcItemWidth && 600 > calcItemWidth) return ludSelectors[cloneID]['container'].addClass(ludOpts[cloneID]['template_name'] + '-c2');
					if (400 > calcItemWidth) return ludSelectors[cloneID]['container'].addClass(ludOpts[cloneID]['template_name'] + '-c3');
				}
			});
			jQuery(window).load(function(){
				cloneID 		= '<?php echo $this->meta['clone']; ?>';
				//engage
				ludSelectors[cloneID]['wraper'].ludLoop(ludSelectors[cloneID], ludOpts[cloneID]);
				//show
				ludSelectors[cloneID]['container'].animate({'height':'100%'},400);
				ludSelectors[cloneID]['wraper'].animate({'opacity':1},400);
			});
			/* ]]> */
		</script>
		<?php
		/* font */
		$font_selector = 'section#'.$this->section_id.$this->meta['clone'].' div.'.$this->prefix.'-container';
		if ( $this->opt( 'text_font' ) ) {
			echo load_custom_font( $this->opt( 'text_font' ), $font_selector );
		}
	}

	function section_template(){
		//params
		$template_name = ( $this->opt( 'template_name' ) ) ? $this->opt( 'template_name' ) : $this->default_template;
		$use_link	= false;
		$animation	= ( $this->opt( 'animation' ) ) ? 'in-grid' : 'fredslider';
		$quotes_img	= ( $this->opt( 'quotes_img' ) ) ? $this->opt( 'quotes_img' ) : $this->base_url.'/images/quotes.png';
		//template json
		$data_path	= $this->base_dir.'/data/';
		$template_json	= (file_exists($data_path.$template_name.'.json')) ? file_get_contents($data_path.$template_name.'.json') : file_get_contents($data_path.'default.json') ;
		$template_json  = json_decode($template_json);
		//query params
		$slides_num	= ( $this->opt( 'slides_num' ) ) ? $this->opt( 'slides_num' ) : '-1';
		$orderby	= ( $this->opt( 'orderby' ) ) ? $this->opt( 'orderby' ) : '';
		$order		= ( $this->opt( 'order' ) ) ? $this->opt( 'order' ) : 'DESC';
		$params	= array( 'post_type' => $this->multiple, 'orderby' => $orderby, 'order' => $order, 'posts_per_page' => $slides_num );
		$taxonomy	= ( $this->opt( 'taxonomy' ) ) ? $this->opt( 'taxonomy' ) : null ;
		if ( $taxonomy ) {
			$query_tax = array(
				array(
					'taxonomy' => $this->taxID,
					'field'    => 'slug',
					'terms'    => array( $taxonomy )
				)
			);
			$params['tax_query'] = $query_tax;
		}
		//query
		$post_data	= array();
		$query		= null;
		$query		= new WP_Query( $params );
		$index 		= 0;
		//collect all posts
		$all_posts	= '';
		if($query->have_posts()){
			while($query->have_posts()){
				$query->the_post();
				//get post data for every post
				$a = get_post_meta(get_the_ID() );
				$a['post_title'][] = get_the_title( );
				$a['post_content'][] = get_the_content( );
				$a['post_url'][] = get_post_permalink();
				if(array_key_exists('img', $a) && $a['img'][0]) {
					$a['img'][0] = (array_key_exists('demo', $a) && $a['demo'][0]) ?  '<img src="'. $a['img'][0] . '">' :  wp_get_attachment_image($a['img'][0]);
				}
				$a['quotes'][] = ($quotes_img) ? '<img src="'.$quotes_img.'">' : '' ;
				//add link where needed
				foreach ($a as $key => $value) {
					if( array_key_exists($key . '_url', $a)){
						$a[$key][0] = '<a href="' .  $a[$key.'_url'][0] . '">'. $a[$key][0] .'</a>';
					}
				}
				$post_data[] = $a;
				//render elements
				$all_elems = '';
				foreach ($template_json as $key => $value) {
					$key++;
					//template - if array in array
					if(is_array($value)){
						$group_elems = '';
						//elements
						foreach ($value as $i => $val) {
							$group_elem = sprintf('<div class="%1$s-%2$s">%3$s</div>',$this->prefix, $val, $post_data[$index][$val][0] );
							$group_elems .= $group_elem;
						}
						//wrap elements
						$group = sprintf('<div id ="%1$s-group-%2$s" class="%1$s-group">%3$s</div>', $this->prefix, $key, $group_elems);
						$all_elems .= $group;
					}else{
						$elem = sprintf('<div class="%1$s-%2$s">%3$s</div>',$this->prefix, $value, $post_data[$index][$value][0] );
						$all_elems .= $elem;
					}
				}
				//add link to item -testimonials has no link
				$link_index = $index + 1;
				$a_open  = '';
				$a_close  = '';
				//wrap elements in <li>
				$index++;
				$all_posts .= sprintf('<li class="%1$s-item %1$s-item-%2$s">%4$s<div id="%1$s-inner-%2$s" class="%1$s-item-inner">%3$s</div>%5$s</li>', $this->prefix, $index, $all_elems, $a_open, $a_close);
			}
		}
		wp_reset_postdata();
		//add controls
		$controls = ('fredslider' === $animation) ?
			sprintf(
				'<span class="%1$s-prev">
					<a class="%1$s-prev-link" href="#"><i class="icon-chevron-left"></i></a>
				</span>
				<span class="%1$s-next">
					<a class="%1$s-next-link" href="#"><i class="icon-chevron-right"></i></a>
				</span>
				<div class="%1$s-pager"></div>'
			, $this->prefix)	: null;
		//wrap it up
		$ludloop = sprintf('<div class="%1$s-container post-id-%2$s template-%3$s"><ul class="%1$s-wraper %4$s">%5$s</ul>%6$s</div>',
			$this->prefix,
			$this->multiple,
			$template_name,
			$animation,
			$all_posts,
			$controls
		);
		//print
		echo do_shortcode($ludloop );
	}

	function section_opts() {
		$opts		= array();
		$opts[] = array(
			'key'		=> 'ccname_set',
			'type'		=>  'multi',
			'col'		=> 1,
			'title'		=> __( 'General settings', 'pagelines' ),
			'opts' => array(
				array(
					'key'	=>	'template_name',
					'type'		=> 'select',
					'label'	=> __( 'Choose Template', 'pagelines' ),
					'opts'		=> $this->get_template_selectvalues(),
					'compile'	=> true
				),
				array(
					'key'	=>	'taxonomy',
					'type'			=> 'select_taxonomy',
					'taxonomy_id'	=> $this->taxID,
					'label'	=> __( 'Select '.$this->single_up.' Set', 'pagelines' )
				),
				array(
					'key'	=>	'slides_num',
					'type'			=> 'text',
					'label'	=> __( 'Number of '.$this->multiple.' to use (default all)', 'pagelines' ),
				),
				array(
					'key'	=>	'order',
					'type'		=> 'select',
					'label'	=> __( 'Order of '.$this->multiple, 'pagelines' ),
					'opts'	=> array(
						'ASC'		=> array( 'name' => __( 'Ascending', 'pagelines' ) ),
						'DESC'		=> array( 'name' => __( 'Descending (default)', 'pagelines' ) ),
					)
				),
				array(
					'key'	=>	'orderby',
					'type'		=> 'select',
					'label'	=> __( 'Orderby', 'pagelines' ),
					'opts'	=> array(
						'title'		=> array( 'name' => __( 'Order by title.', 'pagelines' ) ),
						'name'		=> array( 'name' => __( 'Order by post name (post slug).', 'pagelines' ) ),
						'date'		=> array( 'name' => __( 'Order by date.', 'pagelines' ) ),
						'modified'	=> array( 'name' => __( 'Order by last modified date.', 'pagelines' ) ),
						'ID'		=> array( 'name' => __( 'Order by post id.', 'pagelines' ) ),
						'author'		=> array( 'name' => __( 'Order by author.', 'pagelines' ) ),
						'none'		=> array( 'name' => __( 'No order.', 'pagelines' ) ),
					)
				)
			)
		);
		$opts[] = array(
			'key'		=> 'layout_settings',
			'type'		=>  'multi',
			'col'		=> 2,
			'title'		=> __( 'Layout & Query Params', 'pagelines' ),
			'opts' => array(
				array(
					'key'	=>	'col_num',
					'type'			=> 'select',
					'label'	=> __( 'Number of columns', 'pagelines' ),
					'opts'	=> array(
						'1'	=> array( 'name' => __( '1', 'pagelines' ) ),
						'2'	=> array( 'name' => __( '2', 'pagelines' ) ),
						'3'	=> array( 'name' => __( '3', 'pagelines' ) ),
					)
				),
				array(
					'key'	=>	'slide_gutter',
					'type'			=> 'text',
					'label'	=> __( 'Gutter between '.$this->multiple.' (default 0)', 'pagelines' ),
				),
				array(
					'key'	=>	'animation',
					'type'	=> 'check',
					'label'	=> __( 'Disable animation (show '.$this->multiple.' in grid)', 'pagelines' ),
				),
				array(
					'key'	=>	'equal_height',
					'type'			=> 'check',
					'label'	=> __( 'Enable variable items height.', 'pagelines' ),
					'ref'	=> __( '<span style:"font-weight:bold;">By default:<br>
							'.$this->multiple.' heights are equalized.<br>
							With animation disabled, '.$this->multiple.' are tiled in rows.<br>
							With animation enabled, carousel has fixed heght', 'pagelines' ),
				)
			)
		);
		$opts[] = array(
			'key'		=> 'text_settings',
			'type'		=>  'multi',
			'col'		=> 3,
			'title'		=> __(  $this->single_up.' Content Options', 'pagelines' ),
			'opts' => array(
				array(
					'key'	=>	'text_italic',
					'type'			=> 'check',
					'label'	=> __( 'Italic text style of '.$this->single.' content', 'pagelines' ),
				),
				array(
					'key'	=>	'text_bold',
					'type'			=> 'check',
					'label'	=> __( 'Bold text style of '.$this->single.' content', 'pagelines' ),
				),
				array(
					'key'	=>	'text_font',
					'type' 			=> 'type',
					'label'	=> __( 'Choose '.$this->single_up.' text font', 'pagelines' ),
				)
			)
		);

		$opts[] = array(
			'key'		=> 'rest',
			'type'		=>  'multi',
			'col'		=> 1,
			'title'		=> __(  $this->multiple_up.' Dcumentation', 'pagelines' ),
			'opts' => array(
				array(
					'key'		=> $this->prefix.'_docs',
					'type'		=> 'link',
					'classes'		=> 'btn-info',
					'url'		=> 'http://bestrag.net/testimonials-lud/docs',
					'label'		=> __( 'Docs and Config', 'pagelines' )
				)
			)
		);
		$opts[] = array(
			'key'		=> 'control_settings',
			'type'		=>  'multi',
			'col'		=> 3,
			'title'		=> __( 'Controls', 'pagelines' ),
			'opts' => array(
				array(
					'key'	=>	'auto',
					'type'			=> 'check',
					'label'	=> __( 'Enable manual transition mode', 'pagelines' ),
				),
				array(
					'key'	=>	'pause_on_hover',
					'type'			=> 'check',
					'label'	=> __( 'Enable pause on hover', 'pagelines' ),
				),
				array(
					'key'	=>	'controls',
					'type'			=> 'check',
					'label'	=> __( 'Enable controls (arrows)', 'pagelines' ),
				),
				array(
					'key'	=>	'pager',
					'type'			=> 'check',
					'label'	=> __( 'Show pager', 'pagelines' ),
				)
			)
		);
		$opts[] = array(
			'key'		=> 'trans_settings',
			'type'		=>  'multi',
			'col'		=> 2,
			'title'		=> __( 'Transition', 'pagelines' ),
			'opts' => array(
				array(
					'key'	=>	'mode',
					'type'			=> 'select',
					'label'	=> __( 'Choose Transition Effect', 'pagelines' ),
					'opts'	=> array(
						'scroll'		=> array( 'name' => __( 'Scroll', 'pagelines' ) ),
						'fade'			=> array( 'name' => __( 'Fade', 'pagelines' ) ),
						'cover'			=> array( 'name' => __( 'Cover', 'pagelines' ) ),
						'cover-fade'	=> array( 'name' => __( 'Cover - Fade', 'pagelines' ) ),
						'uncover'		=> array( 'name' => __( 'Uncover', 'pagelines' ) ),
						'uncover-fade'	=> array( 'name' => __( 'Uncover - Fade', 'pagelines' ) ),
					)
				),
				array(
					'key'	=>	'pause',
					'type'	=> 'text',
					'label'	=> __( 'Pause timeout in milliseconds (default 5000)', 'pagelines' ),
				),
				array(
					'key'	=>	'speed',
					'type'			=> 'text',
					'label'	=> __( 'Transition Speed in milliseconds (default 500)', 'pagelines' ),
				),
			)
		);
		return $opts;
	}

	//template list for section_opts()
	function get_template_selectvalues(){
		$dir 	= $this->base_dir.'/templates/';
		$files = glob($dir.'*.less');
		$array 	= array();
		foreach ($files as $filename) {
			$file 		= basename($dir.$filename, ".less");
			$array[$file] 	= array( 'name' => $file );
		}
		return $array;
	}

	function section_persistent(){
		//add_action( 'template_redirect',array(&$this, 'testimonials_lud_less') );
		add_filter( 'pl_settings_array', array( &$this, 'get_meta_array' ) );
		add_filter('pless_vars', array(&$this, 'add_less_vars'));
		//set post
		$this->post_type_setup();
		if(!class_exists('RW_Meta_Box')) {
			add_action( 'admin_notices',array(&$this, 'testimonials_lud_notice') );
		} else  //meta setup
			add_action( 'admin_init',array(&$this, 'post_meta_setup') );
	}

	/* admin notice */
	function testimonials_lud_notice(){
		echo '<div class="updated">
		   	<p>For the <strong>Testimonials Lud</strong> you need to install the <strong>Meta Box</strong> plugin by <a href="http://www.deluxeblogtips.com/" >Rilwis</a>. It is well tested, <strong>free</strong>, open source solution that will be seamlessly integrated once you install it. <strong>It does not require your attention.</strong>
		   	You can get it from <a href="http://wordpress.org/plugins/meta-box" target="_blank"><strong>here</strong></a>.</p>
		</div>';
	}

	/* settings metapanel */
	function get_meta_array( $settings ){
		$settings[ $this->id ] = array(
				'name'  => $this->multiple_up.' Lud',
				//'icon'  => $this->icon,
				'opts'  => $this->sec_site_options()
		);
		return $settings;
	}
	/* settings metapanel options */
	function sec_site_options(){
		$options_array = array(
			array(
				'type' 	=> 	'multi',
				'col'	=> 1,
				'title' => __( 'Background Colors', 'pagelines' ),
				'opts'	=> array(
					array(
						'key'           => $this->prefix.'-templatebg',
						'type'       => 'color',
						'label' => __( 'Container Background', 'pagelines' ),
						//'default'	=> '#',
						'default'	=> pl_setting('bodybg'),
					),
					array(
						'key'           => $this->prefix.'-singlebg',
						'type'       => 'color',
						'label' => __( 'Single '.$this->single_up.' Background', 'pagelines' ),
						//'default'	=> '#',
						'default'	=> pl_setting('bodybg'),
					),
					array(
						'key'           => $this->prefix.'-txtcolor',
						'type'          => 'color',
						'label'    => __( 'Testimonial Text Color', 'pagelines' ),
						//'default'	=> '#',
						'default'	=> pl_setting('text_primary'),
					),
					array(
						'key'           => $this->prefix.'-othertxtcolor',
						'type'       => 'color',
						'label' => __( 'Meta Fields Text Color', 'pagelines' ),
						//'default'	=> '#',
						'default'	=> pl_setting('text_primary'),
					),
					array(
						'key'           => $this->prefix.'-linkcolor',
						'type'       => 'color',
						'label' => __( 'Name & Company Link Color', 'pagelines' ),
						//'default'	=> '#',
						'default'	=> pl_setting('linkcolor'),
					)
				)
			),
			array(
				'type' 	=> 	'multi',
				'col'	=> 2,
				'title' => __( 'Controls Colors and Size', 'pagelines' ),
				'opts'	=> array(
					array(
						'key'           => $this->prefix.'-arrowtcolor',
						'type'          => 'color',
						'label'    => __( 'Arrow Color', 'pagelines' ),
						//'default'	=> '#',
						'default'	=> '#333',
					),
					array(
						'key'           => $this->prefix.'-arrowsize',
						'type'          => 'text',
						'label'    => __( 'Arrow size in pixels', 'pagelines' ),
						//'default'	=> '#',
						'default'	=> '62',
					),
					array(
						'key'           => $this->prefix.'-pagercolor',
						'type'       => 'color',
						'label' => __( 'Pager Color', 'pagelines' ),
						//'default'	=> '#',
						'default'	=> '#333',
					),
					array(
						'key'           => $this->prefix.'-pageractivecolor',
						'type'       => 'color',
						'label' => __( 'Pager Active Color', 'pagelines' ),
						//'default'	=> '#',
						'default'	=> '#FF7F50',
					)
				),
			)
		);
		return $options_array;
	}

	function add_less_vars($vars){
		$vars[$this->prefix.'-templatebg']	= ( pl_setting($this->prefix.'-templatebg') )	? pl_hashify( pl_setting( $this->prefix.'-templatebg' ) ) : pl_hashify(pl_setting('bodybg')) ;
		$vars[$this->prefix.'-singlebg']		= ( pl_setting($this->prefix.'-singlebg') )		? pl_hashify( pl_setting( $this->prefix.'-singlebg' ) ) : pl_hashify(pl_setting('bodybg'));
		$vars[$this->prefix.'-txtcolor']		= ( pl_setting($this->prefix.'-txtcolor') )		? pl_hashify( pl_setting( $this->prefix.'-txtcolor' ) ) : pl_hashify(pl_setting('text_primary'));
		$vars[$this->prefix.'-linkcolor']		= (pl_setting($this->prefix.'-linkcolor'))		? pl_hashify(pl_setting($this->prefix.'-linkcolor')) : pl_hashify(pl_setting('linkcolor'));
		$vars[$this->prefix.'-othertxtcolor']	= ( pl_setting($this->prefix.'-othertxtcolor') )	? pl_hashify( pl_setting( $this->prefix.'-othertxtcolor' ) ) : pl_hashify(pl_setting('text_primary'));
		$vars[$this->prefix.'-arrowtcolor']	= (pl_setting($this->prefix.'-arrowtcolor'))	? pl_hashify(pl_setting($this->prefix.'-arrowtcolor')) : '#333';
		$vars[$this->prefix.'-arrowsize']		= (pl_setting($this->prefix.'-arrowsize'))		? pl_setting($this->prefix.'-arrowsize').'px' : '62px';
		$vars[$this->prefix.'-pagercolor']	= (pl_setting($this->prefix.'-pagercolor'))	? pl_hashify(pl_setting($this->prefix.'-pagercolor')) : '#333';
		$vars[$this->prefix.'-pageractivecolor']	= (pl_setting($this->prefix.'-pageractivecolor'))	? pl_hashify(pl_setting($this->prefix.'-pageractivecolor')) : '#FF7F50';
		return $vars;
	}

	function post_meta_setup(){
		$type_meta_array = array(
			'settings' => array(
				'type'         =>  'multi_option',
				'title'        => __( 'Single '.$this->single_up.' Options', 'pagelines' ),
				'shortexp'     => __( 'Parameters', 'pagelines' ),
				'exp'          => __( '<strong>Single '.$this->single_up.' Options</strong><br>Add '.$this->single_up.' Metadata that will be used on the page.<br><strong>HEADS UP:<strong> Each template uses different set of metadata. Check out <a href="http://bestrag.net/'.$this->multiple.'-lud" target="_blank">demo page</a> for more information.', 'pagelines' ),
				'selectvalues' => array(
					'name' => array(
						'type'       => 'text',
						'inputlabel' => __( 'Person (name of '.$this->single_up.' author)', 'pagelines' )
					),
					'name_url' => array(
						'type'       => 'text',
						'inputlabel' => __( 'Person\'s Url ()', 'pagelines' ),
					),
					'position' => array(
						'type'       => 'text',
						'inputlabel' => __( 'Person\'s position in the company', 'pagelines' )
					),
					'company' => array(
						'type'       => 'text',
						'inputlabel' => __( 'Company Name', 'pagelines' )
					),
					'company_url' => array(
						'type'       => 'text',
						'inputlabel' => __( "Company Url", 'pagelines' ),
					),
					'img'  => array(
						'inputlabel' => __( 'Associate an image with this '.$this->single, 'pagelines' ),
						'type'       => 'thickbox_image'
					),
					'custom_text1' => array(
						'type'       => 'text',
						'inputlabel' => __( 'Custom Text 1', 'pagelines' )
					),
					'custom_text2' => array(
						'type'       => 'text',
						'inputlabel' => __( 'Custom Text 2', 'pagelines' )
					)
				)
			)
		 );

		$fields = $type_meta_array['settings']['selectvalues'];
		$figo = array(); $findex = 0;

		foreach ($fields as $key => $value) {
			$figo[$findex] = array(
				'name'  => $value['inputlabel'],
				'id'    => $key,
				'type'  => $value['type'],
				'std'   => '',
				'class' => 'custom-class',
				'clone' => false);
			$findex++;
		}
		$metabox = array(
			'id'       => 'personal',
			'title'    => 'Personal Information',
			'pages'    => array( $this->multiple ),
			'context'  => 'normal',
			'priority' => 'high',
			'fields' => $figo
		);
		 new RW_Meta_Box($metabox);
	}

	function post_type_setup() {
		$public_pt = false;
		$args = array(
			'label'          => __( $this->multiple_up, 'pagelines' ),
			'singular_label' => __( $this->single_up, 'pagelines' ),
			'description'    => __( 'For creating '.$this->multiple.' items.', 'taxonomies' ),
			'taxonomies'     => array( $this->taxID ),
			'menu_icon'      => $this->icon,
			'public' 			=> $public_pt,
			'show_ui' 		=> true,
			'hierarchical' 		=> true,
			'featured_image'	=> true,
			'has_archive'		=> true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 20,
			'can_export'          => true,

		);
		$taxonomies = array(
			$this->taxID => array(
				'label'          => __( $this->single_up.' Sets', 'pagelines' ),
				'singular_label' => __( $this->single_up.' Set', 'pagelines' ),
			)
		);
		$columns = array(
			'cb'             => "<input type=\"checkbox\" />",
			'title'          => 'Title',
			'client'         => 'Client Name',
			'description'    => 'Description',
			'media'          => 'Media',
			'client-company' => 'Client Company',
			'client-web'     => 'Client Web Page',
			$this->taxID     => $this->single_up.' Sets',
		);
		$this->post_type = new PageLinesPostType( $this->multiple, $args, $taxonomies, $columns, array( &$this, 'column_display' ) );
		 // Defaults
		$this->post_type->set_default_posts( 'bestrag_default_testimonials', $this );
	}

	function bestrag_default_testimonials($post_type){
		$def_posts  = array(
			array(
				'title'         =>   'This is called Agility',
				'content'       =>   'Since we are using Lorem ipsum dolor sit amet, con se ctetur adip is cing elit, sed do eiusmod tempor in cididunt ut labore et do lore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea. Give us a try!',
				'name'          =>   'Jenny Doe',
				'name_url'      =>   'http://bestrag.net',
				'company_url'   =>   'http://www.pagelines.com',
				'position'      =>   'CEO',
				'company'       =>   'NorthWest',
				'custom_text1'  =>   'Simply Amazing',
				'custom_text2'  =>   'Fast Response',
				'img'           =>   $this->base_url.'/images/person1.jpg',
				'demo'		=> true
			),
			array(
				'title'         =>   'What a great solution',
						'content'       =>   'Since we are using Lorem ipsum dolor sit amet, con se ctetur adip is cing elit, sed do eiusmod tempor in cididunt ut labore et do lore magna aliqua. Ut enim ad minim veniam. Give us a try!',
						'name'          =>   'Johnny Doel',
						'name_url'      =>   'http://bestrag.net',
						'company_url'   =>   'http://www.pagelines.com',
						'position'      =>   'Lead Tech',
						'company'       =>   'SouthEast',
						'custom_text1'  =>   'Highly Recommended',
						'custom_text2'  =>   'Always Here',
						'img'           =>   $this->base_url.'/images/person2.jpg',
						'demo'		=> true
			),
			array(
				'title'         =>   'Simply through the day',
						'content'       =>   'Since we are using Lorem ipsum dolor sit amet, con se ctetur adip is cing elit, sed do eiusmod tempor in cididunt ut labore et do lore magna aliqua. Ut enim ad minim veniam. Give us a try! Since we are using Lorem ipsum. Give us a try!',
						'name'          =>   'Jessica Doely',
						'name_url'      =>   'http://bestrag.net',
						'company_url'   =>   'http://www.pagelines.com',
						'position'      =>   'Creative Director',
						'company'       =>   'GlobalCo',
						'custom_text1'  =>   'Amazing Services',
						'custom_text2'  =>   'Everything is perfect',
						'img'           =>   $this->base_url.'/images/person3.jpg',
						'demo'		=> true
			)
		);
		foreach( $def_posts as $p ){

			$defaults                 = array();
			$defaults['post_title']   = $p['title'];
			$defaults['post_content'] = $p['content'];
			$defaults['post_type']    = $post_type;
			$defaults['post_status']  = 'publish';
			$id                       = wp_insert_post( $defaults );
			update_post_meta( $id, 'name', $p['name'] );
			update_post_meta( $id, 'name_url', $p['name_url'] );
			update_post_meta( $id, 'position', $p['position'] );
			update_post_meta( $id, 'company', $p['company'] );
			update_post_meta( $id, 'company_url', $p['company_url'] );
			update_post_meta( $id, 'custom_text1', $p['custom_text1'] );
			update_post_meta( $id, 'custom_text2', $p['custom_text2'] );
			update_post_meta( $id, 'img', $p['img'] );
			 update_post_meta( $id, 'demo', $p['demo'] );
			wp_set_object_terms( $id, 'default-'.$this->multiple, $this->taxID );
		}
	}

	function column_display( $column ) {
		global $post;
		switch ( $column ) {
		case 'client':
			if ( get_post_meta( $post->ID, 'name', true ) )
				echo get_post_meta( $post->ID, 'name', true );
			break;
		case 'description':
			echo the_excerpt();
			break;
		case 'media':
			 if ( get_post_meta( $post->ID, 'img', true ) ){
				echo '<img src="'.get_post_meta( $post->ID, 'img', true ).'" style="max-width: 80px; margin: 10px; border: 1px solid #ccc; padding: 5px; background: #fff" />';
			}
			else {
				if ( has_post_thumbnail( $post->ID ) ){ echo get_the_post_thumbnail( $post->ID, array(80,80) ); }
			}
			break;
		case 'client-company':
			if ( get_post_meta( $post->ID, 'company', true ) )
				echo get_post_meta( $post->ID, 'company', true );
			break;
		case 'client-web':
			if ( get_post_meta( $post->ID, 'position', true ) )
				echo get_post_meta( $post->ID, 'position', true );
			break;
		case $this->taxID:
			echo get_the_term_list( $post->ID, $this->taxID, '', ', ', '' );
			break;
		}
	}
/*
	//handle less template
	function testimonials_lud_less(){
		$template	= (isset( $this->meta['set']['template_name'])) ? $this->meta['set']['template_name'] : $this->default_template;
		$template_file 	= sprintf('%s/templates/%s.less', $this->base_dir, $template);
		pagelines_insert_core_less( $template_file );
	}
*/
}//EOC