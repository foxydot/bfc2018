<?php
if(!class_exists('MSDLab_tickets')){
    class MSDLab_tickets{
        //properties
        private $clan_name = array(
            'Amber Owls',
            'American Eagle',
            'Black Bull',
            'Black Leopard',
            'Black Thorn Raven',
            'Celestial Turtle',
            'Ebony Phoenix',
            'Emerald Bat',
            'Golden Condor',
            'Golden Mare',
            'Gryphon',
            'Omega Wolf',
            'Raven of the Black Rose',
            'Red Fox',
            'Running Bulls',
            'Silver Bristled Boar',
            'Silver Deer',
            'Silver Jaguar',
            'Snow Lion',
            'Sphinx',
            'White Stag',
            'White Tiger',
            'Woad Mare'
        );
        private $accomodations = array(
            'Tent(s)',
            'RV/Popup',
            'Offsite'
        );
        private $tent_size = array(
            'Not applicable',
            '2 Man',
            '4 Man',
            '6 Man',
            '8 Man',
            '10 Man',
            '12 Man',
            'My tent is a mansion I can fit armies in.'
        );

        private $degree_in_bfc = array(
            'Not applicable/Guest',
            'Dedicant',
            'First Degree',
            'Second Degree',
            'Third Degree',
            'Clan Head',
        );
        //construct
        function __construct() {
            add_action('yith_wcevti_custom_option',array(&$this,'add_select'),10,2);
            add_action('yith_wcevti_custom_field',array(&$this,'custom_field_frontend'),10,4);

            //woocommerce
            remove_action('woocommerce_single_product_summary','woocommerce_template_single_meta',40);
            remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
            remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
            remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

            add_filter('woocommerce_sale_flash',array(&$this,'edit_sale_text'),10,3);
            add_filter('loop_shop_columns', array(&$this,'loop_columns'),15);
            add_action('woocommerce_before_add_to_cart_button', array(&$this,'medical_form_button'),99);
            add_action('genesis_header', array(&$this,'medical_form_header'),99);
            add_filter('wc_add_to_cart_message_html', array(&$this,'id10t_proof_add_to_cart_message' ), 10, 2);

            //gravity forms
        }
        //methods
        //core
        function add_select($field,$index){
            $ret = '<option value="select" '.selected( isset( $field['_type'] ) && $field['_type'] == 'select' ) .' >'. __( 'Select', 'yith-event-tickets-for-woocommerce' ) .'</option>';
            print $ret;
        }

        function custom_field_frontend($field, $index, $row, $label){
            // any notice text
            switch($label){
                case 'accomodations':
                    print '<p><strong>PLEASE NOTE: </strong>If you intend to camp in an RV or popup, you are responsible for securing a campign space outside of the group loop. Please contact the <a href="https://pennsylvaniastateparks.reserveamerica.com/camping/gifford-pinchot-state-park/r/campgroundDetails.do?contractCode=PA&parkId=880306" target="_blank">Pennsylvania DCNR</a> to make your reservation and note the site number you reserve below.</p>';
                    break;
                case 'tent-size':
                    print '<p>If you are bringing multiple tents (for children, etc.) please add up and approximate. (ie. Two 4-man tents: 8 man)</p>';
                    break;
            }
            ?>
<p class="form-field _fields_customer_<?php echo $label; ?>_field ">

                        <?php if (!$placeholder){ ?>
    <label
        for="_fields_customer_<?php echo $row ?>_<?php echo $label; ?>"><?php echo $field['_label']; ?><?php if (isset($field['_required'])) {
            if ('on' == $field['_required']) {
                echo '*';
            }
        } ?></label>
<?php } ?>
<input type="hidden" style=""
       name="_fields_customer[<?php echo $row ?>][<?php echo $index ?>][_key]"
       value="<?php echo $label; ?>">
<input type="hidden" style=""
       name="_fields_customer[<?php echo $row ?>][<?php echo $index ?>][_label]"
       value="<?php echo $field['_label']; ?>">
<select class="_field_item" style=""
       name="_fields_customer[<?php echo $row ?>][<?php echo $index ?>][_value]"
       id="_fields_customer_<?php echo $row ?>_<?php echo $label; ?>"
       placeholder="<?php if ($placeholder){echo $field['_label'];} ?>" <?php if (isset($field['_required'])) {
    if ('on' == $field['_required']) {
        echo 'required';
    }
} ?>>
    <?php
    print $this->build_options($label,$field);
    ?>
</select>
</p>
            <?
        }

        public function build_options($label,$field){
            $ret = array();
            $options = $this->{str_replace('-','_',$label)};
            $value = $field['value'];

            foreach ($options AS $v){
                $ret[] = '<option value="'.$v.'"'.selected($value,$v,false).'>'.$v.'</option>';
            }
            return implode("\n",$ret);
        }

//woocommerce stuff

        function edit_sale_text($str,$post,$product){
            $ret = preg_replace('#Sale!#i','Early Bird!',$str);
            return $ret;
        }

        // Change number or products per row to 3
        function loop_columns() {
            return 3; // 3 products per row
        }

        function medical_form_button(){
            global $post;
            if(has_term( 17, 'product_cat', $post )){
                print '<div class="medical-form-notice medical-form-trigger">You must fill out a medical release form with each registration.</div>';
                print '<a class="button alt medical-form-trigger">Medical Release</a>';
            }
        }

        function medical_form_header(){
            if(is_single() && is_product()){
                global $post;
                if(has_term( 17, 'product_cat', $post )){
                    //add javascript
                    $script = "<script>
jQuery(document).ready(function($) {	
    $('.single_add_to_cart_button').attr('disabled','disabled');
    $('.medical-form-trigger').click(function(){
        $('#medical_modal').modal('show');
    });
  
    $(document).bind('gform_confirmation_loaded', function(event, formId){
        // code to be trigger when confirmation page is loaded
        $('.medical-form-notice').html('Thank you for submitting the medical release. You may now add this registration to your cart.');
        $('#medical_modal').modal('hide');
        $('.single_add_to_cart_button').removeAttr('disabled');
    });
});
</script>";
                    print $script;
                    //add modal with form (ajax)
                    $modal = '
        <div class="modal fade" tabindex="-1" role="dialog" id="medical_modal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body">
        '.do_shortcode('[gravityform id="4" title="true" description="true" ajax="true"]').'
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->';
                    print $modal;
                } /*elseif($post->ID == 200) {
                    print '<div class="denial-overlay">You must register before purchasing tee shirts.</div>';
                }*/
            }
        }


        function id10t_proof_add_to_cart_message($message,$products){
            global $woocommerce;
            //get all products

            $products = array(
                'child' => array('note'=>'Child registration','ID'=>162),
                'minor' => array('note'=>'Minor registration','ID'=>160),
                'adult' => array('note'=>'Adult registration','ID'=>149),
                'tshirt' => array('note'=>'Teeshirt(s)','ID'=>200),
                //'donate' => array('note'=>'Make a donation','ID'=>215),
            );
            foreach($products AS $product){
                $button[] = '<a href="'.get_the_permalink($product['ID']).'" class="button">'.$product['note'].'</a>';
            }
            $buttons = '<div><h3>Would you like to add:</h3>'.implode("\n",$button).'</div>';
            $message = $message.$buttons;
            return $message;
        }


        //util
    }


}

//instance
global $msdlab_tickets;
$msdlab_tickets = new MSDLab_tickets();