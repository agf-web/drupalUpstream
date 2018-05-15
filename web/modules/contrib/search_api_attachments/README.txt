Search Api Attachments

This module will extract the content out of attached files using chosen method
among:
 - the Tika library
 - the build in Solr extractor
 - the Pdftotext command line tool
 - the python Pdf2txt extractor
and index it.
Search API attachments will index many file formats.

REQUIREMENTS
------------
Requires the ability to run java on your server and an installation of the
Apache Tika library if you don't want to use the Solr build in extractor.

MODULE INSTALLATION
-------------------
Copy search_api_attachments into your modules folder

Install the search_api_attachments module in your Drupal site

Go to the configuration: admin/config/search/search_api_attachments

Choose an extraction method and follow the instructions under the respective
heading below.


EXTRACTION CONFIGURATION (Tika)
-------------------------------
On Ubuntu 14.04

Install java
> sudo apt-get install openjdk-7-jdk

Download Apache Tika library: http://tika.apache.org/download.html
> wget http://mir2.ovh.net/ftp.apache.org/dist/tika/tika-app-1.8.jar

Enter the full path on your server where you downloaded the jar
e.g. /var/apache-tika/tika-app-1.8.jar.

EXTRACTION CONFIGURATION (Solr)
-------------------------------
Install and configure the search_api_solr module
https://www.drupal.org/project/search_api_solr
Make sure to configure it as explained in its README.txt
Create at least one solr server
Now you can choose it from /admin/config/search/search_api_attachments

Note 1: For Solr extraction to work, we need solarium in 3.3.0 or greater.
Note 2: "lazy loading error"
If you obtain this error, you may need some extra configuration of solr:
Per example with solr 4.10.4, in addition to the configuration suggested in
search_api_solr README.txt, you need to update your solrconfig.xml file
(full path can look like example/solr/collection1/conf/solrconfig.xml)
Change the /update/extract request Handler class like this :

<requestHandler name="/update/extract"
    class="org.apache.solr.handler.extraction.ExtractingRequestHandler" >

This means that you delete this part:
- startup="lazy"
- class="solr.extraction.ExtractingRequestHandler" >

Then in example folder:
cp -r ../contrib/extraction/lib solr/collection1/lib
cp ../dist/solr-cell-4.10.4.jar solr/collection1/lib/

EXTRACTION CONFIGURATION (Pdftotext)
------------------------------------
Pdftotext is a command line utility tool included by default on many linux
distributions. See the wikipedia page for more info:
https://en.wikipedia.org/wiki/Pdftotext

EXTRACTION CONFIGURATION (python Pdf2txt)
-----------------------------------------
On Debian 8

Install Pdf2txt (tested with package version 20110515+dfsg-1 and python 2.7.9)
> sudo apt-get install python-pdfminer

SIMPLE USAGE EXAMPLE
--------------------
0) This is tested with :
   drupal 8.1.x
   search_api 8.x-1.x
   search_api_attachments 8.x-1.x
1) Install drupal, search_api search_api_db and search_api_attachments.
2) Go to admin/structure/types/manage/article/fields/add-field and add a
   file field 'My pdfs' (field_my_pdfs).
3) Go to node/add/article and add a node with a pdf.
4) Go to admin/config/search/search_api_attachments and configure the
   Tika extractor.
5) Go to admin/config/search/search-api/add-server and add server 'My server'
   (my_server) with the default Database Backend.
6) Go to admin/config/search/search-api/add-index and add a new index 'My index'
   (my_index) with 'Content' as Data source and 'My server' as Server.
7) Go to admin/config/search/search-api/index/my_index/processors and enable
   the File attachments processor.
8) Go to admin/config/search/search-api/index/my_index/fields/add and:
   - in the General section, add the "Search api attachments: My pdfs" field.
   - in the Content section, add the "Title".
   - in the Content section, add the "Body".
9) Go to /admin/config/search/search-api/index/my_index/fields to configure
   "Search api attachments: My pdfs" and "Title" to Fulltext.
10) Go to admin/structure/views/add and add a Page view:
    - View name: SAA
    - View settings:Show: Index My index
    - Page settings: Check Create a page with title and path 'saa' that
      displays "Rendered entity" format.
    ("Search results" format seems not working for now)
11) Add a filter to the view: the 'Fulltext search' with
    - Operator : Contains any of these words
    - Check the Expose checkbox
12) Go to admin/structure/views/view/saa and in the "Exposed Form" section, hit
    the Basic link and choose 'Input required' so that the view doesn't display
    any default results.
13) Go to admin/config/search/search-api/index/my_index and Index items
14) Go to /saa and search for any term in the title, body or in the pdf file :)

HOOKS
-----
This module provides hook_search_api_attachments_indexable.
See more details in search_api_attachments.api.php
