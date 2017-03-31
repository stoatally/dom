# Document Object Model

This version of the DOM separated the custom functionality entirely from the libxml objects. It did this by 
assigning every instance of a libxml object an instance of our custom objects that could be loaded at any time. 
Unfortunately due to what I suspect is garbage collection, the references to our custom objects were frequently 
lost rendering this effort useless.
