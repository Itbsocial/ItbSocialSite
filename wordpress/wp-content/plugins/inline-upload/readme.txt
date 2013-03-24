=== Inline Upload ===
Contributors: nickboss
Donate link: http://www.iptanus.com/?page_id=27
Tags: upload, upload file, progress bar, form, ajax, directory, HTML5
Requires at least: 2.9.2
Tested up to: 3.4.1
Stable tag: "trunk"

Simple plugin to upload files from any page

== Description ==

With this plugin you can upload files to your site from any page by using shortcodes.

Simply put the shortcode [inline_upload] to the contents of any WordPress page and you will be able to upload files to any directory inside wp-contents of your WordPress site.

The characteristics of the plugin are:

* It does not use flash, so it can work in any browser, even in mobile phones.
* You can have more than one instances of the shortcode in the same page.
* It shows an upload progress bar (in browsers supporting HTML5)
* It supports localization.
* It integrates with WP-Filebase.
* It is highly customizable.
* It produces notification messages and e-mails.

Please visit the **Other Notes** section for customization options of this plugin.

== Installation ==

1. First copy inline_upload directory inside wp-contents/plugins directory of your wordpress site.
1. Activate the plugin from Plugins menu of your Dashboard.
1. In order to use the plugin simply put the shortcode [inline_upload] in the contents of any page.

== Frequently Asked Questions ==

= Will the plugin work in a mobile browser? =

Yes, the plugins will work in most mobile phones (has been tested in iOS, Android and Symbian browsers as well as Opera Mobile) 

= Do I need to have Flash to use then plugin? =

No, you do not need Flash to use the plugin.

= I get an SAFE MODE restriction error when I try to upload a file. Is there an alternative?  =

Your domain has probably turned SAFE MODE ON and you have restrictions uploading and accessing files. Inline Upload includes an alternative way to upload files, using FTP access. Simply add the attribute **accessmethod="ftp"** inside the shortcode, together with FTP access information in **ftpinfo** attribute.

= Can I see the progress of the upload? =

Yes, you can see the progress of the upload. During uploading a progress bar will appear showing progress info, however this functionality functions only in browsers supporting HTML5 upload progress bar.

== Screenshots ==

1. A screenshot of the plugin in its most simple form.
2. A screenshot of the plugin showing the progress bar.
3. A screenshot of the plugin showing the successfull upload message.
4. A screenshot of the plugin with different placement.

== Changelog ==

= 1.7.11 =
* Added single button operation (file will be automatically uploaded when selected without pressing Upload Button).

= 1.7.10 =
* Fixed bug with functionality of attribute filebaselink for new versions of WP-Filebase plugin.

= 1.7.9 =
* Fixed problem with functionality of attribute filebaselink for new versions of WP-Filebase plugin.

= 1.7.8 =
* More than one roles can now be defined in attribute uploadrole, separated by comma (,).

= 1.7.7 =
* Variable %filename% now works also in redirectlink.

= 1.7.6 =
* Changes in ftp functionality, added useftpdomain attribute so that it can work with external ftp domains as well.
* Improvement of classic upload (used in IE or when setting forceclassic to true) messaging functionality.
* Minor bug fixes.

= 1.7.5 =
* Source modified so that it can work with Wordpress sites that are not installed in root.
* Added variable %blogid% for use with multi-site installations.
* Bug fixes related to showing of messages.

= 1.7.4 =
* Replacement of json2.js with another version.

= 1.7.3 =
* CSS style changes to resolve conflicts with various theme CSS styles.

= 1.7.2 =
* Added variable %useremail% used in notifyrecipients, notifysubject and notifymessage attributes.

= 1.7.1 =
* Added capability to upload files outside wp-content folder.
* Improved error reporting.

= 1.7 =
* Complete restructuring of plugin HTML code, in order to make it more configurable and customizable.
* Appearance of messages has been improved.
* Added option to put the plugin in testmode.
* Added option to configure the colors of success and fail messages.
* Added option to modify the dimensions of the individual objects of the plugin.
* Added option to change the placement of the individual objects of the plugin.
* Improved error reporting.
* Added localization for error messages.
* Minor bug fixes.

