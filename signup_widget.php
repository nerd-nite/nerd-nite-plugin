<?php
class NerdNiteSignup_Widget extends WP_Widget {
        function NerdNiteSignup_Widget() {
                $widget_ops = array('classname' => 'NerdNiteSignup', 
                                    'description' => 'A sign-up form for the Nerd Nite mailing lists' );
                $this->WP_Widget('NerdNiteSignup', __('Sign up'), $widget_ops);
        }

        function form($instance){
            //Defaults
            $instance = wp_parse_args( (array) $instance, array('list_id'=>'0', 'list_name'=>'') );
        
            $list_id = htmlspecialchars($instance['list_id']);
            $list_name = htmlspecialchars($instance['list_name']);
        
            # Output the options
			echo '<p style="text-align:right;"><label for="' . 
				$this->get_field_name('list_name') . 
				'">' . __('List name:') . 
				' <input style="width: 150px;" id="' . 
				$this->get_field_id('list_name') . 
				'" name="' . 
				$this->get_field_name('list_name') . 
				'" type="text" value="' . 
				$list_name . 
				'" /></label></p>';
				
            echo '<p style="text-align:right;"><label for="' . 
            	$this->get_field_name('list_id')   . 
            	'">' . __('List ID:')   . 
            	' <input style="width: 30px;" id="'. 
            	$this->get_field_id('list_id') . 
            	'" name="' . 
            	$this->get_field_name('list_id') . 
            	'" type="text" value="' . 
            	$list_id . 
            	'" /></label></p>';
        }

        function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $instance['list_id'] = strip_tags(stripslashes($new_instance['list_id']));
            $instance['list_name'] = strip_tags(stripslashes($new_instance['list_name']));
        
            return $instance;
        }

        function widget($args, $instance) {
        	extract($args);
            wp_enqueue_script( 'signup_widget' );
            wp_enqueue_style( 'signup_widget' );
            wp_enqueue_style( 'qtip' );
            $list_id   = empty($instance['list_id']) ? 0 : $instance['list_id'];
        	$list_name = empty($instance['list_name']) ? 'nerdnite' : $instance['list_name'];
            // outputs the content of the widget

        	# Before the widget
        	echo $before_widget;
         	echo $before_title . apply_filters('widget_title', 'Stay in touch here') . $after_title;
        	if($list_id == 0) {
                echo "Waiting for Nerd Nite boss to set up mailing list";
        	}
        	else {
        ?>
        <div id='signup-content'>
        <p>Sign up here for updates about Nerd Nite events in your city.</p>
<script src="/js/list_subscribe_checker.js" language="Javascript" type="text/javascript"></script>
<form method=post name="subscribeform" action="http://nerdnite.com/lists/?p=subscribe&id=42" target="nnSignup" onsubmit="window.open('', this.target,
'dialog,modal,scrollbars=no,resizable=no,width=550,height=300,left=0,top=0');">
        <div class="required">Name</div>
    <span class="attributeinput">
            <input type=text name="attribute1"  class="attributeinput" size="20" value=""/>
           <script language="Javascript" type="text/javascript">addFieldToCheck("attribute1","Name");</script>
    </span>
    <div class="required">Email</div>
        <span class="attributeinput">
           <input type=text name=email value="" size="20"/>
        <script language="Javascript" type="text/javascript">addFieldToCheck("email","Email");</script>
    </span>
    <div class="required">Confirm your email address</div>
    <span class="attributeinput">
            <input type=text name=emailconfirm value="" size="20"/>
        <script language="Javascript" type="text/javascript">addFieldToCheck("emailconfirm","Confirm your email address");</script>
    </span>
    <input type=hidden name="htmlemail" value="0"/>
    <div class="required">Would you like to present, some day? <span class="attributeinput">
            <!--0--><select name="attribute3" class="attributeinput">
                    <option value="4" >Yes</option>
                    <option value="5" >No</option>
                    <option value="6" selected="selected">Maybe</option>
            </select>
    </span></div>
    
    <input type="hidden" name="list[<?php echo $list_id; ?>]" value="signup"/>
    <input type="hidden" name="listname[<?php echo $list_id; ?>]" value="<?php echo $list_name; ?>"/>
    
<div class="required">Add me to the Global Nerd Nite list too:  <span class="attributeinput">
      <input type="checkbox" name="add_to_global" id="add_to_global" checked />
    </span></div>
<div id="global-list-info">more info</div>
   
		<input class="global-list" type="hidden" name="list[5]" value="signup"/>
    	<input class="global-list" type="hidden" name="listname[5]" value="nerdnite-global"/>  	
    <div style="display:none">
        <input type="text" name="VerificationCodeX" value="" size="20"/>
    </div>
    <p>
        <input type=submit name="subscribe" value="Sign Up" onclick="return checkform();"/>
    </p>
 </form>
 </div>
        <?php
        }
        # After the widget
        echo $after_widget;
        }

	}
	
	function NerdNiteSignup_Init() {
  		register_widget('NerdNiteSignup_Widget');
  		wp_register_script( 'qtip', plugins_url('/jquery.qtip.min.js', __FILE__), array('jquery') );
  		wp_register_script( 'signup_widget', plugins_url('/signup_widget.js', __FILE__), array('jquery', 'qtip'), '1.01' );
  		
  		wp_register_style( 'signup_widget', plugins_url('/signup_widget.css', __FILE__), array(), '1.05' );
  		wp_register_style( 'qtip', plugins_url('/jquery.qtip.min.css', __FILE__) );
  	}
	add_action('widgets_init', 'NerdNiteSignup_Init');

?>