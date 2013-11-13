<?php
/*
	Section: Testimonials Lud
	Author: bestrag
	Version: 3.1
	Author URI: http://bestrag.net
	Demo: http://bestrag.net/testimonials/
	Description: Testimonials Lud is going to help users manage everything they need when it comes to testimonials management. It is offering custom templating so literally users can make it as they wish. It comes with several built in templates that can be used anywhere on the page.
	Class Name: TestimonialsLud
	Filter: component
*/

class TestimonialsLud extends PageLinesSection {
	var $tabID		= 'testimonials-meta';
	var $name		= 'Testimonials';
	var $taxID		= 'testimonial-sets';
	var $ptID		= 'testimonials';
	var $default_template	= 'sabrine';
	var $container		= 'tls-container';

	/* section_styles */
	function section_scripts() {
		wp_enqueue_script( 'jquery-fred', $this->base_url.'/min.caroufredsel.js', array( 'jquery' ), true );
		wp_enqueue_script( 'jquery-masonry', array( 'jquery' ) );
	}

	/* section_head */
	function section_head() {
		//text style and weight
		$text_italic	= ( $this->opt( 'text_italic' ) ) ? 'italic' : 'normal' ;
		$text_bold	= ( $this->opt( 'text_bold' ) ) ? 'bold' : 'normal' ;
		//jQuery CarouFredCel variables
		$pause				= ( $this->opt( 'pause' ) ) ? $this->opt( 'pause' ) : '5000' ;
		$pause_on_hover		= ( $this->opt( 'pause_on_hover' ) ) ? 'true' : 'false' ;
		$auto				= ( $this->opt( 'tls_auto' ) ) ? 'false' :'true' ;
		$pager				= ( $this->opt( 'pager') ) ? 'true' : 'false' ;
		$controls			= ( $this->opt( 'controls') ) ? 'true' : 'false' ;
		$speed				= ( $this->opt( 'speed' ) ) ? $this->opt( 'speed' ) : '1000' ;
		$mode				= ( $this->opt( 'mode' ) ) ? $this->opt( 'mode' ) : 'scroll' ;
		$disable_animation		= ( $this->opt( 'animation' ) )  ? 'true' : 'false';
		//layout
		$numslides	= ( $this->opt( 'col_num_fred' ) )  ? $this->opt( 'col_num_fred' ) : '1';
		$slide_gutter	= ( $this->opt( 'slide_gutter' ) ) ? $this->opt( 'slide_gutter' ) : '0' ;
		$template	= ( $this->opt( 'template_name' ) ) ? $this->opt( 'template_name' ) : $this->default_template;
		$quotes_img	= ( file_exists($this->base_dir.'/images/'.$template.'-quotes.png') ) ? $this->base_url.'/images/'.$template.'-quotes.png' : $this->base_url.'/images/quotes.png';
		if( $this->opt( 'quotes_img' ) ) $quotes_img = $this->opt( 'quotes_img' );
		//query
		$slides_num	= ( $this->opt( 'slides_num' ) ) ? $this->opt( 'slides_num' ) : -1;
		$params	= array( 'post_type' => $this->ptID, 'order' => 'ASC', 'posts_per_page' => $slides_num );
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

		$query		= null;
		$post_data	= array();
		$query		= new WP_Query( $params );
		if($query->have_posts()){
			while($query->have_posts()){
				$query->the_post();
				$a = get_post_meta(get_the_ID() );
				$a['post_title'][] = get_the_title( );
				$a['post_content'][] = get_the_content( );

				if(array_key_exists('img', $a) && $a['img'][0]) {
					$a['img'][0] = (array_key_exists('demo', $a) && $a['demo'][0]) ?  '<img src="'. $a['img'][0] . '"' :  wp_get_attachment_image($a['img'][0]);
				}
				$a['quotes'] = ($quotes_img) ? '<img src="'.$quotes_img.'">' : '' ;
				foreach ($a as $key => $value) {
					if( array_key_exists($key . '_url', $a)){
						$a[$key][0] = '<a href="' .  $a[$key.'_url'][0] . '">'. $a[$key][0] .'</a>';
					}
				}
				$post_data[] = $a;
			}
		}
		wp_reset_postdata();
		//all you need is JSON
		$post_data		= json_encode($post_data);
		$data_path		= $this->base_dir.'/data/';
		$template_json	= (file_exists($data_path.$template.'.json')) ? file_get_contents($data_path.$template.'.json') : file_get_contents($data_path.'default.json') ;

		?>
		<script type="text/javascript">

			/* <![CDATA[ */
			var $ = jQuery;
			var postData; 
			var template; 	
			var winWidth 		= jQuery(window).width();
			var defItemWidth 	= 465;
			var numslides		= 1;
			var percentArray	= ['100%', '50%', '33.3333%'];
			var sectionClone	= '';
			var container		= '';
			var wraper 		= '';
			var slider		= '';
			var grid			= '';
			var inner		= '';
			var tlsItem		= '';
			var tlsGroup		= '';
			var templateName	= '';
			var tlsItemWidth	= '';
			var tlsItemPercent	= '';
			var tlsItemGutter	= '';
			var calcItemWidth	= '';

			jQuery(document).ready(function(){
				//selectors
				sectionClone		= jQuery('section#testimonials-lud'+'<?php echo $this->meta['clone']; ?>');
				container		= jQuery('.tls-container', sectionClone);
				wraper			= jQuery('.tls-wraper', container);
				//options
				numslides		= <?php print $numslides;?>;
				templateName		=  '<?php print $template;?>'.toLowerCase();
				//get jsons
				template 		= <?php echo $template_json; ?>;
				postData		= <?php echo $post_data; ?>;
				//draw elements
				var index = '';
				jQuery.each(postData, function(key, value){
					index	= key+1;
					li	= '<li class="tls-item tls-item-'+index+'"><div class="tls-item-inner"></div></li>';
					wraper.append(li);
					inner	= jQuery('li:last-child .tls-item-inner',container);
					jQuery.each(template, function(i, val){
						if(jQuery.isArray(val)){
							inner.append('<div id ="tls-group-'+i+'" class="tls-group"></div>');
							tlsGroup = jQuery('.tls-group:last-child', inner);
							jQuery.each(val, function(index, subval) {
								tlsGroup.append('<div class="tls-'+ subval +'">'+ value[subval] +'</div>');
							});
						}
						else{inner.append('<div class="tls-'+ val +'">'+ value[val] +'</div>');}
					});
				});
				//Item css
				tlsItem = jQuery('li', wraper);
				//gutter
				tlsItemGutter = <?php print $slide_gutter; ?> / 2;
				tlsItem.css({
					'padding-left': tlsItemGutter,
					'padding-right': tlsItemGutter,
					'font-style': '<?php print $text_italic;?>',
					'font-weight': '<?php print $text_bold;?>'
				});
			});
			jQuery(window).load(function(){

				/*
				sectionClone                = jQuery('section#testimonials-lud'+'<?php echo $this->meta['clone']; ?>');
		                              container                = jQuery('.tls-container', sectionClone);
		                              wraper                        = jQuery('.tls-wraper', container);
		                               //options
		                              numslides                = <?php print $numslides;?>;
		                              templateName                =  '<?php print $template;?>'.toLowerCase();
		                              tlsItem = jQuery('li', wraper);
				*/

				//responsive classes
				var responsiveClasses = function(){
					var currentItemWidth = tlsItem.width();
					if (400 < currentItemWidth && 600 > currentItemWidth) return container.addClass(templateName + '-c2');
					if (400 > currentItemWidth) return container.addClass(templateName + '-c3');
				}
				//item width and responsive classes - must be loaded before fred/masonry
				if(960 > winWidth){
					if(3 === numslides) numslides = 2;
				}
				if(600 > winWidth){
					numslides = 1;
				}
				calcItemWidth = (container.width()/numslides) - 1;
				//percentItemWidth = percentArray[(numslides-1)];
				tlsItem.css({
					'width' :	calcItemWidth
					//'width' : percentItemWidth
				});
				responsiveClasses();
				//engage
				wraper.imagesLoaded(function(){
					if ( <?php print $disable_animation;?> === true ){

							//masonry
							wraper.masonry({
								//columnWidth: calcItemWidth,
								itemSelector: 'li.tls-item',
								isAnimated: true,
								isFitWidth: true
							});

					}else {
						//fred item width
						fredWidth = defItemWidth;
						if(600 > winWidth) fredWidth = 600;
						//check for front-end controls
						if(<?php print $pager; ?> || <?php print $controls; ?>) jQuery('.tls-controls', container).show(0);
						//fred
						var obj = {
							responsive: true,
							height: "auto",
							items:{
								visible: {
							            	min         : 1,
							            	max         : numslides
							        	},
								width: fredWidth,
							},
							scroll:{
								items: 1,
								fx: "<?php print $mode; ?>",
								duration: <?php print $speed; ?>,
								pauseOnHover: <?php print $pause_on_hover; ?>
							},
							auto:{
								play: <?php print $auto; ?>,
								timeoutDuration: <?php print $pause; ?>,
							},
							pagination: '.tls-pager',
							prev : {	button  : '',},
				    			next : { button  : '',},

						}
						//add arrows
						if(<?php print $controls; ?>) {
							jQuery('.tls-next, .tls-prev', container).show();
							obj.next.button = jQuery('.tls-next', container);
							obj.prev.button = jQuery('.tls-prev', container);
				    		}
				    		if(<?php print $pager; ?>) jQuery('.tls-pager', container).show(0).stop(true, true).delay(600).animate({'opacity': 1}, 400);
						wraper.carouFredSel(obj);
					}
					});
				//show
				setTimeout(function(){wraper.animate({'opacity': 1}, 400);}, 400) ;
			});
			/* ]]> */
		</script>
		<?php
		/* menu font */
		$font_selector = 'section#testimonials-lud'.$this->meta['clone'].' div.tls-container';
		if ( $this->opt( 'text_font' ) ) {
				echo load_custom_font( $this->opt( 'text_font' ), $font_selector );
		}
	}

