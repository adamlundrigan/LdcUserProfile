If you're fortunate enough to be on a *nix system with PHP >=5.4 and `pdo_sqlite`, pop into the `demo` folder and run the setup script (`run.sh`).  This will build the demo application, install the [example profile extension module](demo/ExtensionModule), and start a webserver.  Once that's all done just open your browser and:
 - Navigate to `http://localhost:8080/user/register`
 - Create an account
 - Navigate to `http://localhost:8080/user/profile`

----

NOTE: The demo application has purposefully disabled the editability of the email address field to demonstrate how to toggle fields using the form validation group feature.
