# DB2EAV
The DB2EAV API of mapping database to EAV model as solution of data interoperability and portability
between different Content Management Systems (CMS).

The DB2EAV API is created with the aim of providing a solution to the data interoperability between CMS that
implements an EAV model as design of their databases. The DB2EAV is an API of mapping databases to EAV model.
the API is for a specific design of Database who is EAV, in order to described with details how every database
have implement the design. The mapping is based on an XML file that describes the implementation of the
three components of the EAV model : Entity, Value and Attribute. In addition to database mapping, the API allows
access to a CMS data directly from database with SQL queries.

The DB2EAV API operates in four steps:
1 - Calling the API : the API is based on language PHP, and compatible with 5.3.0 version or higher.
2 - Choosing a Target Host : a Target Host is a Web Site based on CMS. it is used to define access settings of the
CMS's database. A list of all available Target Hosts is defined in an XML file.
3 - Mapping database to EAV model : in this step the API uses an XML mapping file, corresponding to the Target Host
defined in step 2,  to build all SQL queries needed to get content from CMS's database. This mapping file describes how
a CMS implements the three components of the EAV model.
4 - Recovering content from CMS : using API we can retrieve data from remote CMS's database. The data is retrieved
width SQL queries in associative arrays.

# Current version
beta 1.0
Take a look to Demo to have more detail of how using the API
The class diagram is exposed on the file "DB2EAV-ClassDiagram.png"

# RoadMap
Next version will be beta 1.1,
The roadMap can be defined in the next features:
1/ Fixe Bugs on write mode
2/ Use Doctrine as ORM for database
3/ Add a new config template of Wordpres CMS



