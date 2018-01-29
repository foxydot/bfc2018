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
        //construct
        function __construct() {
            add_action('yith_wcevti_custom_option',array(&$this,'add_select'),10,2);
            add_action('yith_wcevti_custom_field',array(&$this,'custom_field_frontend'),10,4);

            add_filter('woocommerce_sale_flash',array(&$this,'edit_sale_text'),10,3);
            add_filter('loop_shop_columns', array(&$this,'loop_columns'),15);

        }
        //methods
        //core
        function add_select($field,$index){
            $ret = '<option value="select" '.selected( isset( $field['_type'] ) && $field['_type'] == 'select' ) .' >'. __( 'Select', 'yith-event-tickets-for-woocommerce' ) .'</option>';
            print $ret;
        }

        function custom_field_frontend($field, $index, $row, $label){
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
        //util
    }

}

//instance
global $msdlab_tickets;
$msdlab_tickets = new MSDLab_tickets();