	function section_template(){
		$template	= ( $this->opt( 'template_name' ) ) ? $this->opt( 'template_name' ) : $this->default_template;
		$animation	= ( $this->opt( 'animation' ) ) ? 'in-grid' : 'fredslider';
		printf('<div class="%s post-id-%s template-%s"><ul class="tls-wraper %s"></ul><div class="tls-controls"><span class="tls-prev"><a class="tls-prev-link" href="#"><i class="icon-chevron-left"></i></a></span><span class="tls-next"><a class="tls-next-link" href="#"><i class="icon-chevron-right"></i></a></span><span class="tls-pager"></span></div></div>', $this->container, $this->ptID, $template, $animation);
	}

	function section_opts() {
		$opts		= array();
		$opts[] = array(
			'key'		=> 'ccname_set',
			'type'		=>  'multi',
			'col'		=> 1,
			'title'		=> __( 'Template & Taxonomy', 'pagelines' ),
			'opts' => array(
				array(
					'key'	=>	'template_name',
					'type'			=> 'select',
					'label'	=> __( 'Choose Template', 'pagelines' ),
					'opts'			=> $this->get_template_selectvalues(),
				),
				array(
					'key'	=>	'taxonomy',
					'type'			=> 'select_taxonomy',
					'taxonomy_id'	=> $this->taxID,
					'label'	=> __( 'Select Testimonial Set (default "all")', 'pagelines' )
				),
				array(
					'key'	=>	'slides_num',
					'type'			=> 'text',
					'label'	=> __( 'Number of testimonials to use (default all)', 'pagelines' ),
				),
				array(
					'key'	=>	'animation',
					'type'       => 'check',
					'label' => __( 'Disable animation (show testimonials in grid)', 'pagelines' ),
				)
			)
		);
		$opts[] = array(
			'key'		=> 'layout_settings',
			'type'		=>  'multi',
			'col'		=> 2,
			'title'		=> __( 'Layout', 'pagelines' ),
			'opts' => array(
				array(
					'key'	=>	'col_num_fred',
					'type'			=> 'count_select',
					'count_start'	=> '1',
					'count_number'	=> '3',
					'label'	=> __( 'Number of columns', 'pagelines' ),
				),
				array(
					'key'	=>	'slide_gutter',
					'type'			=> 'text',
					'label'	=> __( 'Gutter between testimonials (default 0)', 'pagelines' ),
				),
				array(
					'key'		=> 'quotes_img',
					'label' => __( 'Quotation Marks Image (Works with supported templates)', 'pagelines' ),
					'type'       => 'image_upload'
				)
			)
		);
		$opts[] = array(
			'key'		=> 'text_settings',
			'type'		=>  'multi',
			'col'		=> 3,
			'title'		=> __(  'Testimonial Content Options', 'pagelines' ),
			'opts' => array(
				array(
					'key'	=>	'text_italic',
					'type'			=> 'check',
					'label'	=> __( 'Italic text style of testimonial content', 'pagelines' ),
				),
				array(
					'key'	=>	'text_bold',
					'type'			=> 'check',
					'label'	=> __( 'Bold text style of testimonial content', 'pagelines' ),
				),
				array(
					'key'	=>	'text_font',
					'type' 			=> 'type',
					'label'	=> __( 'Choose Testimonial text font', 'pagelines' ),
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
					'key'	=>	'tls_auto',
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
		//add_action( 'template_redirect',array(&$this, 'snav_less') );
		add_filter( 'pl_settings_array', array( &$this, 'get_meta_array' ) );
		add_filter('pless_vars', array(&$this, 'add_less_vars'));
		//set post
		$this->post_type_setup();

		if(!class_exists('RW_Meta_Box')) {
			add_action('admin_notices',  function(){
			echo '<div class="updated">
			   	<p>For the <strong>Testimonials Lud</strong> you need to install the <strong>Meta Box</strong> plugin by <a href="http://www.deluxeblogtips.com/" >Rilwis</a>. It is well tested, <strong>free</strong>, open source solution that will be seamlessly integrated once you install it. <strong>It does not require your attention.</strong>
			   	You can get it from <a href="http://wordpress.org/plugins/meta-box" target="_blank"><strong>here</strong></a>.</p>
				</div>';
			});
		} else  //meta setup
		$this->post_meta_setup();


	}

	/* site options metapanel */
	function get_meta_array( $settings ){

		$settings[ $this->id ] = array(
				'name'  => $this->name,
				//'icon'  => $this->icon,
				'opts'  => $this->sec_site_options()
		);
		return $settings;
	}

	function sec_site_options(){
		$options_array = array(
			array(
				'type' 	=> 	'multi',
				'col'	=> 1,
				'title' => __( 'Background Colors', 'pagelines' ),
				'opts'	=> array(
					array(
						'key'           => 'tls-templatebg',
						'type'       => 'color',
						'label' => __( 'Container Background', 'pagelines' ),
						'default'	=> pl_hashify(pl_setting('bodybg')),
					),
					array(
						'key'           => 'tls-singlebg',
						'type'       => 'color',
						'label' => __( 'Single Testimonial Background', 'pagelines' ),
						'default'	=> '#FFFFFF',
					),
					array(
						'key'           => 'tls-txtcolor',
						'type'          => 'color',
						'label'    => __( 'Content Text Color', 'pagelines' ),
						'default'	=> pl_hashify(pl_setting('text_primary')),
					),
					array(
						'key'           => 'tls-othertxtcolor',
						'type'       => 'color',
						'label' => __( 'Non Content Text Color', 'pagelines' ),
						'default'	=> pl_hashify(pl_setting('text_primary')),
					),
					array(
						'key'           => 'tls-linkcolor',
						'type'       => 'color',
						'label' => __( 'Name & Company Link Color', 'pagelines' ),
						'default'	=> pl_hashify(pl_setting('linkcolor')),
					)
				)
			),
			array(
				'type' 	=> 	'multi',
				'col'	=> 2,
				'title' => __( 'Controls Colors and Size', 'pagelines' ),
				'opts'	=> array(
					array(
						'key'           => 'tls-arrowtcolor',
						'type'          => 'color',
						'label'    => __( 'Arrow Color', 'pagelines' ),
						'default'	=> '#333',
					),
					array(
						'key'           => 'tls-arrowsize',
						'type'          => 'text',
						'label'    => __( 'Arrow size in pixels', 'pagelines' ),
						'default'	=> '62',
					),
					array(
						'key'           => 'tls-pagercolor',
						'type'       => 'color',
						'label' => __( 'Pager Color', 'pagelines' ),
						'default'	=> '#333',
					),
					array(
						'key'           => 'tls-pageractivecolor',
						'type'       => 'color',
						'label' => __( 'Pager Active Color', 'pagelines' ),
						'default'	=> '#FF7F50',
					)
				),
			)
		);
		return $options_array;
	}

	function add_less_vars($vars){
		$vars['tls-templatebg'] 	= ( pl_setting('tls-templatebg') ) ? pl_hashify( pl_setting( 'tls-templatebg' ) ) : pl_hashify(pl_setting('bodybg'));
		$vars['tls-singlebg'] 	= ( pl_setting('tls-singlebg') ) ? pl_hashify( pl_setting( 'tls-singlebg' ) ) : pl_hashify(pl_setting('bodybg'));
		$vars['tls-txtcolor'] 	= ( pl_setting('tls-txtcolor') ) ? pl_hashify( pl_setting( 'tls-txtcolor' ) ) : pl_hashify(pl_setting('text_primary'));
		$vars['tls-linkcolor']	= (pl_setting('tls-linkcolor')) ? pl_hashify(pl_setting('tls-linkcolor')) : pl_hashify(pl_setting('linkcolor'));
		$vars['tls-othertxtcolor']	= ( pl_setting('tls-othertxtcolor') ) ? pl_hashify( pl_setting( 'tls-othertxtcolor' ) ) : pl_hashify(pl_setting('text_primary'));
		$vars['tls-arrowtcolor']	= (pl_setting('tls-arrowtcolor')) ? pl_hashify(pl_setting('tls-arrowtcolor')) : '#333' ;
		$vars['tls-arrowsize']	= (pl_setting('tls-arrowsize')) ? pl_setting('tls-arrowsize').'px' : '62px' ;
		$vars['tls-pagercolor']	= (pl_setting('tls-pagercolor')) ? pl_hashify(pl_setting('tls-pagercolor')) : '#333' ;
		$vars['tls-pageractivecolor']	= (pl_setting('tls-pageractivecolor')) ? pl_hashify(pl_setting('tls-pageractivecolor')) : '#FF7F50' ;
		return $vars;
	}

	function post_meta_setup(){
		add_action( 'admin_init', function(){
			$type_meta_array = array(
			            'settings' => array(
			                'type'         =>  'multi_option',
			                'title'        => __( 'Single Testimonial Options', 'pagelines' ),
			                'shortexp'     => __( 'Parameters', 'pagelines' ),
			                'exp'          => __( '<strong>Single Testimonial Options</strong><br>Add Testimonial Metadata that will be used on the page.<br><strong>HEADS UP:<strong> Each template provides its own set of metadata. Check out <a href="http://bestrag.net/testimonials" target="_blank">demo page</a> for more information.', 'pagelines' ),
			                'selectvalues' => array(
			                    'name' => array(
			                        'type'       => 'text',
			                        'inputlabel' => __( 'Person (name of Testimonial author)', 'pagelines' )
			                    ),
			                    'name_url' => array(
			                        'type'       => 'text',
			                        'inputlabel' => __( "Person's Url ()", 'pagelines' ),
			                    ),
			                    'position' => array(
			                        'type'       => 'text',
			                        'inputlabel' => __( "Person's position in the company", 'pagelines' )
			                    ),
			                    'company' => array(
			                        'type'       => 'text',
			                        'inputlabel' => __( 'Company Name', 'pagelines' )
			                    ),
			                    'company_url' => array(
			                        'type'       => 'text',
			                        'inputlabel' => __( "Company's Url", 'pagelines' ),
			                   ),
			                    'img'  => array(
			                        'inputlabel' => __( "Associate an image with this testimonial", 'pagelines' ),
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
				$figo[$findex] = array( 'name'  => $value['inputlabel'],
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
			        'pages'    => array( 'testimonials' ),
			        'context'  => 'normal',
			        'priority' => 'high',
			        'fields' => $figo
			    );

			 new RW_Meta_Box($metabox);

		});
	}

	function post_type_setup() {
	        $args = array(
	            'label'          => __( $this->name, 'pagelines' ),
	            'singular_label' => __( 'Testimonial', 'pagelines' ),
	            'description'    => __( 'For creating customer testimonials.', 'taxonomies' ),
	            'taxonomies'     => array( $this->taxID ),
	            'menu_icon'      => $this->icon
	        );
	        $taxonomies = array(
	            $this->taxID => array(
	                'label'          => __( 'Testimonial Sets', 'pagelines' ),
	                'singular_label' => __( 'Testimonial Set', 'pagelines' ),
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
	            $this->taxID     => 'Testimonial Sets'
	        );
	        $this->post_type = new PageLinesPostType( $this->ptID, $args, $taxonomies, $columns, array( &$this, 'column_display' ) );
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
		            wp_set_object_terms( $id, 'default-testimonials', $this->taxID );

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
	function snav_less(){
		$template 		= ($this->meta['set']['snav_template']) ? $this->meta['set']['snav_template'] : $this->default_template;
		$template_file 	= sprintf('%s/less/%s.less', $this->base_dir, $template);
		pagelines_insert_core_less( $template_file );
	}
	*/
}//EOC