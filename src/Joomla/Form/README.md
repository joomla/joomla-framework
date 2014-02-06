# The Form Package

## Introduction ##

The Form Package provides an easy interface to create, display, and validate forms.

To use the Form Package in your code include:

	use namespace Joomla\Form\Form

at the top of your PHP class.

## Installation via Composer

Add `"joomla/form": "dev-master"` to the require block in your composer.json, make sure you have `"minimum-stability": "dev"` and then run `composer install`.

```json
{
	"require": {
		"joomla/form": "dev-master"
	},
	"minimum-stability": "dev"
}
```

Alternatively, you can simply run the following from the command line:

```sh
composer init --stability="dev"
composer require joomla/form "dev-master"
```

## Creating a Form & Loading Data ##

To use the Form Package in your code include:

	use namespace Joomla\Form\Form

You will now be able to create form objects either programmatically or loading forms from an XML file.

### Form File Structure ###

The Form Package requires forms in valid XML.  For example, a form to define a customer would use this XML:

	<?xml version="1.0" encoding="utf-8"?>
	<form>
		<fields>
			<field name="id" type="hidden" label=""/>
			<field name="first_name" type="text" label="First Name" required="true"/>
			<field name="last_name" type="text" label="Last Name" required="true"/>
			<field name="street" type="text" label="Street" required="false"/>
			<field name="suburb" type="text" label="suburb" required="false"/>
			<field name="state" type="list" label="state" required="false">
				<option value="QLD">Queensland</option>
				<option value="NSW">New South Wales</option>
				<option value="ACT">Australian Capital Territory</option>
				<option value="VIC">Victoria</option>
				<option value="TAS">Tasmania</option>
				<option value="SA">South Australia</option>
				<option value="WA">Western Australia</option>
				<option value="NT">Nothern Territory</option>
				<option value="N/A">Outside Australia</option>
			</field>
			<field name="postcode" type="text" label="Postcode" required="false"/>
			<field name="email" type="text" label="Email" validate="email" required="false"/>
			<field name="phone" type="text" label="Phone" validate="tel" required="true" />
		</fields>
	</form>

More advanced forms can also group forms into field sets and groups.

This simple form defines a number of fields as text inputs, one list (displayed as select box) and one hidden input for the customer's id field.

Each element has the following mandatory attributes:

* name - the name by which the field will be referred to in the form and if manually loading form elements.
* type - the type of element that will be rendered.  For example text will generate a text field while list would generate a select box.
* label - the label to be applied to the element.

The following optional attributes are also available:

* required - whether the element is mandatory or not.
* validate - which of the validation rules will be applied when running form validation
* label class - any optional classes that should be applied to the label.  This is particularly useful if you want to make sure your forms render well in responsive templates
* class - any optional classes that should be applied to the field itself.

### Loading a Form ###

Once you have created the form XML you need to load the form into your code for use.

The first step is to create a Form object.  At minimum constructing a new form object requires naming the form.  To create a minimal form object you can use the below:

	$clientForm = new Form('client');

The Form is now initialised and ready for use.  Presently it doesn't have any fields or data associated with it.

The Form class provides two methods for loading a form:

* Form::load - can be used to load a form with the fields defined in a SimpleXMLElement.  This may have been created programatically or loaded previously.
* Form::loadFile - passes a string reference to an XML file that can be used as a form.

Either way the result is the same.  Your form is now loaded with fields and ready for use.

### Binding Data to a Form ###

If the Form is being used to display data you need to populate the form fields.  This is done using the Form::bind() method.  This method takes either an array or an object and populates the form element values with the keys or properties sharing the same name.

For example using the customer form defined above this can be filled with an array like such:

	$customer = new Array('first_name'=>'John','last_name'=>'Smith'...);
	$customerForm->bind($customer);

If you are using groups within your form you would need to nest these keys under a parent element.


## Displaying Your Form Elements ##

Presumably you at some point would  like to display your form for data entry.  To display your form there are two approaches depending on the level of control you would like over the positioning and layout of options.

### Option 1: Just Output the Elements as They Are ###

This is the easiest option and useful when you have a small, simple form, or don't much care about customising the layout of the form.

Load your form fields using the Form::getGroup() method.  This requires a string indicating which field group you would like to load.  If you have not used field groups you can just pass in a blank string.  For example to load the fields from the customer form I would use:

	$fields = $customForm->getGroup('');

Once the fields have been loaded with the form you can then loop through these to display the label, input field, and if bound the data.

	foreach ($fields as $field) {
		echo $field->label;
		echo $field->input;
	}

### Option 2: Displaying a Form With More Control ###

If you want to exert a little more control over how the form is laid out you can access each field by name.  For example to load the input and the label for the *first_name* field you would use the below:

	echo $form->getLabel('first_name');
	echo $form->getInput('first_name');

### Additional Options for Controlling Form Display ###

Each element in the form can also be assigned attributes class and labelclass.  These contain additional classes that will be applied to the label and the input element.  This can be used when designing responsive forms with bootstrap classes.

## Validating Your Form Data ##

The Joomla Form object allows easy validation of fields.  The Form object provides a validate method that tests the supplied user input against validation rules defined in the XML.

Before you can validate form data it is necessary to load the form again. (See Loading a Form above).  

Once the form has been reloaded, don't bind data to it.  This time call the Form::validate() method passing in an array containing the data you wish to validate.  For example:

	$clientForm->validate($_POST);

This will validate the data stored in the POST array against the validation rules defined in the form.

Validation errors are retrievable by using the Form::getErrors() function:

	$clientForm->getErrors();

Presently this will return an array of RuntimeException Objects.

Validation rules are defined using the *validate* attribute in XML. The following rules are currently defined:

* Boolean - tests whether a field is true or false
* Color - tests whether a field contains a valid hexadecimal colour value. (ie. #00000 to #fffff).  Does not require input of the '#'.
* Email - tests whether a field contains an email value.
* Equals - tests if two values are equal. 
* Options - endures that the value entered is one of the options given in a list.
* Tel - validates a field as a telephone number.  An optional attribute *plan* can also be specified to define the phone pattern.  Current options are: northamerica, us, International, int, missdn, IETF.
* Url - validates a field as a URL.