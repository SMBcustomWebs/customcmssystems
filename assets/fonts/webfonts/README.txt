SELF-HOSTED WEB FONTS
=====================

Nothing on this site fetches fonts from Google any more. Every family it uses
is served from this folder, which is why the folder exists and why it needs
filling in.

WHY IT WAS DONE THIS WAY
------------------------
Loading a font from fonts.googleapis.com sends the visitor's IP address to a
third party before they have agreed to anything. A German court ruled against
exactly that in 2022. The alternative - hiding fonts behind the cookie banner -
would technically comply while making the site render in fallback fonts until
someone clicked a button. Serving the files ourselves removes the question
instead of answering it, and is faster besides.


LAYOUT
------
One folder per family. The folder and the CSS file inside it are both named
after the family: lowercase, spaces replaced with hyphens.

    assets/fonts/webfonts/
        montserrat/
            montserrat.css          <- the @font-face rules
            montserrat-400.woff2    <- whatever the CSS references
            montserrat-700.woff2
        playfair-display/
            playfair-display.css
            playfair-display-400.woff2

"Playfair Display" becomes "playfair-display". "Open Sans" becomes "open-sans".
Get that wrong and the family is simply not detected - it will not error, it
will just never appear.


ADDING A FAMILY
---------------
1. Download it from any self-hosting source. Two that work well:

       google-webfonts-helper   gwfh.mranftl.com
       Fontsource               fontsource.org

   Both hand you a CSS file plus woff2 files. woff2 alone is enough - every
   browser released in the last decade supports it.

2. Create the folder here, named as above.

3. Put the woff2 files in it, and the CSS file named to match the folder.

4. Open the CSS and check the url() paths point at the files beside it -
   usually "./name.woff2" or just "name.woff2". Tools sometimes emit paths
   assuming a different folder depth. This is the one step people get wrong.

5. Add the family name to the $hosted array in whichever of the five
   dropdowns it belongs to - snippets/dropdowns/font-sans.htm, font-serif.htm,
   font-mono.htm, font-script.htm or font-decor.htm. Spell it as the family,
   not as the folder: 'Playfair Display', not 'playfair-display'.

   THIS STEP IS REQUIRED AND THIS FILE USED TO SAY IT WASN'T. The array is an
   allowlist, not just a lookup. Nothing on disk records whether a family is a
   serif or a script, so a plain scan of this folder could not sort families
   into the five roles - each dropdown has to be told which ones are its own.
   A family missing from every $hosted array is invisible in the panel no
   matter how correctly its folder and css are built, and nothing reports it.

The family now appears in the font dropdowns in Global Site Settings, and is
linked automatically when selected.


REMOVING A FAMILY
-----------------
Delete the folder. It disappears from the dropdowns. If it was already
selected somewhere, that selection falls back to the system font stack defined
in assets/css/user.php - the page stays readable.


WHAT TO INSTALL FIRST
---------------------
The four theme presets name families directly and do not go through the
dropdowns, so these eleven cover every built-in theme:

    Lato              Montserrat         Roboto
    Playfair Display  Cinzel             Lora
    Dancing Script    Open Sans          Raleway
    Oswald            Merriweather

The default theme, used when no preset is chosen, needs only three of them:
Montserrat, Raleway and Playfair Display.

These need no download and are always available, because every operating
system already has them:

    Arial   Helvetica   Georgia   Times New Roman
    Consolas   Courier New   Brush Script MT   Impact


HOW THIS IS WIRED
-----------------
    snippets/utils/google_fonts.htm    links the CSS for each selected family,
                                       skipping any that is not present here
    snippets/dropdowns/font-*.htm      offer the families named in their
                                       $hosted array THAT ALSO EXIST HERE

google_fonts.htm never needs editing. The dropdowns need one line added to
$hosted per new family, per step 5 above - removing a family needs no edit,
since the file_exists check drops it on its own.
