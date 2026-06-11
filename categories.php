<?php require_once( 'ccs_dash/cms.php' ); ?>
<cms:template title="Category Dictionary" icon='tags' clonable='1' order="960" >
    
    <cms:editable type='message' name='tpl_msg' order='1' >
        <h2>Category Dictionary</h2>
        <h4>Add directory categories here. The Title will display in the Directory Search dropdowns.</h4>
        <p><strong>Note for CSV Imports:</strong> Ensure your CSV header is mapped exactly as: <code>Category_Name</code>.</p>
        <hr><br>
    </cms:editable>

</cms:template>
<cms:ignore>
<!--
	How to use this when the time comes:
	Ensure the client's file is named exactly locations.csv.

	Use ftp or something to Upload locations.csv into the root folder of your website (the same folder where locations.php lives).

	Open locations.php in your code editor and delete the cms:ignore  tags.

	Visit yoursite.com/locations.php in your browser. You will literally watch the screen print out the successful creation of every single city in real-time.

	Put the cms:ignore tags back to lock the script down again.
-->
</cms:ignore>
<cms:ignore><!--
    <cms:if k_user_access_level ge '10'>
        
        <h3>Running CSV Import...</h3>
        <hr>
        
        <cms:csv_reader file="<cms:show k_site_path />categories.csv" use_columns='1'>
            
            <cms:db_persist
                _masterpage='categories.php'
                _mode='insert'
                _invalidate_cache='0'
                k_page_title = Category_Name
            />
            
            <cms:if k_error>
                <p style="color:red;">Error importing <b><cms:show Category_Name /></b>: <cms:show k_error /></p>
            <cms:else />
                <p style="color:green;">Successfully imported: <b><cms:show Category_Name /></b></p>
            </cms:if>
            
        </cms:csv_reader>
        
        <hr>
        <h3>Import Complete! Please re-add the &lt;cms:ignore&gt; tags to the code.</h3>

    </cms:if>
--></cms:ignore>

<?php COUCH::invoke(); ?>