
****************************
  Prerequisites
****************************
1. Create a FaceBook app
You'll need to create and register an application with FaceBook. Use the
following URL to do so:
http://www.facebook.com/developers/createapp.php

2. Install Boxy (0.1.4)
Download boxy into your libraries folder (e.g. sites/all/libraries)
http://plugins.jquery.com/files/boxy-0.1.4.zip


****************************
  Installation
****************************
1. Place this module in your module folder
2. Enable this module
3. Configure the module (admin/settings/simple_fbwall)
   1.1 Go to admin/settings/simple_fbwall
   1.2 Enter your application ID (this is the only value you need; the api key and secret key are unnecessary)


****************************
  Usage
****************************
This module provides a single API function, which consists of a standard Drupal form that can be
called/printed wherever you desire, by using drupal_get_form('simple_fbwall_post_button', $payload).
It takes as an argument a payload array, containing the various elements of a wall post. Here is a
sample payload and subsequent call to get the Simple Facebook Wall Post form:

$example_payload = array(
  'message' => 'Hello World!',
  'picture' => 'http://www.example.com/example.jpg',
  'actions' => array(
    array(
      'name' => 'Visit Example.com',
      'link' => 'http://www.example.com/',
    ),
  'link' => array(
    'link' => 'http://www.example.com/example_page',
    'name' => 'Example Page',
    'caption' => 'This is the example page',
    'description' => 'The example page has a lot of stuff on it that is used for examples.',
  ),
);

drupal_get_form('simple_fbwall_post_button', $example_payload);


****************************
  Limitations
****************************
1. FaceBook only accepts 1 action
   Even though actions are structured as an array, the Simple Facebook Wall Post
   module only sends the first defined action in order to comply with the FaceBook API.

2. This module's JS prevents the form submit from executing, so you cannot hook into 
   form post processing (we're thinking about exposing a post submit hook, if someone
   needs it).