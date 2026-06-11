<?php require_once( 'ccs_dash/cms.php' ); ?>
<cms:template title="Homepage" parent='_site_' icon='home' order="90" >
    
    <cms:pagebuilder name='home_pb' label='Homepage PageBuilder' skip_custom_fields='1' order='-1'>
        
        <cms:section label='Page Hero' name='hro_sect'  masterpage='blocks/frame/hero.php' mosaic='ccs_hro_msc' />
        <cms:section label='Banner Transition' name='trans_sect'  masterpage='blocks/banner/transitions.php' mosaic='trns_msc' />
        
        <cms:section label='Site Footer' name='ftr_sect'  masterpage='blocks/frame/footer.php' mosaic='footer_msc' />

    </cms:pagebuilder>
    
</cms:template>
<cms:embed 'pb_mods/pg_frame/head.htm' />
<cms:ignore>
<!-- use this inside a tile/mosaic to check and capture var before pb changes them --> 
    <cms:abort><cms:dump_all /></cms:abort>   
</cms:ignore>
<cms:set my_redirect_link = k_page_link />

<cms:if k_user_access_level ge '7' && "<cms:not "<cms:get_session 'inline_edit_on' />" />" >
    <cms:no_edit />
</cms:if>
<cms:if k_user_access_level ge '7' >   
    <cms:form method='post' anchor='0' class='m-0 p-0' >
        <cms:if k_success>
            <cms:if "<cms:get_session 'inline_edit_on' />" >
                <cms:delete_session 'inline_edit_on' />
            <cms:else />
                <cms:set_session 'inline_edit_on' value='1' />
            </cms:if>
            <cms:redirect url="<cms:link k_template_link />" />
        </cms:if>
        <cms:if "<cms:get_session 'inline_edit_on' />" >
            <div>
                <button class="btn btn-sm">
                    <cms:input class="bg-warning"  name='submit' type="submit" value="Turn Edit Off" />
                </button>
            </div>
        <cms:else />
            <div>
                <button class="btn btn-sm">
                    <cms:input class="bg-danger" name='submit' type="submit" value="Turn Edit On" />
                </button>
            </div>
        </cms:if>
    </cms:form>
</cms:if>
<cms:embed 'pb_mods/pg_frame/main-cap.htm' />   
<cms:embed 'pb_mods/pg_frame/nav/nav_emb.htm' />

<cms:show_pagebuilder 'home_pb'  no_cache="<cms:if (k_user_access_level ge '7') && ("<cms:get_session 'inline_edit_on' />") >1</cms:if>">
	<cms:show k_content />
</cms:show_pagebuilder>

<cms:embed 'pb_mods/pg_frame/footer/ftr_emb.htm' />
<cms:embed 'pb_mods/pg_frame/tail.htm' />
<cms:if (k_user_access_level ge '7') && ("<cms:get_session 'inline_edit_on' />")  >     
    <div>
        <cms:popup_edit_ex 'home_pb' width='1050' height='1200' link_text='<button class="btn btn-lg btn-danger me-2 mb-1 fs--1" type="button">Full Page Mosaic Edit</button>' /> 
    </div>         
</cms:if>
<?php COUCH::invoke(); ?>