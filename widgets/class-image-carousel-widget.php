<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

class SEIC_Image_Carousel_Widget extends Widget_Base {

	public function get_name() { return 'seic_image_carousel'; }

	public function get_title() { return esc_html__( 'Simple Image Carousel', 'seic' ); }

	public function get_icon() { return 'eicon-slider-push'; }

	public function get_categories() { return array( 'general' ); }

	public function get_keywords() { return array( 'carousel', 'slider', 'images', 'swiper' ); }

	public function get_style_depends() { return array( 'seic-carousel' ); }

	public function get_script_depends() { return array( 'seic-carousel' ); }

	protected function register_controls() {

		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Image Carousel', 'seic' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'carousel_name',
			array(
				'label'   => esc_html__( 'Carousel Name', 'seic' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Image Carousel', 'seic' ),
			)
		);

		$this->add_control(
			'images',
			array(
				'label'   => esc_html__( 'Add Images', 'seic' ),
				'type'    => Controls_Manager::GALLERY,
				'default' => array(),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'    => 'image',
				'default' => 'large',
			)
		);

		$this->add_responsive_control(
			'slides_to_show',
			array(
				'label'   => esc_html__( 'Slides to Show', 'seic' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 3,
				'options' => array(
					1 => 1,
					2 => 2,
					3 => 3,
					4 => 4,
					5 => 5,
					6 => 6,
					7 => 7,
					8 => 8,
					9 => 9,
					10 => 10,
				),
			)
		);

		$this->add_responsive_control(
			'slides_to_scroll',
			array(
				'label'   => esc_html__( 'Slides to Scroll', 'seic' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 1,
				'options' => array(
					1 => 1,
					2 => 2,
					3 => 3,
					4 => 4,
					5 => 5,
					6 => 6,
					7 => 7,
					8 => 8,
					9 => 9,
					10 => 10,
				),
			)
		);

		$this->add_responsive_control(
			'space_between',
			array(
				'label'   => esc_html__( 'Space Between', 'seic' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'devices' => array( 'desktop', 'tablet', 'mobile' ),
				'desktop_default' => array(
					'size' => 20,
					'unit' => 'px',
				),
				'tablet_default' => array(
					'size' => 10,
					'unit' => 'px',
				),
				'mobile_default' => array(
					'size' => 10,
					'unit' => 'px',
				),
			)
		);

		$this->add_control(
			'image_stretch',
			array(
				'label'   => esc_html__( 'Image Stretch', 'seic' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'no',
				'options' => array(
					'no'  => esc_html__( 'No', 'seic' ),
					'yes' => esc_html__( 'Yes', 'seic' ),
				),
			)
		);

		$this->add_control(
			'navigation',
			array(
				'label'   => esc_html__( 'Navigation', 'seic' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'both',
				'options' => array(
					'both'   => esc_html__( 'Arrows and Dots', 'seic' ),
					'arrows' => esc_html__( 'Arrows', 'seic' ),
					'dots'   => esc_html__( 'Dots', 'seic' ),
					'none'   => esc_html__( 'None', 'seic' ),
				),
			)
		);

		$this->add_control(
			'previous_arrow_icon',
			array(
				'label'   => esc_html__( 'Previous Arrow Icon', 'seic' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-chevron-left',
					'library' => 'fa-solid',
				),
				'condition' => array(
					'navigation' => array( 'both', 'arrows' ),
				),
			)
		);

		$this->add_control(
			'next_arrow_icon',
			array(
				'label'   => esc_html__( 'Next Arrow Icon', 'seic' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-chevron-right',
					'library' => 'fa-solid',
				),
				'condition' => array(
					'navigation' => array( 'both', 'arrows' ),
				),
			)
		);

		$this->add_control(
			'link_behavior',
			array(
				'label'   => esc_html__( 'Link', 'seic' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => array(
					'none'   => esc_html__( 'None', 'seic' ),
					'media'  => esc_html__( 'Media File', 'seic' ),
					'custom' => esc_html__( 'Custom URL', 'seic' ),
				),
			)
		);

		$this->add_control(
			'caption',
			array(
				'label'   => esc_html__( 'Caption', 'seic' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => array(
					'none'        => esc_html__( 'None', 'seic' ),
					'title'       => esc_html__( 'Title', 'seic' ),
					'caption'     => esc_html__( 'Caption', 'seic' ),
					'description' => esc_html__( 'Description', 'seic' ),
				),
			)
		);

		$this->end_controls_section();

		// Additional Settings Section
		$this->start_controls_section(
			'section_additional_options',
			array(
				'label' => esc_html__( 'Additional Options', 'seic' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'infinite_loop',
			array(
				'label'   => esc_html__( 'Infinite Loop', 'seic' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'   => esc_html__( 'Autoplay', 'seic' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->add_control(
			'autoplay_speed',
			array(
				'label'   => esc_html__( 'Autoplay Speed (ms)', 'seic' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 5000,
				'condition' => array(
					'autoplay' => 'yes',
				),
			)
		);

		$this->add_control(
			'pause_on_hover',
			array(
				'label'   => esc_html__( 'Pause on Hover', 'seic' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => array(
					'autoplay' => 'yes',
				),
			)
		);

		$this->add_control(
			'pause_on_interaction',
			array(
				'label'   => esc_html__( 'Pause on Interaction', 'seic' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
				'condition' => array(
					'autoplay' => 'yes',
				),
			)
		);

		$this->add_control(
			'animation_speed',
			array(
				'label'   => esc_html__( 'Animation Speed (ms)', 'seic' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 500,
				'min'     => 100,
				'max'     => 3000,
			)
		);

		$this->add_control(
			'direction',
			array(
				'label'   => esc_html__( 'Direction', 'seic' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'ltr',
				'options' => array(
					'ltr' => esc_html__( 'Left to Right', 'seic' ),
					'rtl' => esc_html__( 'Right to Left', 'seic' ),
				),
			)
		);

		$this->add_control(
			'lazy_load',
			array(
				'label'   => esc_html__( 'Lazy Load', 'seic' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_navigation',
			array(
				'label' => esc_html__( 'Navigation', 'seic' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'navigation' => array( 'both', 'arrows', 'dots' ),
				),
			)
		);

		$this->add_control(
			'heading_arrows',
			array(
				'label'     => esc_html__( 'Arrows', 'seic' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'navigation' => array( 'both', 'arrows' ),
				),
			)
		);

		$this->add_control(
			'arrows_box_size',
			array(
				'label' => esc_html__( 'Box Size', 'seic' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range' => array(
					'px' => array(
						'min' => 20,
						'max' => 100,
					),
				),
				'default' => array(
					'size' => 44,
					'unit' => 'px',
				),
				'selectors' => array(
					'{{WRAPPER}} .seic-prev, {{WRAPPER}} .seic-next' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;',
				),
				'condition' => array(
					'navigation' => array( 'both', 'arrows' ),
				),
			)
		);

		$this->add_control(
			'arrows_icon_size',
			array(
				'label' => esc_html__( 'Icon Size', 'seic' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range' => array(
					'px' => array(
						'min' => 10,
						'max' => 50,
					),
				),
				'default' => array(
					'size' => 16,
					'unit' => 'px',
				),
				'selectors' => array(
					'{{WRAPPER}} .seic-prev i, {{WRAPPER}} .seic-next i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .seic-prev svg, {{WRAPPER}} .seic-next svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'navigation' => array( 'both', 'arrows' ),
				),
			)
		);

		$this->add_control(
			'arrows_color',
			array(
				'label'     => esc_html__( 'Color', 'seic' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .seic-prev, {{WRAPPER}} .seic-next' => 'color: {{VALUE}};',
					'{{WRAPPER}} .seic-prev svg, {{WRAPPER}} .seic-next svg' => 'fill: {{VALUE}};',
				),
				'condition' => array(
					'navigation' => array( 'both', 'arrows' ),
				),
			)
		);

		$this->add_control(
			'arrows_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'seic' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(0,0,0,0.5)',
				'selectors' => array(
					'{{WRAPPER}} .seic-prev, {{WRAPPER}} .seic-next' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'navigation' => array( 'both', 'arrows' ),
				),
			)
		);

		$this->add_control(
			'arrows_hover_color',
			array(
				'label'     => esc_html__( 'Hover Color', 'seic' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .seic-prev:hover, {{WRAPPER}} .seic-next:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .seic-prev:hover svg, {{WRAPPER}} .seic-next:hover svg' => 'fill: {{VALUE}};',
				),
				'condition' => array(
					'navigation' => array( 'both', 'arrows' ),
				),
			)
		);

		$this->add_control(
			'arrows_hover_bg_color',
			array(
				'label'     => esc_html__( 'Hover Background Color', 'seic' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(0,0,0,0.8)',
				'selectors' => array(
					'{{WRAPPER}} .seic-prev:hover, {{WRAPPER}} .seic-next:hover' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'navigation' => array( 'both', 'arrows' ),
				),
			)
		);

		$this->add_control(
			'heading_pagination',
			array(
				'label'     => esc_html__( 'Pagination', 'seic' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'navigation' => array( 'both', 'dots' ),
				),
			)
		);

		$this->add_control(
			'pagination_color',
			array(
				'label'     => esc_html__( 'Color', 'seic' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'navigation' => array( 'both', 'dots' ),
				),
			)
		);

		$this->add_control(
			'pagination_active_color',
			array(
				'label'     => esc_html__( 'Active Color', 'seic' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'navigation' => array( 'both', 'dots' ),
				),
			)
		);

		$this->add_control(
			'pagination_visibility',
			array(
				'label'   => esc_html__( 'Pagination Visibility', 'seic' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'visible',
				'options' => array(
					'visible' => esc_html__( 'Visible', 'seic' ),
					'hidden'  => esc_html__( 'Hidden', 'seic' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .seic-pagination' => 'display: {{VALUE}} !important;',
					'{{WRAPPER}} .swiper-pagination' => 'display: {{VALUE}} !important;',
				),
				'selectors_dictionary' => array(
					'visible' => 'block',
					'hidden'  => 'none',
				),
				'condition' => array(
					'navigation' => array( 'both', 'dots' ),
				),
			)
		);

		$this->end_controls_section();

		// Image Styling Section
		$this->start_controls_section(
			'section_style_image',
			array(
				'label' => esc_html__( 'Image', 'seic' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'image_height',
			array(
				'label'      => esc_html__( 'Image Height', 'seic' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 100,
						'max' => 1000,
					),
					'vh' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'default'    => array(
					'size' => 400,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .seic-slide' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .seic-slide img' => 'height: 100%; width: 100%; object-fit: cover;',
				),
			)
		);

		$this->add_control(
			'image_radius',
			array(
				'label'      => esc_html__( 'Image Border Radius', 'seic' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
					'%' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default'    => array(
					'size' => 8,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .seic-slide img' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'image_box_shadow',
				'selector' => '{{WRAPPER}} .seic-slide img',
			)
		);

		$this->add_control(
			'image_opacity',
			array(
				'label' => esc_html__( 'Opacity', 'seic' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range' => array(
					'px' => array(
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					),
				),
				'default' => array(
					'size' => 1,
				),
				'selectors' => array(
					'{{WRAPPER}} .seic-slide img' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$images   = isset( $settings['images'] ) ? $settings['images'] : array();

		if ( empty( $images ) ) {
			echo '<div class="seic-empty">' . esc_html__( 'Please add images to the gallery.', 'seic' ) . '</div>';
			return;
		}
		
		// Ensure we have enough slides for loop mode - BUT don't over-duplicate
		// Only duplicate if we have fewer slides than needed for a smooth loop
		if ( ( $settings['infinite_loop'] ?? 'yes' ) === 'yes' ) {
			$slides_to_show = isset($settings['slides_to_show']) ? (int)$settings['slides_to_show'] : 3;
			
			// Only duplicate if we have LESS than 2x the visible slides
			// This prevents blank slides from appearing
			$min_required = $slides_to_show * 2;
			
			if ( count($images) > 0 && count($images) < $min_required ) {
				$original_images = $images;
				$current_count = count($images);
				
				// Calculate how many times to duplicate
				$times_to_duplicate = ceil( $min_required / $current_count );
				
				// Duplicate only as many times as needed
				for ( $i = 1; $i < $times_to_duplicate; $i++ ) {
					$images = array_merge( $images, $original_images );
				}
			}
			// If we already have enough slides, don't duplicate at all
		}

		$uid = 'seic-' . $this->get_id();

		// Responsive settings
		$slides_to_show = $settings['slides_to_show'] ?? 3;
		$slides_to_show_tablet = $settings['slides_to_show_tablet'] ?? 2;
		$slides_to_show_mobile = $settings['slides_to_show_mobile'] ?? 1;

		$slides_to_scroll = $settings['slides_to_scroll'] ?? 1;
		$slides_to_scroll_tablet = $settings['slides_to_scroll_tablet'] ?? 1;
		$slides_to_scroll_mobile = $settings['slides_to_scroll_mobile'] ?? 1;

		$space_between = isset( $settings['space_between']['size'] ) ? $settings['space_between']['size'] : 20;
		$space_between_tablet = isset( $settings['space_between_tablet']['size'] ) ? $settings['space_between_tablet']['size'] : 10;
		$space_between_mobile = isset( $settings['space_between_mobile']['size'] ) ? $settings['space_between_mobile']['size'] : 10;

		$options = array(
			'slidesPerView'      => (int) $slides_to_show,
			'slidesPerGroup'     => (int) $slides_to_scroll,
			'spaceBetween'       => (int) $space_between,
			'loop'               => ( ( $settings['infinite_loop'] ?? '' ) === 'yes' ),
			'speed'              => (int) ( $settings['animation_speed'] ?? 500 ),
			'lazy'               => ( ( $settings['lazy_load'] ?? '' ) === 'yes' ),
			'breakpoints'        => array(
				320  => array( 
					'slidesPerView'  => (int) $slides_to_show_mobile,
					'slidesPerGroup' => (int) $slides_to_scroll_mobile,
					'spaceBetween'   => (int) $space_between_mobile,
				),
				768  => array( 
					'slidesPerView'  => (int) $slides_to_show_tablet,
					'slidesPerGroup' => (int) $slides_to_scroll_tablet,
					'spaceBetween'   => (int) $space_between_tablet,
				),
				1025 => array( 
					'slidesPerView'  => (int) $slides_to_show,
					'slidesPerGroup' => (int) $slides_to_scroll,
					'spaceBetween'   => (int) $space_between,
				),
			),
			'navigation'         => in_array( $settings['navigation'], array( 'both', 'arrows' ) ),
			'pagination'         => in_array( $settings['navigation'], array( 'both', 'dots' ) ),
			'autoplay'           => ( ( $settings['autoplay'] ?? '' ) === 'yes' ),
			'autoplayDelay'      => (int) ( $settings['autoplay_speed'] ?? 5000 ),
			'pauseOnHover'       => ( ( $settings['pause_on_hover'] ?? '' ) === 'yes' ),
			'pauseOnInteraction' => ( ( $settings['pause_on_interaction'] ?? '' ) !== 'yes' ), // Inverted: false means it will pause
		);

		$dir_attr = ( $settings['direction'] === 'rtl' ) ? 'dir="rtl"' : 'dir="ltr"';
		$link_behavior = $settings['link_behavior'] ?? 'none';
		$lazy_load     = ( $settings['lazy_load'] ?? '' ) === 'yes';
		$image_stretch = ( $settings['image_stretch'] ?? 'no' ) === 'yes';
		
		$wrapper_class = 'seic-carousel';
		if ( $image_stretch ) {
			$wrapper_class .= ' seic-stretch';
		}

		?>
		<div class="<?php echo esc_attr( $wrapper_class ); ?>" id="<?php echo esc_attr( $uid ); ?>" <?php echo $dir_attr; ?> data-seic-options="<?php echo esc_attr( wp_json_encode( $options ) ); ?>">
			<div class="swiper-container seic-swiper">
				<div class="swiper-wrapper">
					<?php foreach ( $images as $img ) :
						$attachment_id = ! empty( $img['id'] ) ? (int) $img['id'] : 0;
						$img_url       = ! empty( $img['url'] ) ? $img['url'] : '';

						$full_url = $attachment_id ? wp_get_attachment_url( $attachment_id ) : $img_url;

						// Build image HTML
						if ( $attachment_id ) {
							$image_html = Group_Control_Image_Size::get_attachment_image_html( 
								$settings, 
								'image', 
								$attachment_id 
							);
						} elseif ( $img_url ) {
							$image_html = '<img src="' . esc_url( $img_url ) . '" alt="" />';
						} else {
							continue;
						}

						// Fallback if Elementor returned empty markup
						if ( empty( $image_html ) && $full_url ) {
							$image_html = '<img src="' . esc_url( $full_url ) . '" alt="" />';
						}

						// Handle lazy loading
						if ( $lazy_load ) {
							if ( strpos( $image_html, 'loading=' ) === false ) {
								$image_html = str_replace( '<img ', '<img loading="lazy" ', $image_html );
							} else {
								$image_html = str_replace( 'loading="eager"', 'loading="lazy"', $image_html );
							}
						} else {
							if ( strpos( $image_html, 'loading=' ) !== false ) {
								$image_html = str_replace( 'loading="lazy"', 'loading="eager"', $image_html );
							} else {
								$image_html = str_replace( '<img ', '<img loading="eager" ', $image_html );
							}
						}
						?>
						<div class="swiper-slide seic-slide">
							<?php
							if ( $link_behavior === 'media' && $full_url ) {
								echo '<a class="seic-link" href="' . esc_url( $full_url ) . '" data-elementor-open-lightbox="yes" data-elementor-lightbox-slideshow="' . esc_attr( $uid ) . '">' . $image_html . '</a>';
							} else {
								echo $image_html;
							}
							
							// Caption handling
							if ( $settings['caption'] !== 'none' && $attachment_id ) {
								$caption_text = '';
								switch( $settings['caption'] ) {
									case 'title':
										$caption_text = get_the_title( $attachment_id );
										break;
									case 'caption':
										$caption_text = wp_get_attachment_caption( $attachment_id );
										break;
									case 'description':
										$post = get_post( $attachment_id );
										$caption_text = $post->post_content ?? '';
										break;
								}
								if ( ! empty( $caption_text ) ) {
									echo '<div class="seic-caption">' . esc_html( $caption_text ) . '</div>';
								}
							}
							?>
						</div>
					<?php endforeach; ?>
				</div>

				<?php if ( in_array( $settings['navigation'], array( 'both', 'arrows' ) ) ) : ?>
					<div class="swiper-button-prev seic-prev" aria-label="<?php echo esc_attr__( 'Previous', 'seic' ); ?>">
						<?php \Elementor\Icons_Manager::render_icon( $settings['previous_arrow_icon'], [ 'aria-hidden' => 'true' ] ); ?>
					</div>
					<div class="swiper-button-next seic-next" aria-label="<?php echo esc_attr__( 'Next', 'seic' ); ?>">
						<?php \Elementor\Icons_Manager::render_icon( $settings['next_arrow_icon'], [ 'aria-hidden' => 'true' ] ); ?>
					</div>
				<?php endif; ?>

				<?php if ( in_array( $settings['navigation'], array( 'both', 'dots' ) ) ) : ?>
					<div class="swiper-pagination seic-pagination"></div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
	protected function content_template() {
		?>
		<#
		var uid = 'seic-' + view.getID();
		var images = settings.images || [];
		
		if ( images.length === 0 ) {
			#>
			<div class="seic-empty"><?php esc_html_e( 'Please add images to the gallery.', 'seic' ); ?></div>
			<#
			return;
		}

		// Handle loop mode duplication in editor - same as frontend
		var slides_to_show = parseInt(settings.slides_to_show) || 3;
		if ( settings.infinite_loop === 'yes' && images.length < (slides_to_show * 2) ) {
			var original_images = images.slice();
			var min_required = slides_to_show * 2;
			while ( images.length < min_required ) {
				images = images.concat(original_images);
			}
		}

		var wrapperClass = 'seic-carousel';
		if ( settings.image_stretch === 'yes' ) {
			wrapperClass += ' seic-stretch';
		}

		var dirAttr = settings.direction === 'rtl' ? 'rtl' : 'ltr';
		
		// Build options for JS - SAME as frontend
		var options = {
			slidesPerView: parseInt(settings.slides_to_show) || 3,
			slidesPerGroup: parseInt(settings.slides_to_scroll) || 1,
			spaceBetween: settings.space_between && settings.space_between.size ? parseInt(settings.space_between.size) : 20,
			loop: settings.infinite_loop === 'yes',
			speed: parseInt(settings.animation_speed) || 500,
			lazy: settings.lazy_load === 'yes',
			breakpoints: {
				320: {
					slidesPerView: parseInt(settings.slides_to_show_mobile) || 1,
					slidesPerGroup: parseInt(settings.slides_to_scroll_mobile) || 1,
					spaceBetween: settings.space_between_mobile && settings.space_between_mobile.size ? parseInt(settings.space_between_mobile.size) : 10
				},
				768: {
					slidesPerView: parseInt(settings.slides_to_show_tablet) || 2,
					slidesPerGroup: parseInt(settings.slides_to_scroll_tablet) || 1,
					spaceBetween: settings.space_between_tablet && settings.space_between_tablet.size ? parseInt(settings.space_between_tablet.size) : 10
				},
				1025: {
					slidesPerView: parseInt(settings.slides_to_show) || 3,
					slidesPerGroup: parseInt(settings.slides_to_scroll) || 1,
					spaceBetween: settings.space_between && settings.space_between.size ? parseInt(settings.space_between.size) : 20
				}
			},
			navigation: settings.navigation === 'both' || settings.navigation === 'arrows',
			pagination: settings.navigation === 'both' || settings.navigation === 'dots',
			autoplay: settings.autoplay === 'yes',
			autoplayDelay: parseInt(settings.autoplay_speed) || 5000,
			pauseOnHover: settings.pause_on_hover === 'yes',
			pauseOnInteraction: settings.pause_on_interaction !== 'yes'
		};
		#>
		<div class="{{ wrapperClass }}" id="{{ uid }}" dir="{{ dirAttr }}" data-seic-options="{{ JSON.stringify(options) }}">
			<div class="swiper-container seic-swiper">
				<div class="swiper-wrapper">
					<# _.each( images, function( image ) {
						var imageUrl = image.url;
						var imageId = image.id;
						
						if ( ! imageUrl && ! imageId ) {
							return;
						}

						var lazyAttr = settings.lazy_load === 'yes' ? ' loading="lazy"' : '';
					#>
					<div class="swiper-slide seic-slide">
						<# if ( settings.link_behavior === 'media' && imageUrl ) { #>
							<a class="seic-link" href="{{ imageUrl }}" data-elementor-open-lightbox="yes" data-elementor-lightbox-slideshow="{{ uid }}">
								<img src="{{ imageUrl }}"{{ lazyAttr }} alt="" />
							</a>
						<# } else { #>
							<img src="{{ imageUrl }}"{{ lazyAttr }} alt="" />
						<# } #>
					</div>
					<# }); #>
				</div>

				<# if ( settings.navigation === 'both' || settings.navigation === 'arrows' ) { #>
					<div class="swiper-button-prev seic-prev" aria-label="<?php esc_attr_e( 'Previous', 'seic' ); ?>">
						<# if ( settings.previous_arrow_icon && settings.previous_arrow_icon.value ) {
							var iconHTML = elementor.helpers.renderIcon( view, settings.previous_arrow_icon, {}, 'i' , 'object' );
							print( iconHTML.value );
						} #>
					</div>
					<div class="swiper-button-next seic-next" aria-label="<?php esc_attr_e( 'Next', 'seic' ); ?>">
						<# if ( settings.next_arrow_icon && settings.next_arrow_icon.value ) {
							var iconHTML = elementor.helpers.renderIcon( view, settings.next_arrow_icon, {}, 'i' , 'object' );
							print( iconHTML.value );
						} #>
					</div>
				<# } #>

				<# if ( settings.navigation === 'both' || settings.navigation === 'dots' ) { #>
					<div class="swiper-pagination seic-pagination"></div>
				<# } #>
			</div>
		</div>
		<?php
	}
}