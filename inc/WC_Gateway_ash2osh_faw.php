<?php

class WC_Gateway_ash2osh_faw extends WC_Payment_Gateway {

    public function __construct() {
        global $woocommerce;
        $this->id = ASH2OSH_FAW_TEXT_DOM;
      //  $this->method_title =__( '@Fawry',ASH2OSH_FAW_TEXT_DOM);
        $this->title=__('@Fawry',ASH2OSH_FAW_TEXT_DOM);
        $this->method_description = __( '@Fawry Payment Method',ASH2OSH_FAW_TEXT_DOM);

        // $this->load_plugin_textdomain();
        $this->icon = ASH2OSH_FAW_URL . '/images/logo_small.png';
        $this->has_fields = false;
        if (is_admin()) {
            $this->has_fields = true;
            $this->init_form_fields();
        }


        $this->init_form_fields();
        $this->init_settings();
        
        //you need to add a save hook for your settings:
        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );

    }


        /**
     * Initialise Gateway Settings Form Fields
     *
     * @access public
     * @return void
     */
    function init_form_fields()
    {
        $this->form_fields = array(
            'enabled'             => array(
                'title'   => __('Enable/Disable', ASH2OSH_FAW_TEXT_DOM),
                'type'    => 'checkbox',
                'label'   => __('Enable the @Fawry gateway', ASH2OSH_FAW_TEXT_DOM),
                'default' => 'yes'
            ),
            'description'         => array(
                'title'       => __('Description', ASH2OSH_FAW_TEXT_DOM),
                'type'        => 'text',
                'description' => __('This is the description the user sees during checkout.', ASH2OSH_FAW_TEXT_DOM),
                'default'     => __('Pay for your Order with any Credit or Debit Card or through Fawry Machines', ASH2OSH_FAW_TEXT_DOM)
            ),
            
            'merchant_identifier' => array(
                'title'       => __('Merchant Identifier', ASH2OSH_FAW_TEXT_DOM),
                'type'        => 'text',
                'description' => __('Your Merchant Identifier', ASH2OSH_FAW_TEXT_DOM),
                'default'     => '',
                'desc_tip'    => true,
                'placeholder' => ''
            ),
          
            'is_staging'  => array(
                'title'   => __('Is Staging Environment', ASH2OSH_FAW_TEXT_DOM),
                'type'    => 'checkbox',
                'label'   => __('Enable staging (Testing) Environment'),
                'default' => 'no'
            ),
        );
    }

    
    
        }
