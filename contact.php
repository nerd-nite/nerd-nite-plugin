<?php
require_once (ABSPATH . WPINC . '/registration.php');

add_filter('the_content', 'handleContactForm');

function handleContactForm($content) {
    if (preg_match('[contact]', $content)) {
        if ($_REQUEST['action'] == "contact") {
            return processContactForm($content);
        } else {
            return showContactCForm($content);
        }
    } else {
        // Return the content untouched
        return $content;
    }
}


function processContactForm($content) {
    global $recaptcha_opt;

    $errors = array();

    // Check the reCAPTCHA first, to prevent username sniffing
    $response = recaptcha_check_answer('6LcAUQYAAAAAAM6FS1ZdvTs8wHjMGvAVPSheQg6N',
                                       $_SERVER['REMOTE_ADDR'],
                                       $_POST['recaptcha_challenge_field'],
                                       $_POST['recaptcha_response_field']);

    // Check the email address
    $emailAddress = trim($_POST['emailAddress']);
    if (strlen($emailAddress) == 0) {
        $errors['emailAddress'] = "Please provide an email address";
    }


    // Check that we have some sort of name

    $realname = strtolower(trim($_POST['realname']));
    if (strlen($realname) == 0) {
        $errors['realname'] = "Please provide your name";
    }


    // Check that we have some sort of subject

    $subject = strtolower(trim($_POST['subject']));
    if (strlen($subject) == 0) {
        $errors['subject'] = "Please provide a subject";
    }


    // Check that we have a message
    $message = strtolower(trim($_POST['message']));
    if (strlen($message) == 0) {
        $errors['message'] = "Please provide a message";
    }

    $errors['recaptcha'] = 'OK';

    if (!$response->is_valid) {
        $errors['recaptcha'] = $response->error;
        return showContactCForm($content, $errors, $_POST);
    }

    /*
     * Need to fix the fact that $errors['recaptcha'] is always defined
     */
    if ((count($errors) > 0) && ($errors['recaptcha'] != "OK")) {
        return showContactCForm($content, $errors, $_POST);
    } else {
        $city = explode('.', $_SERVER['SERVER_NAME']);

        // Everything appears to be in order
        wp_mail("$city[0]@nerdnite.com", "[WEB]: $subject", "$message\n\nFrom:$realname <$emailAddress>", "From: $emailAddress");

        return str_replace("[contact]", "<b>Your message has been sent</b>", $content);
    }
}


function showContactCForm($content, $errors = array(), $values = array()) {
    global $recaptcha_opt;

    if (strlen($values['emailAddress']) > 0) {
        $values['emailAddress'] = '[nohide]' . $values['emailAddress'] . '[/nohide]';
    }

    $recaptchaHTML = recaptcha_get_html('6LcAUQYAAAAAAHFnarAkNcKixfesnSTHTirV0nnD', $errors['recaptcha']);

    $form = <<<FORM
	<form method="post" action="$_SERVER[REQUEST_URI]" enctype="multipart/form-data" id="startCampForm">
        <fieldset>
            <legend>About You</legend>
            <span class="error">$errors[realname]</span><br />
            <label for="name">Name<span class="required">*</span></label><input type="text" name="realname" id="realname" value="$values[realname]" /><br />
            <span class="error">$errors[emailAddress]</span><br />
            <label for="emailAddress">Email<span class="required">*</span></label><input type="text" name="emailAddress" id="emailAddress" value="$values[emailAddress]" /><br />
            <span class="error">$errors[subject]</span><br />
            <label for="subject">Subject<span class="required">*</span></label><input type="text" name="subject" id="subject" value="$values[subject]" /><br />
        </fieldset>

        <fieldset>
        	<legend>Your Message</legend>
            <span class="error">$errors[message]</span><br />
            <label for="message">Message<span class="required">*</span></label><textarea name="message" id="message" cols="40" rows="10">$values[message]</textarea><br />
        </fieldset>

        <fieldset>
            <legend>Registration</legend>
            <p>Like you, we hate spam. Please fill in this CAPTCHA field, to prove you are a human:</p>
                $recaptchaHTML
            <input type="hidden" name="action" value="contact"/>
            <input name="formSubmit" type="submit" id="formSubmit" value="Send message" />
        </fieldset>
    </form>
FORM;

    return str_replace("[contact]", $form, $content);
}


function pdcSACFormStyling() {
    ?>

<style type="text/css">
    label {
        float: left;
        text-align: right;
        margin-right: 15px;
        width: 100px;
    }

    input:focus {
        border: 2px solid #900;
    }

    span.error {
        color: #ff0000;
        font-size: x-small;
        padding-left: 1em;
    }
</style>

<?php
}


add_action('wp_head', 'pdcSACFormStyling');



?>