= 1.6.3 =
* Bug fixes to correct incompatibilities of the new ajax functionality when uploadrole is set to "all".

= 1.6.2 =
* Bug fixes to correct incompatibilities of the new ajax functionality with redirectlink, filebaselink and adminmessages.

= 1.6.1 =
* Correction of serious bug that prevented the normal operation of the plugin when the browser of the user supports HTML5 functionality.
* Tags added to the plugin Wordpress page.

= 1.6 =
* Major lifting of the whole code.
* Added ajax functionality so that file is uploaded without page reload (works in browsers supporting HTML5).
* Added upload progress bar (works in browsers supporting HTML5).
* Added option to allow user to select if wants to use the old form upload functionality.
* File will not be saved again if user presses the Refresh button (or F5) of the page.
* Translation strings updated.
* Bug fixes for problems when there are more than one instances of the plugin in a single page.

= 1.5 =
* Added option to notify user about upload directory.
* Added option to allow user to select a subfolder to upload the file.

= 1.4.1 =
* css corrections for bug fixes.

= 1.4 =
* Added option to attach uploaded file to notification email.
* Added option to customize message on successfull upload (variables %filename% and %filepath% can be used).
* Added option to customize color of message on successfull upload.
* "C:\fakepath\" problem resolved.
* warning message about function create_directory() resolved.
* css enhancements for compatibility with more themes.

= 1.3 =
* Additional variables added (%filename% and %filepath%).
* All variables can be used inside message subject and message text.
* Added option to determine how to treat dublicates (overwrite existing file, leave existing file, leave both).
* Added option to determine how to rename the uploaded file, when another file already exists in the target directory.
* Added option to create directories and upload files using ftp access, in order to overcome file owner and SAFE MODE restrictions.
* Added the capability to redirect to another web page when a file is uploaded successfully.
* Added the option to show to administrators additional messages about upload errors.
* Bug fixes related to interoperability with WP_Filebase

= 1.2 =
* Added notification by email when a file is uploaded.
* Added the ability to upload to a variable folder, based on the name of the user currently logged in.

= 1.1 =
Added the option to allow anyone to upload files, by setting the attribute uploadrole to "all".

= 1.0 =
Initial version.

== Upgrade Notice ==

= 1.7.11 =
Optional upgrade to add new features.

= 1.7.10 =
Important upgrade to correct bug with filebaselink attribute functionality.

= 1.7.9 =
Important upgrade to resolve issue with filebaselink attribute functionality.

= 1.7.8 =
Optional upgrade to add new features.

= 1.7.7 =
Optional upgrade to add new features.

= 1.7.6 =
Optional upgrade to add new features and make minor bug fixes.

= 1.7.5 =
Important upgrade to resolve issues with Wordpress sites not installed in root.

= 1.7.4 =
Important upgrade to resolve issues with json2 functionality.

= 1.7.3 =
Important upgrade to resolve issues with style incompatibilities.

= 1.7.2 =
Optional upgrade to add new features, related to variables.

= 1.7.1 =
Optional upgrade to add new features, related to uploadpath and error reporting.

= 1.7 =
Optional upgrade to add new features, related to appearance of the plugin and error reporting.

= 1.6.3 =
Important upgrade to correct bugs that prevented normal operation of the plugins in some cases.

= 1.6.2 =
Important upgrade to correct bugs that prevented normal operation of the plugins in some cases.

= 1.6.1 =
Important upgrade to correct bug that prevented normal operation of the plugins in some cases.

= 1.6 =
Optional upgrade to add new features, related to ajax functionality and minor bug fixes.

= 1.5 =
Optional upgrade to add new features, related to subfolders.

= 1.4.1 =
Important upgrade to correct a css problem with Firefox.

= 1.4 =
Important upgrade that introduces some bug fixes and some new capabilities.

