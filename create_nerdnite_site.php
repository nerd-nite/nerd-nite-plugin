<?php
include("xmlapi.php");
add_action('network_admin_menu', 'nerdnite_menu');
add_action('wp_print_scripts', 'nerdnite_menu_scripts');

function nerdnite_menu_scripts() {
	wp_register_script( 'appendo', plugins_url('/jquery.appendo.js', __FILE__), array('jquery') );
	wp_enqueue_script( 'appendo' );

}

function nerdnite_menu() {
	add_menu_page( "Nerd Nite", "Nerd Nite", 'manage_network', 'create-nerdnite', 'create_nn_site_form');;
}

function create_nn_site_form() {
	$hidden_field_name = 'create-site-field';
	if (!current_user_can('manage_network'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	if( isset($_POST[ $hidden_field_name ]) && 
			  $_POST[ $hidden_field_name ] == 'Y' )  {
		process_form();
	}
	?>
<style type="text/css">
fieldset {
	border: solid thin black;
	padding: 0.5em;
}

label {
	width: 10em;
	display: block;
}

td {
	background-color: #ffffcc;
	padding: 0.4em;
}
</style>
<h1>Create a Nerd Nite</h1>
<p>Use this form to create a new Nerd Nite site.</p>
<form name="form1" method="post" action="">
	<fieldset>
		<legend>City Details</legend>
		<input type="hidden" name="<?php echo $hidden_field_name; ?>"
			value="Y"> <label for="city_name">City Name</label> <input
			type="text" name="city_name" value="" size="20"><br /> <label
			for="city_domain">Domain Name</label> <input type="text"
			name="city_domain" value="" size="20">.nerdnite.com<br /> <label
			for="city_email">Email</label> <input type="text" name="city_email"
			value="" size="20">@nerdnite.com<br />
	</fieldset>
	<fieldset>
		<legend>Bosses</legend>
		<script type="text/javascript">
	jQuery.appendo.opt.labelAdd = "Add Boss";
	jQuery.appendo.opt.labelDel = "Delete Boss";
	</script>
		<p>There are two types of email delivery:</p>
		<dl>
			<dt>Mailbox delivery</dt>
			<dd>With this type, emails go into your very own Nerd Nite mailbox.
				You can then access them via IMAP or Webmail. This also means that
				you can send emails from this address.</dd>
			<dt>Forwarder delivery</dt>
			<dd>
				With this type, emails are simply forwarded to your current email
				address. In order to <i>send</i> from your Nerd Nite address, you
				need to set up your email client to fake it.
			</dd>
		</dl>
		<table class="appendo">
			<tr>
				<td><label for="name[]">Name</label><input type="text" name="name[]" />
				</td>
				<td><label for="current_email[]">Current email</label><input
					type="text" name="current_email[]" /></td>
				<td><label for="desired_email[]">NN Email</label> <input type="text"
					name="desired_email[]" />@nerdnite.com</td>
				<td><label for="email_type[]">Delivery method</label><select
					name="email_type[]">
						<option value="mailbox">Mailbox</option>
						<option value="forwarder">Forwarder</option>
				</select></td>
			</tr>
		</table>

	</fieldset>

	<p class="submit">
		<input type="submit" name="Submit" class="button-primary"
			value="Create Site" />
	</p>

</form>
	<?php
}

function process_form() {
	require_once(ABSPATH . WPINC . '/registration.php');
	$ip = 'server.lizziebracken.com';

	$account  = 'nerdnite';
	$password = 'aus78702';
	$xmlapi = new xmlapi($ip);
	//$xmlapi->set_debug(1);
	$xmlapi->set_port(2083);

	$xmlapi->password_auth($account,$password);
	?>
<pre>
	<?php
	var_dump($_POST);
	$bosses = array();
	for($i = 0; $i < sizeof($_POST['name']); ++$i)
	{
		$boss = array(
		name 			=> $_POST['name'][$i],
		current_email	=> $_POST['current_email'][$i],
		desired_email	=> $_POST['desired_email'][$i],
		email_type		=> $_POST['email_type'][$i],
		);
		array_push($bosses, $boss);
	}
	var_dump($bosses);
	/*
	 * Check that the city domain is available
	 */
	$city_exists = domain_exists($_POST['city_domain'].".nerdnite.com", "/");
	if($city_exists) {
		wp_die( __('You are trying to create a Nerd Nite site that already exists!'));
	}

	/*
	 * Check that the city email is available
	 */
	$email_exists_in_wp = email_exists($_POST['city_email']."@nerdnite.com");
	$forwards  = $xmlapi->api2_query($account, "Email", "listforwards");
	$mailboxes = $xmlapi->api2_query($account, "Email", "listpops");

	$emails    = array();
	if($mailboxes->event->result != 1) {
		wp_die( __('Not able to fetch full NN email list: '.$mailboxes->event->reason));
	}
	elseif($forwards->event->result != 1) {
		wp_die( __('Not able to fetch full NN email list: '.$forwards->event->reason));
	}
	else {
		foreach ($forwards->data as $forward) {
			array_push($emails, (string) $forward->dest[0]);
		}
		foreach ($mailboxes->data as $mailbox) {
			array_push($emails, (string) $mailbox->email[0]);
		}
		$city_email_exists = in_array($_POST['city_email']."@nerdnite.com", $emails);
		print "City email in WP? ".($email_exists_in_wp?'Yes':'No')."\n".
			      "City email in CP? ".($city_email_exists?'Yes':'No')."\n";			      
	}

	/*
	 * Check that the bosses' emails are available
	 */
	foreach($bosses as $boss) {
		print "Name: ".$boss['name'];
		$boss_email_exists = in_array($boss['desired_email']."@nerdnite.com", $emails);
		print ": Exists? ".($boss_email_exists?'Yes':'No')."\n";
	}
	?></pre>
	<?php
}
?>