
              ___  __ _____  ______              _           __
             / _ \/ // / _ \/_  __/__ ______ _  (_)__  ___ _/ /
            / ___/ _  / ___/ / / / -_) __/  ' \/ / _ \/ _ `/ / 
           /_/  /_//_/_/    /_/  \__/_/ /_/_/_/_/_//_/\_,_/_/  
                                                           v1.0
                            PHPTerminal Manual



Table of Contents

   <a href="#section-0">0</a>. Installation ................................................... <a href="#section-0">0</a>
   <a href="#section-1">1</a>. phpterm - print PHPTerminal information ........................ <a href="#section-1">1</a>
   <a href="#section-2">2</a>. man - an interface to the reference manuals .................... <a href="#section-2">2</a>
   <a href="#section-3">3</a>. clear - clear the screen ....................................... <a href="#section-3">3</a>
   <a href="#section-4">4</a>. pwd - print name of current/working directory .................. <a href="#section-4">4</a>
   <a href="#section-5">5</a>. cd - change the working directory .............................. <a href="#section-5">5</a>
   <a href="#section-6">6</a>. ls - list directory contents ................................... <a href="#section-6">6</a>
   <a href="#section-7">7</a>. cat - concatenate files and print on the standard output ....... <a href="#section-7">7</a>
   <a href="#section-8">8</a>. touch - change file timestamps ................................. <a href="#section-8">8</a>
   <a href="#section-9">9</a>. edit - internal file editor .................................... <a href="#section-9">9</a>
   <a href="#section-10">10</a>. rename - rename file ......................................... <a href="#section-10">10</a>
   <a href="#section-11">11</a>. cp - copy files and directories .............................. <a href="#section-11">11</a>
   <a href="#section-12">12</a>. mv - move files and directories .............................. <a href="#section-12">12</a>
   <a href="#section-13">13</a>. rm - remove files or directories ............................. <a href="#section-13">13</a>
   <a href="#section-14">14</a>. mkdir - make directories ..................................... <a href="#section-14">14</a>
   <a href="#section-15">15</a>. rmdir - remove empty directories ............................. <a href="#section-15">15</a>
   <a href="#section-16">16</a>. chmod - change file mode bits ................................ <a href="#section-16">16</a>
   <a href="#section-17">17</a>. upload - uploads file in current directory ................... <a href="#section-17">17</a>
   <a href="#section-18">18</a>. download - downloads file .................................... <a href="#section-18">18</a>
   <a href="#section-19">19</a>. sql - executes sql query ..................................... <a href="#section-19">19</a>
   <a href="#section-20">20</a>. zip - package and compress (archive) files ................... <a href="#section-20">20</a>
   <a href="#section-21">21</a>. unzip - extract compressed files in a ZIP archive ............ <a href="#section-21">21</a>
   <a href="#section-22">22</a>. ini_get - get php ini option ................................. <a href="#section-22">22</a>
   <a href="#section-23">23</a>. get_loaded_extensions - returns loaded php modules ........... <a href="#section-23">23</a>
   <a href="#section-24">24</a>. phpversion - returns the current php version ................. <a href="#section-24">24</a>