= 1.3 =
Important upgrade that introduces some bug fixes and a lot of new capabilities.

= 1.2 =
Optional upgrade in order to set additional capabilities.

= 1.1 =
Optional upgrade in order to set additional capabilities.

= 1.0 =
Initial version.

== Attributes ==

The easiest way to use the plugin is to put the shortcode [inline_upload] in the page. In this case, the plugin will use the default functionality.

If you want to customize the plugin (define the upload path, use file filter, change title and button text) then you can use the following attributes:

* **uploadid:** This is the ID of every instance of the plugin inside the same page. Valid values are 1,2,3... Please use a different value for every instance.
* **uploadtitle:** The title of the plugin. Default value is "Upload a file".
* **selectbutton:** The title of the select button. Default value is "Select File".
* **uploadbutton:** The title of the upload button. Default value is "Upload File".
* **singlebutton:** If this attribute is set to "true", only Upload Button will be shown and file will be automatically uploaded when selected. Default value is "false".
* **uploadrole:** This is the roles that are allowed to upload files. Default role is "administrator". If you use other roles, like "editor", then only users of this role and also of role "administrator" will be able to upload files. You can set multiple roles, separated by comma, e.g. "editor, author". If you set uploadrole to "all" then all users, even guests, will be able to upload files.
* **uploadpath:** This is the path of the upload directory. The path must be relative to wp-content folder of your Wordpress website. For instance, if your upload directory is "wp-content/uploads/myuploaddir", then uploadpath must have the value "uploads/myuploaddir". The default value is "uploads", meaning that the files will be uploaded to wp-content/uploads dir. If you put the variable "%username%" inside the uploadpath string, then this variable will be replaced by the username of the user currently logged in. If you want to upload files outside wp-content folder, then put a double dot (..) at the beginning of your uploadpath value.
* **createpath:** If this attribute is set to "true", the upload directory, defined by uploadpath, will be created in case it does not exist. Default value is "false".
* **uploadpatterns:** This is the filter of the uploaded files. Default value is "*.*", meaning that all files can be uploaded. Use this attribute to restrict the types of files that can be uploaded. For instance, in order to upload only pdf files put "\*.pdf". You can use more that one filters, separated by comma, for instance "\*.pdf,\*.doc".
* **maxsize:** This is the maximum size in MBytes of the uploaded files. Use this attribute to restrict the upload of files larger that this value. Default value is "10", meaning that you cannot upload files larger than 10MBytes.
* **accessmethod:** This attributes defines the method to create directories and upload files. Default value is "normal". If it is set to "ftp", then the plugin will attempt to create directories and upload files using ftp access. In order to do this, the attribute *ftpinfo* must also be filled with valid ftp access information. Use this attribute when you cannot upload files, access uploaded files or cannot copy or delete uploaded files because of SAFE MODE restrictions, or because the owner of the file is the domain administrator.
* **ftpinfo:** This attribute defines the ftp access information. It has the syntax *username:password@domain*. If username, password or domain contains the characters (:) or (@), then replace them with (\\:) and (\\@) in order to avoid misreading of the attribute.
* **useftpdomain:** This attribute is used when the ftp domain used to upload files is in different domain than Wordpress installation. If it is set to "true" (and also uploadmethod is "ftp"), then the domain that will be used to upload files will be the one defined in ftpinfo attribute. Default value is "false".
* **dublicatespolicy:** This attribute defines what to do when the upload file has the same name with another file inside target directory. If it is set to "overwrite" then the upload file will replace the existing file. If it is set to "reject" then the upload operation will be cancelled. If it is set to "maintain both" then the upload file will be saved inside the target directory with another name, in order to keep both files. Default value is "overwrite".
* **uniquepattern:** This attribute defines how to save the upload file when a file with the same name already exists inside the target directory. If it is set to "index" then the upload file will be saved with a numeric suffix, like (1), (2) etc. in order to keep the name of the uploaded file unique. If it is set to "datetimestamp", then the suffix will be an encoded datetime of the upload operation. The plugin ensures that the name of the uploaded file will be unique, in order to avoid accidental replacement of existing files. Default value is "index".
* **filebaselink:** This attribute defines if this plugin will be linked to wp-filebase plugin. Wp-filebase is another plugin with which you can upload files and then show them in your pages in a customizable way. If you set this attribute to "true", then you can upload files inside wp-filebase directories using inline_upload and then update the databases of wp-filebase, so that it is informed about the new uploads. The default value is "false". Please note that this attribute does not check to see if wp-filebase is installed and active, so be sure to have wp-filebase active if you want to use it.
* **notify:** If this attribute is set to "true", then an email will be sent to the addresses defined by the attribute *notifyrecipients* to inform them that a new file has been uploaded.
* **notifyrecipients:** This attribute defines the list of email addresses to receive the notification message that a new file has been uploaded. More that one address can be defined, separated by comma (,). You can use variables inside this attribute, as explained below.
* **notifysubject:** This attribute defines the subject for the notification message. Default value is "File Upload Notification". You can use variables inside this attribute, as explained below.
* **notifymessage:** This attribute defines the body text for the notification message. Default value is "Dear Recipient, this is an automatic delivery message to notify you that a new file has been uploaded. Best Regards". You can use variables inside this attribute, as explained below.
* **attachfile:** This attribute defines if the uploaded file will be attached to the notification email. Default value is "false".
* **redirect:** This attribute defines if the user will be redirected to another web page when the file is uploaded successfully. Default value is "false".
* **redirectlink:** This attribute defines the url of the redirection page. Please use the prefix "http://" if the redirection page is in another domain, otherwise the server will assume that the url is relative to the server path.
* **adminmessages:** This attribute offers the option to administrator users to receive additional information about upload errors. These messages will be visible only to administrators. Default value is "false".
* **successmessage:** This attribute defines the message to be shown upon successfull upload. Default value is "File %filename% uploaded successfully". You can use the variables %filename% and %filepath% inside the message, as explained below.
* **successmessagecolors:** This attribute defines the colors of the message shown upon successfull upload. Default value is "#006600,#EEFFEE,#006666". The first value is the text color, the second the background color and the third the border color.
* **failmessagecolors:** This attribute defines the colors of the message shown upon failure to upload. Default value is "#660000,#FFEEEE,#666600". The first value is the text color, the second the background color and the third the border color.
* **showtargetfolder:** This attribute defines if a message with the upload directory will be shown. Default value is "false".
* **targetfolderlabel:** This attribute defines the text for the message for the upload directory. Default value is "Upload Directory".
* **askforsubfolders:** This attribute defines if the user can select a subfolder to upload the file. Default value is "false". If set to "true", then the user is able to select a subfolder of the path, defined by the attribute *uploadpath*, to upload a file through a drop down list. This attributed is used together with attribute *subfoldertree*, which defines the subfolders.
* **subfoldertree:** This attribute defines the structure of the subfolders that the user can select to upload a file. Default value is "". The format of this attribute is as follows: the subfolders are separated by commas (,), e.g. "subfolder1, subfolder2". It is possible to use nested subfolders (a folder inside another folder). To do this place stars (*) before the name of the subfolder. The number of stars determines nesting level, e.g. "subfolder1, *nested1, *nested2, **nested3". Please note that the first subfolder must be the name of the folder defined by attribute *uploadpath* (only the last part) without any stars, while all the next subfolders must have at least one star. The user has also the capability to use a different name (from the actual subfolder name) to be shown in the drop down list for every subfolder, by separating the actual and shown name using the slash (/) symbol, e.g. "subfolder1, *subfolder2/shownname2, *subfolder3/shownname3".
* **forceclassic:** This attribute defines if the plugin will use the old classic functionality to upload files (using forms) or ajax functionality (supported in HTML5). Default value is "false". Please note that if your browser does not support HTML ajax functionality, then the plugin will automatically switch to classic one.
* **testmode:** This attribute defines if the plugin will be shown in test mode. Default value is "false". If it is set to "true", then the plugin will obtain a "dummy" functionality (it will not be able to upload files) and it will appear showing all of its objects (the selection of subfolders, progress bar, a test message), while the buttons will show a "Test Mode" message when pressed. This option can be used to configure the dimensions of the individual objects of the plugin more easily.
* **widths:** This attribute can be used to define the width of every individual object of the plugin. Default value is "". To define the width of an individual object, simply put the name of the object and the width, separated by the (:) character (e.g. "title:100px"). To define more than one objects separate them with comma (,). The name of every individual object is shown below.
* **heights:** This attribute can be used to define the height of every individual object of the plugin. Default value is "". To define the height of an individual object, simply put the name of the object and the height, separated by the (:) character (e.g. "title:20px"). To define more than one objects separate them with comma (,). The name of every individual object is shown below.
* **placements:** This attribute can be used to change the placement of the objects of the plugin. Default value is "title/filename+selectbutton+uploadbutton/subfolders/progressbar/message". Every line is separated by a slash (/). To put more than one objects to the same line, separate them with a plus (+). The name of every object is shown below.

