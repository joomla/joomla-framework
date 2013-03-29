## The Uri Package

The Uri package provides you with a simple object oriented approach to deal with uris.

# Provided classes

The Uri packages provides one Interface and two implementations of that interface.

The Uri class is a mutable object which you'd use to manipulate an Uri.

To pass along an uri as value use UriImmutable, this object gurantees that the code you pass the object into can't manipulate it and, causing bugs in your code.

If only read access is required it's recommended to type hint against the UriInterface. This way either an Uri or an UriImmutable object can be passed.

The UriHelper class only contains one method parse_url() that's an UTF-8 safe replacement for PHP's parse_url().
