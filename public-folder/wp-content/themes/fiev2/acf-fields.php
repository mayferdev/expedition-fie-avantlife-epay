<?php

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_5a96537ddf821',
	'title' => 'Detalles del evento.',
	'fields' => array(
		array(
			'key' => 'field_5a965c5e4b798',
			'label' => 'Info.',
			'name' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'placement' => 'top',
			'endpoint' => 0,
		),
		array(
			'key' => 'field_5ba1af445ed38',
			'label' => 'Tags',
			'name' => 'tags',
			'type' => 'taxonomy',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'taxonomy' => 'post_tag',
			'field_type' => 'checkbox',
			'allow_null' => 0,
			'add_term' => 0,
			'save_terms' => 1,
			'load_terms' => 1,
			'return_format' => 'id',
			'multiple' => 0,
		),
		array(
			'key' => 'field_5a9658ca3c9fe',
			'label' => 'Gallery',
			'name' => 'gallery',
			'type' => 'gallery',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'min' => 1,
			'max' => 10,
			'insert' => 'append',
			'library' => 'all',
			'min_width' => 360,
			'min_height' => 216,
			'min_size' => '',
			'max_width' => '',
			'max_height' => '',
			'max_size' => 25,
			'mime_types' => '',
		),
		array(
			'key' => 'field_5a9655e1c01e2',
			'label' => 'Descripcion del evento',
			'name' => 'desc',
			'type' => 'wysiwyg',
			'instructions' => 'Paste/type the description of the event',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'tabs' => 'all',
			'toolbar' => 'full',
			'media_upload' => 1,
			'delay' => 0,
		),
		array(
			'key' => 'field_5a96555fc01e0',
			'label' => 'Que llevar?',
			'name' => 'what_to_bring',
			'type' => 'repeater',
			'instructions' => 'Enter the items the expeditioner have to bring.',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'collapsed' => '',
			'min' => 1,
			'max' => 0,
			'layout' => 'table',
			'button_label' => 'Add item',
			'sub_fields' => array(
				array(
					'key' => 'field_5ac13f0af5fa8',
					'label' => 'ID',
					'name' => 'id',
					'type' => 'unique_id',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
				),
				array(
					'key' => 'field_5a96559ac01e1',
					'label' => 'Item name',
					'name' => 'name',
					'type' => 'text',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
			),
		),
		array(
			'key' => 'field_5a965c484b797',
			'label' => 'Fecha',
			'name' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'placement' => 'top',
			'endpoint' => 0,
		),
		array(
			'key' => 'field_5a965bcb4b795',
			'label' => 'Fecha del evento',
			'name' => 'departure_date',
			'type' => 'date_time_picker',
			'instructions' => 'The departure date time.',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'display_format' => 'd/m/Y g:i a',
			'return_format' => 'Y-m-d H:i:s',
			'first_day' => 1,
		),
		array(
			'key' => 'field_5a965e1ff88a8',
			'label' => 'Lugar del evento',
			'name' => 'departure_place',
			'type' => 'google_map',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'center_lat' => '',
			'center_lng' => '',
			'zoom' => '',
			'height' => '',
		),
		array(
			'key' => 'field_5a9660b88eba2',
			'label' => 'Nombre del lugar',
			'name' => 'departure_place_name',
			'type' => 'text',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_5a96613549d46',
			'label' => 'Actividades',
			'name' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'placement' => 'top',
			'endpoint' => 0,
		),
		array(
			'key' => 'field_5a965dbdf88a7',
			'label' => 'Actividades',
			'name' => 'itinerary',
			'type' => 'repeater',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'collapsed' => '',
			'min' => 0,
			'max' => 0,
			'layout' => 'table',
			'button_label' => 'Add item',
			'sub_fields' => array(
				array(
					'key' => 'field_5a966162815ec',
					'label' => 'Date',
					'name' => 'date',
					'type' => 'date_time_picker',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'display_format' => 'd/m/Y g:i a',
					'return_format' => 'Y-m-d H:i:s',
					'first_day' => 1,
				),
				array(
					'key' => 'field_5a96617b815ed',
					'label' => 'Description',
					'name' => 'desc',
					'type' => 'text',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
			),
		),
// 		array(
// 			'key' => 'field_5a96612849d45',
// 			'label' => 'Return',
// 			'name' => '',
// 			'type' => 'tab',
// 			'instructions' => '',
// 			'required' => 0,
// 			'conditional_logic' => 0,
// 			'wrapper' => array(
// 				'width' => '',
// 				'class' => '',
// 				'id' => '',
// 			),
// 			'placement' => 'top',
// 			'endpoint' => 0,
// 		),
// 		array(
// 			'key' => 'field_5a965c2c4b796',
// 			'label' => 'Return date',
// 			'name' => 'return_date',
// 			'type' => 'date_time_picker',
// 			'instructions' => 'The return date time.',
// 			'required' => 1,
// 			'conditional_logic' => 0,
// 			'wrapper' => array(
// 				'width' => '',
// 				'class' => '',
// 				'id' => '',
// 			),
// 			'display_format' => 'd/m/Y g:i a',
// 			'return_format' => 'Y-m-d H:i:s',
// 			'first_day' => 1,
// 		),
// 		array(
// 			'key' => 'field_5a9660d28eba3',
// 			'label' => 'Return place',
// 			'name' => 'return_place',
// 			'type' => 'google_map',
// 			'instructions' => '',
// 			'required' => 1,
// 			'conditional_logic' => 0,
// 			'wrapper' => array(
// 				'width' => '',
// 				'class' => '',
// 				'id' => '',
// 			),
// 			'center_lat' => '',
// 			'center_lng' => '',
// 			'zoom' => '',
// 			'height' => '',
// 		),
// 		array(
// 			'key' => 'field_5a9660e78eba4',
// 			'label' => 'Return place name',
// 			'name' => 'return_place_name',
// 			'type' => 'text',
// 			'instructions' => '',
// 			'required' => 1,
// 			'conditional_logic' => 0,
// 			'wrapper' => array(
// 				'width' => '',
// 				'class' => '',
// 				'id' => '',
// 			),
// 			'default_value' => '',
// 			'placeholder' => '',
// 			'prepend' => '',
// 			'append' => '',
// 			'maxlength' => '',
// 		),
		array(
			'key' => 'field_5a965c9b4b799',
			'label' => 'Detalles',
			'name' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'placement' => 'top',
			'endpoint' => 0,
		),
		array(
			'key' => 'field_5bbfceefc6454',
			'label' => 'Codigo del evento',
			'name' => '',
			'type' => 'message',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'new_lines' => 'wpautop',
			'esc_html' => 0,
		),
		array(
			'key' => 'field_5a98d879ed1d7',
			'label' => 'Color del evento',
			'name' => 'main_color',
			'type' => 'color_picker',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
		),
		array(
			'key' => 'field_5a9654d1c01df',
			'label' => 'Tipo de evento',
			'name' => 'type',
			'type' => 'select',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'private' => 'Private',
				'public' => 'Public',
			),
			'default_value' => array(
				0 => 'public',
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'return_format' => 'value',
			'ajax' => 0,
			'placeholder' => '',
		),
		array(
			'key' => 'field_5a96595d3c9ff',
			'label' => 'Espacios disponibles',
			'name' => 'max_capacity',
			'type' => 'number',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_5a9654d1c01df',
						'operator' => '==',
						'value' => 'public',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => 10,
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'min' => 1,
			'max' => 10000,
			'step' => 1,
		),
// 		array(
// 			'key' => 'field_5bbfc46391f0f',
// 			'label' => 'Hide seats information',
// 			'name' => 'hide_seats_information',
// 			'type' => 'true_false',
// 			'instructions' => 'Do you want to hide the number of seats available?',
// 			'required' => 0,
// 			'conditional_logic' => 0,
// 			'wrapper' => array(
// 				'width' => '',
// 				'class' => '',
// 				'id' => '',
// 			),
// 			'message' => '',
// 			'default_value' => 0,
// 			'ui' => 0,
// 			'ui_on_text' => '',
// 			'ui_off_text' => '',
// 		),
		array(
			'key' => 'field_5a9659af3ca00',
			'label' => 'Expeditioners',
			'name' => 'expeditioners',
			'type' => 'user',
			'instructions' => 'Please consider that this value can\'t be modified, so include all the expeditioners you want to invite',
			'required' => 1,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_5a9654d1c01df',
						'operator' => '==',
						'value' => 'private',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'role' => array(
				0 => 'expeditioner',
			),
			'allow_null' => 0,
			'multiple' => 1,
			'return_format' => 'id',
		),
		array(
			'key' => 'field_5fvfcfc953dxc',
			'label' => 'Instrucciones de pago',
			'name' => 'payment_instructions',
			'type' => 'textarea',
			'instructions' => 'Introduce el texto a mostrar como instrucciones de pago.',
			'required' => 1,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_5a9654d1c01df',
						'operator' => '==',
						'value' => 'public',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'maxlength' => '',
			'rows' => '',
			'new_lines' => '',
		),
		array(
			'key' => 'field_5aafcb595e56c',
			'label' => 'Informacion de pago',
			'name' => 'monetary_deposit_copy',
			'type' => 'textarea',
			'instructions' => 'Paste/type the text to show to users with instructions to make deposit or transfer to confirm the booking.',
			'required' => 1,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_5a9654d1c01df',
						'operator' => '==',
						'value' => 'public',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => 'Tienes --- horas para realizar	un depósito o transferencia bancaria en las cuentas monetarias a nombre de ... :

G&T Continental : ...
Banco Industrial : ...

Tu monto de depósito debe coincidir con el número de servicios seleccionados',
			'placeholder' => '',
			'maxlength' => '',
			'rows' => '',
			'new_lines' => '',
		),
		array(
			'key' => 'field_5b1046d14ca20',
			'label' => 'Politicas de cancelacion',
			'name' => 'cancellation_policy',
			'type' => 'textarea',
			'instructions' => 'Paste/type the text to show to users the cancellations policies.',
			'required' => 1,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_5a9654d1c01df',
						'operator' => '==',
						'value' => 'public',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'maxlength' => '',
			'rows' => '',
			'new_lines' => '',
		),
		array(
			'key' => 'field_5e79dbb90bb84',
			'label' => 'Registrar pago?',
			'name' => 'hide_payment',
			'type' => 'true_false',
			'instructions' => 'If you check this we\'ll hide the payment component.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => 'Hide',
			'ui_off_text' => 'Not hide',
		),
		array(
			'key' => 'field_5a96631a7985a',
			'label' => 'Categorias',
			'name' => 'category',
			'type' => 'repeater',
			'instructions' => 'Add the pricing shifts (useful to separates different classes of contributions, like general, premium, vip, etc)',
			'required' => 1,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_5a9654d1c01df',
						'operator' => '==',
						'value' => 'public',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'collapsed' => '',
			'min' => 0,
			'max' => 0,
			'layout' => 'table',
			'button_label' => 'Agregar categoria',
			'sub_fields' => array(
				array(
					'key' => 'field_5a96640cda9d6',
					'label' => 'ID',
					'name' => 'id',
					'type' => 'unique_id',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '10',
						'class' => '',
						'id' => '',
					),
				),
				array(
					'key' => 'field_5a96637d7985b',
					'label' => 'Nombre',
					'name' => 'category',
					'type' => 'text',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '15',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_5aada8e05270a',
					'label' => 'Descripcion',
					'name' => 'desc',
					'type' => 'textarea',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '25',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => 'Incluye/No incluye',
					'maxlength' => '',
					'rows' => 8,
					'new_lines' => '',
				),
				array(
					'key' => 'field_5a9664513a470',
					'label' => 'Detalles',
					'name' => 'prices',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'collapsed' => '',
					'min' => 0,
					'max' => 0,
					'layout' => 'table',
					'button_label' => 'Add Amount',
					'sub_fields' => array(
						array(
							'key' => 'field_5aad7fc8d5306',
							'label' => 'ID',
							'name' => 'id',
							'type' => 'unique_id',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '15',
								'class' => '',
								'id' => '',
							),
						),
						array(
							'key' => 'field_5a96db7a3a472',
							'label' => 'Tipo',
							'name' => 'title',
							'type' => 'text',
							'instructions' => '',
							'required' => 1,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
							'placeholder' => 'Adult/Child',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
						),
						array(
							'key' => 'field_5e79e17359c63',
							'label' => 'Disponibles',
							'name' => 'max_capacity',
							'type' => 'number',
							'instructions' => '',
							'required' => 1,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => 10,
							'placeholder' => '',
							'prepend' => '',
							'append' => '',
							'min' => '',
							'max' => '',
							'step' => 1,
						),
						array(
							'key' => 'field_5a96647c3a471',
							'label' => 'Precio',
							'name' => 'price',
							'type' => 'number',
							'instructions' => '',
							'required' => 1,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => 0,
							'placeholder' => '',
							'prepend' => '',
							'append' => '',
							'min' => 0,
							'max' => 1000000,
							'step' => '',
						),
					),
				),
			),
		),
		array(
			'key' => 'field_5bbfe51255e70',
			'label' => 'Currency',
			'name' => 'currency_symbol',
			'type' => 'select',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'Q' => 'Quetzal',
				'$' => 'Dollar',
				'MXN' => 'Mexican Peso',
			),
			'default_value' => array(
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'ajax' => 0,
			'return_format' => 'value',
			'placeholder' => '',
		),
		array(
			'key' => 'field_5aab01da9cde0',
			'label' => 'Encargado',
			'name' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'placement' => 'top',
			'endpoint' => 0,
		),
		array(
			'key' => 'field_5aab01e29cde1',
			'label' => 'Owner',
			'name' => 'owner',
			'type' => 'user',
			'instructions' => 'Please consider that this value can\'t be modified',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'role' => array(
				0 => 'business',
				1 => 'expeditioner',
				2 => 'administrator',
			),
			'allow_null' => 0,
			'multiple' => 0,
			'return_format' => 'id',
			'disabled' => 0,
		),
		array(
			'key' => 'field_5b992e3ffb75f',
			'label' => 'Participantes',
			'name' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'placement' => 'top',
			'endpoint' => 0,
		),
		array(
			'key' => 'field_5b992e55fb760',
			'label' => 'Volunteers',
			'name' => '',
			'type' => 'message',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'new_lines' => 'wpautop',
			'esc_html' => 0,
		),
		array(
			'key' => 'field_5c6c8dcc23277',
			'label' => 'Info. Participantes',
			'name' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'placement' => 'top',
			'endpoint' => 0,
		),
		array(
			'key' => 'field_5c6c8dde23278',
			'label' => 'Solicitar Nombre',
			'name' => 'request_first_name',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 1,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_5c6c8e0323279',
			'label' => 'Solicitar Apellido',
			'name' => 'request_last_name',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 1,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_5c6c8e032312s',
			'label' => 'Solicitar Sexo',
			'name' => 'request_sex',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 1,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_5dc88e543312v',
			'label' => 'Solicitar Edad',
			'name' => 'request_sex',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 1,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_5c632d032312s',
			'label' => 'Solicitar Nacionalidad',
			'name' => 'request_nationality',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 1,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		
// 		array(
// 			'key' => 'field_5c6c8e0c2327a',
// 			'label' => 'Solicitar Edad',
// 			'name' => 'request_age',
// 			'type' => 'true_false',
// 			'instructions' => '',
// 			'required' => 0,
// 			'conditional_logic' => 0,
// 			'wrapper' => array(
// 				'width' => '',
// 				'class' => '',
// 				'id' => '',
// 			),
// 			'message' => '',
// 			'default_value' => 1,
// 			'ui' => 1,
// 			'ui_on_text' => '',
// 			'ui_off_text' => '',
// 		),
		array(
			'key' => 'field_5c6c8e212327c',
			'label' => 'Solicitar DPI/Passport',
			'name' => 'request_dpi_passport',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 1,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_5c6c8e332327d',
			'label' => 'Solicitar Telefono',
			'name' => 'request_phone',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 1,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_5ca2a3790e89d',
			'label' => 'Solicitar Correo electronico',
			'name' => 'request_email',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 1,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_5f031593783db',
			'label' => 'Preguntas personalizadas',
			'name' => 'solicitar_informacion_extra',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => 'Si',
			'ui_off_text' => 'No',
		),
		array(
			'key' => 'field_5f0315ba783dc',
			'label' => 'Informacion a solicitar',
			'name' => 'informacion_a_solicitar',
			'type' => 'repeater',
			'instructions' => 'Ingresa el "placeholder" del campo a solicitar',
			'required' => 1,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_5f031593783db',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'collapsed' => '',
			'min' => 1,
			'max' => 10,
			'layout' => 'table',
			'button_label' => '',
			'sub_fields' => array(
				array(
					'key' => 'field_5f03160c783dd',
					'label' => 'Titulo',
					'name' => 'titulo',
					'type' => 'text',
					'instructions' => 'Titulo del campo a solicitar',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_5f031636783de',
					'label' => 'Placeholder',
					'name' => 'placeholder',
					'type' => 'text',
					'instructions' => 'Placeholder del campo que deseas solicitar',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
			),
		),
        array(        
			'key' => 'field_5f21ecc467af4',
			'label' => 'Codigos de descuento',
			'name' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'placement' => 'top',
			'endpoint' => 0,
		),
		array(
			'key' => 'field_5f21ebb5882d2',
			'label' => 'Codigos de descuento',
			'name' => 'discount_codes',
			'type' => 'repeater',
			'instructions' => 'Ingresa el codigo de descuento y el porcentaje',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'collapsed' => 'field_5f21ec10882d3',
			'min' => 0,
			'max' => 0,
			'layout' => 'table',
			'button_label' => 'Agregar descuento',
			'sub_fields' => array(
				array(
					'key' => 'field_5f21ec10882d3',
					'label' => 'Codigo',
					'name' => 'codigo',
					'type' => 'text',
					'instructions' => 'Codigo de descuento',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => 'code',
					'prepend' => '',
					'append' => '',
					'maxlength' => 10,
				),
				array(
					'key' => 'field_5f21ec3d882d4',
					'label' => 'Porcentaje de descuento',
					'name' => 'percentage',
					'type' => 'number',
					'instructions' => 'Ingresa el porcentaje de descuento',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => 0,
					'placeholder' => '0-100%',
					'prepend' => '',
					'append' => '%',
					'min' => 0,
					'max' => 100,
					'step' => '',
				),
			),
		),
		array(
			'key' => 'field_5f04b569fa996',
			'label' => 'Email Confirmation Template',
			'name' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'placement' => 'top',
			'endpoint' => 0,
		),
		array(
			'key' => 'field_5f04b58dfa998',
			'label' => 'Subject',
			'name' => 'confirmation_subject_en',
			'type' => 'text',
			'instructions' => 'You can use this tags {user_email}, {user_full_name}, {user_firstname} and {user_last_name}.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_5f04b574fa997',
			'label' => 'Content',
			'name' => 'confirmation_content_en',
			'type' => 'wysiwyg',
			'instructions' => 'You can using the flags {user_email}, {user_full_name}, {user_firstname}, {user_last_name}, {event_name}, {businesss_name}, {booking_id}, {booking_qr_code_image}, {departure_date}, {tag}, {category_title}, {departure_place}, {description}',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'tabs' => 'all',
			'toolbar' => 'full',
			'media_upload' => 1,
			'delay' => 0,
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'tour',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => array(
		0 => 'the_content',
		1 => 'categories',
		2 => 'tags',
	),
	'active' => true,
	'description' => '',
));

endif;