You can use any of these attributes to customize the plugin. The way to use these attributes is the following:

`
[inline_upload attribute1=value1 attribute2=value2]
`

Here are some examples:

`
[inline_upload uploadtitle="Upload files to the Upload dir"]
[inline_upload uploadtitle="Upload files to the Upload dir" uploadpath="uploads/myuploaddir"]
[inline_upload uploadid="1" uploadpath="../myuploaddir"]
[inline_upload uploadpath="uploads/users/%username%" createpath="true"]
[inline_upload uploadpath="uploads/myuploaddir" notify="true" notifyrecipients="name1@address1.com, name2@address2.com"]
[inline_upload uploadpath="/uploads/myuploaddir" askforsubfolders="true" subfoldertree="myuploaddir/My Upload Directory,*subfolder1/Subfolder1 Inside myuploaddir,**inner/2nd Level Nested Dir, *reports/Reports"]
[inline_upload uploadrole="all" uploadpath="/uploads/filebase/%username%" createpath="false" notify="true" notifyrecipients="myname@domain.com" notifysubject="A new file has been uploaded!" attachfile="true" askforsubfolders="true" subfoldertree="admin/Administrator,*root/Root Folder,**inner, *reports/Reports" filebaselink="true" widths="filename:150px, selectbutton:80px, uploadbutton:80px, progressbar:220px, message:368px, subfolders_label:100px, subfolders_select:125px" placements="title/filename+subfolders/selectbutton+uploadbutton+progressbar/message"]
`

