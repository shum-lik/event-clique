// $Id: README.txt,v 1.2 2011/01/25 09:27:08 hswong3i Exp $

ABOUT OAUTH2
------------

OAuth 2.0 is the next evolution of the OAuth protocol which was originally
created in late 2006. OAuth 2.0 focuses on client developer simplicity while
providing specific authorization flows for web applications, desktop
applications, mobile phones, and living room devices. This specification is
being developed within the IETF OAuth WG and is based on the OAuth WRAP
proposal.

The latest version of the spec is can be found at:
http://tools.ietf.org/html/draft-ietf-oauth-v2


ABOUT DRUPAL OAUTH2.0
---------------------

OAuth2.0 implements the IETF OAuth2.0 draft fir yse wutg Drupal and acts as a
support module for other modules that wish to use OAuth2.0.

This module currently use the OAuth2.0 PHP library, which is originally
licensed under the MIT license, and it to be found at Google code:
http://code.google.com/p/oauth2-php/. The maintainers of this module will NOT
accept any patches to that library if they haven't been submitted to the
original project first. This is to avoid any license hijacking, and to further
the development of a common OAuth2.0 library for PHP.

This module support both standalone mode (e.g. acting as authorization server)
and proxy mode (e.g. acting as resourse server). Server operate in proxy mode
will redirect all its oauth2-php logic to remote authorization server,
including both access token issue, validate and expire. In order to provide
remote services for resource server, authorization server should install
Services 3.x (http://drupal.org/project/services).

This module also provides OAuth2.0 authentication for the Service 3.x module.


INSTALLATION
------------

Before start with module installation please download oauth2-php from
http://code.google.com/p/oauth2-php/ and extract it under either following
directory:

  * oauth2-php under your Drupal oauth2 modules, e.g. OAuth2.inc will therefore
    under: sites/all/modules/oauth2/oauth2-php/lib/OAuth2.inc
  * If Libraries API (http://drupal.org/project/libraries) is activated,
    extract oauth2-php under sites/all/libraries, e.g. OAuth2.inc will
    therefore under: sites/all/libraries/oauth2-php/lib/OAuth2.inc

OAuth2.0 coming with number of submodules, each provide different
functionalities:

  * Core functionalities:
    * oauth2 : Provides oauth2-php library linkage and default setup, etc.
    * oauth2_provider: Provides functionality for OAuth2.0 when acting as a
      provider.
    * oauth2_consumer: Extend OAuth2.0 Server Identifer with consumer support.

  * Configuration data containers:
    * oauth2_client: Handle OAuth2.0 Client Identifer as Drupal node.
    * oauth2_server: Handle OAuth2.0 Server Identifer as Drupal node.
    * oauth2_scope: Handle OAuth2.0 Scope Identifer as Drupal node.

  * 3rd party modules integration:
    * services_oauth2: Provides OAuth2.0 authentication for the Services 3.x
      module.
    * oauth2_resources: Integrate with Services 3.x, provide APIs for remote
      resource server running in proxy mode.

Besides manually activate above submodules with your own combination, OAuth2.0
also provide following dummy packages for resolve submodule dependency:

  * oauth2_authorize_server: Activate all submodules for acting as
    authorization server. Support Services 3.x integration by default.
  * oauth2_resource_server: Activate all submodules for acting as resource
    server. By default it should combine with a remote authorization server
    for centralized token management.


CONFIGURATION
-------------

Whatever install OAuth2.0 in which mode, standalone or proxy, authorization or
resource, both coming with similar configuration procedures:

  * admin/user/permissions
    * Configurate corresponding permission for OAuth2.0 identifiers, e.g. only
      allow webmaster to add identifier.

  * admin/content/types
    * Configurate the OAuth2.0 identifiers refer to your requirement. Typically
      you would like to disable comment and always create revision during node
      save.
    * In case of vertical_tabs enabled, you would also like to exclude
      "OAuth2.0 settings" from vertical tabs.

  * node/add
    * Add OAuth2.0 identifiers as Drupal node.

  * admin/build/oauth2
    * Include basic configuration of OAuth2.0, e.g. oauth2-php library path,
      operate in standalone or proxy mode with corresponding setup, etc.
    * Optional administration interface is also provided for OAuth2.0
      identifiers, under admin/build/oauth2/*.

  * (optional) admin/build/services/*/edit
    * Configure Services 3.x endpoint with OAuth2.0 authentication.

  * (optional) admin/build/services/*/resources
    * Enable ALL (yes, ALL) actions for oauth2_resource, when acting as
      authorization server AND hope to provide remote resource server
      integration services.


TESTING
-------

Testing OAuth2.0 setup manually is a bit complicated, and required for detail
understanding of overall OAuth2.0 logic which will not detail within this
document. Here we will go though a simple test case with authorization server
operate in standalone mode:

  * Install dummy package oauth2_authorize_server.

  * Add a new OAuth2.0 Client Identifier under node/add/oauth2-client, with
    "Site URL" as your Drupal base URI, e.g. http://example.com/

  * Go to admin/build/oauth2 and confirm if all basic configuration are setup
    correctly.

  * In your browser, type in following URL pattern and replace with your own
    OAuth2.0 Client Identifier setup (line breaks are for display purposes
    only):

    http://example.com/oauth2/authorize
      ?response_type=token
      &client_id=<client_id>

  * After page redirect your URL should now become following pattern with
    access_token included (line breaks are for display purposes only):

    http://example.com/
      #access_token=<access_token>
      &expires_in=3600
      &refresh_token=<refresh_token>

Up to this point your setup should successfully operate as OAuth2.0
authorization server in standalone mode. For other detail test case please
study with http://tools.ietf.org/html/draft-ietf-oauth-v2-10.


TESTING FOR DEVELOPER
---------------------

OAuth2.0 already build in with some Simpletest
(http://drupal.org/project/simpletest) test cases. Once install with Simpletest
go to admin/build/testing and you will able to test OAuth2.0 under "OAuth2.0"
section. For more information please visit Simpletest's official documents.


LIST OF MAINTAINERS
----------------------

PROJECT OWNER
M: Edison Wong <hswong3i@pantarei-design.com.com>
S: maintained
W: http://pantarei-design.com/