<hr />
<a name="page-0" id="page-0" href="#page-0" class="invisible"> </a>
<strong><a name="section-0" href="#section-0">0</a>. INSTALLATION</strong></span>

   Extract zip archive with PHPTerminal. Before you upload it to your server you have to edit phpterminal.php
   and configure required fields:

          define('PHPTERM_USERNAME', 'admin'); // Username required to access terminal
          define('PHPTERM_PASSWORD', 'admin'); // Password required 
          define('PHPTERM_SQL_HOSTNAME', 'localhost'); // Hostname/IP address to your MySQL database
          define('PHPTERM_SQL_DATABASE', 'database'); // MySQL database name
          define('PHPTERM_SQL_USERNAME', 'username'); // MySQL username
          define('PHPTERM_SQL_PASSWORD', 'password'); // MySQL password

   Once you finish you will end up with something like this:

          define('PHPTERM_USERNAME', 'myusername');
          define('PHPTERM_PASSWORD', 'mysafepassword');
          define('PHPTERM_SQL_HOSTNAME', '127.0.0.1');
          define('PHPTERM_SQL_DATABASE', 'mydatabase');
          define('PHPTERM_SQL_USERNAME', 'myuser');
          define('PHPTERM_SQL_PASSWORD', 'mypassword');

   Save the changes and upload phpterminal.php and phpterminal directory to your server.

          Note: phpterminal.php file and phpterminal directory needs to be in the same place!

   Now you can access your terminal in two ways. You can either access it directly by entering url to
   phpterminal.php on your server (e.g. http://mywebsite.com/phpterminal.php) or by embedding it in your
   application by creating an iframe with src attribute set to url pointing to your phpterminal.php.

          &lt;iframe src=&quot;http://mywebsite.com/phpterminal.php&quot;&gt;&lt;/iframe&gt;

   Now you're set to go. Have fun using PHPTerminal:)


<hr />
<a name="page-1" id="page-1" href="#page-1" class="invisible"> </a>
<strong><a name="section-1" href="#section-1">1</a>. PHPTERM</strong></span>

   NAME
          phpterm - print PHPTerminal information

   SYNOPSIS
          phpterm [OPTION]...

   DESCRIPTION
          Print PHPTerminal information.

          -v
                 output version information only


<hr />
<a name="page-2" id="page-2" href="#page-2" class="invisible"> </a>
<strong><a name="section-2" href="#section-2">2</a>. MAN</strong></span>

   NAME
          man - an interface to the reference manuals

   SYNOPSIS
          man COMMAND

   DESCRIPTION
          man is the system for refrence manuals and help information regarding
          available commands.



<hr />
<a name="page-3" id="page-3" href="#page-3" class="invisible"> </a>
<strong><a name="section-3" href="#section-3">3</a>. CLEAR</strong></span>

   NAME
          clear - clear the screen

   SYNOPSIS
          clear

   DESCRIPTION
          clear clears your screen if this is possible.



<hr />
<a name="page-4" id="page-4" href="#page-4" class="invisible"> </a>
<strong><a name="section-4" href="#section-4">4</a>. PWD</strong></span>

   NAME
          pwd - print name of current/working directory

   SYNOPSIS
          pwd

   DESCRIPTION
          Print the full filename of the current working directory.



<hr />
<a name="page-5" id="page-5" href="#page-5" class="invisible"> </a>
<strong><a name="section-5" href="#section-5">5</a>. CD</strong></span>

   NAME
          cd — change the working directory

   SYNOPSIS
          cd DIRECTORY

   DESCRIPTION
          Change the current directory to DIRECTORY



<hr />
<a name="page-6" id="page-6" href="#page-6" class="invisible"> </a>
<strong><a name="section-6" href="#section-6">6</a>. LS</strong></span>

   NAME
          ls - list directory contents

   SYNOPSIS
          ls [OPTION]... FILE...

   DESCRIPTION
          List information about the FILEs (the current directory by default).
          Sort entries alphabetically.

          -a
                 do not ignore entries starting with .

          -r
                 reverse order while sorting

          -R
                 list subdirectories recursively



<hr />
<a name="page-7" id="page-7" href="#page-7" class="invisible"> </a>
<strong><a name="section-7" href="#section-7">7</a>. CAT</strong></span>

   NAME
          cat - concatenate files and print on the standard output

   SYNOPSIS
          cat [OPTION]... FILE...

   DESCRIPTION
          Concatenate FILE(s) to standard output.

          -E
                 display $ at end of each line

          -n
                 number all output lines



<hr />
<a name="page-8" id="page-8" href="#page-8" class="invisible"> </a>
<strong><a name="section-8" href="#section-8">8</a>. TOUCH</strong></span>

   NAME
          touch - change file timestamps

   SYNOPSIS
          touch [OPTION]... FILE...

   DESCRIPTION
          Update the access and modification times of each FILE to the current time.

          A FILE argument that does not exist is created empty, unless -c is supplied.

          -c
                 do not create any files



<hr />
<a name="page-9" id="page-9" href="#page-9" class="invisible"> </a>
<strong><a name="section-9" href="#section-9">9</a>. EDIT</strong></span>

   NAME
          edit - internal file editor

   SYNOPSIS
          edit FILE

   DESCRIPTION
          Edit FILE in internal file editor. CTRL+2 saves and quits the editor. CTRL+0 will quit
          without saving changes.



<hr />
<a name="page-10" id="page-10" href="#page-10" class="invisible"> </a>
<strong><a name="section-10" href="#section-10">10</a>. RENAME</strong></span>

   NAME
          rename - rename file

   SYNOPSIS
          rename [OPTION]... FILE FILENAME

   DESCRIPTION
          rename will rename the specified FILE with FILENAME. Note that FILENAME is NOT a destination
          path, just a file name relative to FILE.

   OPTIONS
          -v
                 Show which files were renamed, if any.

          -o
                 Do not overwrite existing files.



<hr />
<a name="page-11" id="page-11" href="#page-11" class="invisible"> </a>
<strong><a name="section-11" href="#section-11">11</a>. CP</strong></span>

   NAME
          cp - copy files and directories

   SYNOPSIS
          cp [OPTION]... SOURCE DEST
          cp [OPTION]... SOURCE... DIRECTORY
          cp [OPTION]... DIRECTORY SOURCE...

   DESCRIPTION
          Copy SOURCE to DEST, or multiple SOURCE(s) to DIRECTORY.

          -r, -R
                 copy directories recursively

          -v
                 explain what is being done



<hr />
<a name="page-12" id="page-12" href="#page-12" class="invisible"> </a>
<strong><a name="section-12" href="#section-12">12</a>. MV</strong></span>

   NAME
          mv - move files and directories

   SYNOPSIS
          mv [OPTION]... SOURCE DEST
          mv [OPTION]... SOURCE... DIRECTORY
          mv [OPTION]... DIRECTORY SOURCE...

   DESCRIPTION
          Move SOURCE to DEST, or multiple SOURCE(s) to DIRECTORY.

          -r, -R
                 copy directories recursively

          -v
                 explain what is being done



<hr />
<a name="page-13" id="page-13" href="#page-13" class="invisible"> </a>
<strong><a name="section-13" href="#section-13">13</a>. RM</strong></span>

   NAME
          rm - remove files or directories

   SYNOPSIS
          rm [OPTION]... FILE...

   DESCRIPTION
          rm removes each specified file.  By default, it does not remove directories.

   OPTIONS
          Remove (unlink) the FILE(s).

          -r, -R
                 remove directories and their contents recursively

          -d
                 remove empty directories

          -v
                 explain what is being done



<hr />
<a name="page-14" id="page-14" href="#page-14" class="invisible"> </a>
<strong><a name="section-14" href="#section-14">14</a>. MKDIR</strong></span>

   NAME
          mkdir - make directories

   SYNOPSIS
          mkdir [OPTION]... DIRECTORY...

   DESCRIPTION
          Create the DIRECTORY(ies), if they do not already exist.

          -p
                 no error if existing, make parent directories as needed

          -v
                 print a message for each created directory



<hr />
<a name="page-15" id="page-15" href="#page-15" class="invisible"> </a>
<strong><a name="section-15" href="#section-15">15</a>. RMDIR</strong></span>

   NAME
          rmdir - remove empty directories

   SYNOPSIS
          rmdir [OPTION]... DIRECTORY...

   DESCRIPTION
          Remove the DIRECTORY(ies), if they are empty.

          -r, -R
                 remove directories and their contents recursively

          -v
                 output a diagnostic for every directory processed



<hr />
<a name="page-16" id="page-16" href="#page-16" class="invisible"> </a>
<strong><a name="section-16" href="#section-16">16</a>. CHMOD</strong></span>

   NAME
          chmod - change file mode bits

   SYNOPSIS
          chmod [OPTION]... OCTAL-MODE FILE...

   OPTIONS
          Change the mode of each FILE to OCTAL-MODE.

          -v
                 output a diagnostic for every file processed

          -R
                 change files and directories recursively



<hr />
<a name="page-17" id="page-17" href="#page-17" class="invisible"> </a>
<strong><a name="section-17" href="#section-17">17</a>. UPLOAD</strong></span>

   NAME
          upload - uploads file in current directory

   SYNOPSIS
          upload

   DESCRIPTION
          Upload any file that meets upload limits criteria to current working directory.



<hr />
<a name="page-18" id="page-18" href="#page-18" class="invisible"> </a>
<strong><a name="section-18" href="#section-18">18</a>. DOWNLOAD</strong></span>

   NAME
          download - downloads file

   SYNOPSIS
          download FILE

   DESCRIPTION
          Download FILE using web browser. If directory is specified, process is aborted.



<hr />
<a name="page-19" id="page-19" href="#page-19" class="invisible"> </a>
<strong><a name="section-19" href="#section-19">19</a>. SQL</strong></span>

   NAME
          sql - executes sql query

   SYNOPSIS
          sql "QUERY"

   OPTIONS
          Execute sql QUERY string. QUERY must be wrapped in double-quotes. If executed query is select then
          results are returned, else the number of rows affected.



<hr />
<a name="page-20" id="page-20" href="#page-20" class="invisible"> </a>
<strong><a name="section-20" href="#section-20">20</a>. ZIP</strong></span>

   NAME
          zip - package and compress (archive) files

   SYNOPSIS
          zip [OPTION]... ZIPFILE FILE...

   OPTIONS
          -r
                 Travel the directory structure recursively; for example:

                        zip -r foo.zip foo

                 In this case, all the files and directories in foo are saved in a zip archive named foo.zip,
                 including files with names starting with ".", since the recursion does not use the shell's
                 file-name substitution mechanism.  If you wish to include only a specific subset of the files
                 in directory foo and its subdirectories,  use  the  -i  option  to specify the pattern of
                 files to be included. You should not use -r with the name ".*", since that matches ".." which
                 will attempt to zip up the parent directory (probably not what was intended).

                 Multiple source directories are allowed as in

                        zip -r foo.zip foo1 foo2

                 which first zips up foo1 and then foo2, going down each directory.



<hr />
<a name="page-21" id="page-21" href="#page-21" class="invisible"> </a>
<strong><a name="section-21" href="#section-21">21</a>. UNZIP</strong></span>

   NAME
          unzip - extract compressed files in a ZIP archive

   SYNOPSIS
          unzip ZIPFILE

   DESCRIPTION
          unzip will extract files from a ZIP archive.  The default behavior (with no options) is to extract into
          the current directory (and subdirectories below it) all files from the specified ZIP archive.



<hr />
<a name="page-22" id="page-22" href="#page-22" class="invisible"> </a>
<strong><a name="section-22" href="#section-22">22</a>. INI_GET</strong></span>

   NAME
          ini_get - get php ini option

   SYNOPSIS
          ini_get OPTION

   DESCRIPTION
          Gets the value of a PHP configuration OPTION.



<hr />
<a name="page-23" id="page-23" href="#page-23" class="invisible"> </a>
<strong><a name="section-23" href="#section-23">23</a>. GET_LOADED_EXTENSIONS</strong></span>

   NAME
          get_loaded_extensions - returns loaded php modules

   SYNOPSIS
          get_loaded_extensions

   DESCRIPTION
          Returns the names of all the modules compiled and loaded in the PHP interpreter.



<hr />
<a name="page-24" id="page-24" href="#page-24" class="invisible"> </a>
<strong><a name="section-24" href="#section-24">24</a>. PHPVERSION</strong></span>

   NAME
          phpversion - returns the current php version

   SYNOPSIS
          phpversion

   DESCRIPTION
           Returns a string containing the version of the currently running PHP parser.