== Variables ==

From version 1.2 variables are supported inside attributes.

A variable is a string surrounded by percent characters, in the form *%variable_name%*. This variable is replaced by another string whenever the plugin is executed.

For instance, if the variable %username% is used inside *uploadpath* attribute, then it will be replaced by the username of the user who is currently logged in every time a file is uploaded. By this way, every user can upload files to a separate folder, without any additional programming.

For the time being, the following attributes are supported:

* **%username%:** Is replaced by the username of the current user. Can be used inside attributes *uploadpath*, *notifysubject* and *notifymessage*.
* **%useremail%:** Is replaced by the email of the current user. Can be used inside attributes *notifyrecipients*, *notifysubject* and *notifymessage*.
* **%filename%:** Is replaced by the filename (not including path information) of the uploaded file. Can be used inside attributes *notifysubject*, *notifymessage*, *successmessage* and *redirectlink*.
* **%filepath%:** Is replaced by the filepath (full path and filename) of the uploaded file. Can be used inside attributes *notifysubject*, *notifymessage* and *successmessage*.
* **%blogid%:** Is replaced by the blog_id of the current site. Can be used inside attribute *uploadpath*.

== Requirements ==

The plugin requires to have Javascript enabled in your browser. For Internet Explorer you also need to have Active-X enabled. 
In order to have ajax HTML5 functionality (no page reload during file upload, progress bar), a browser supporting HTML5 must be used.
