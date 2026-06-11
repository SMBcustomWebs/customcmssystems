<?php require_once( 'ccs_dash/cms.php' ); ?>
<cms:template title="Blog"  clonable='1' icon='copywriting' order="1500"  >
    
    <cms:pagebuilder name='blg_cntnt_pb' label='Blog Content Page' skip_custom_fields='1' order='110'>
        
        <cms:section label='Thin Info Bar' name='thnbr_sect'  masterpage='blocks/thinbar.php' mosaic='thnbar_msc' />
        <cms:section label='Site Navigation' name='nav_sect'  masterpage='blocks/navbar.php' mosaic='site_nav_msc' />
        <cms:section label='Page Hero' name='hro_sect'  masterpage='blocks/hero.php' mosaic='ccs_hro_block_msc' />
        <cms:section label='Page Modules' name='trans_sect'  masterpage='blocks/transitions.php' mosaic='trns_msc' />
        <cms:section label='Blog Content' name='cntnt_pg_sect'  masterpage='blocks/content/content_st_1.php' mosaic='cntnt_pg_st_1_msc' />
        
        <cms:section label='Page Footer' name='ftr_sect'  masterpage='blocks/footer.php' mosaic='footer_msc' />

    </cms:pagebuilder>
    
</cms:template>
<cms:embed 'pg_frame/head.htm' />
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
            <cms:redirect k_page_link />
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
<cms:embed 'pg_frame/main-cap.htm' />   
<cms:embed 'pg_frame/nav_emb.htm' />   
<cms:ignore>
<!-- use this inside a tile/mosaic to check and capture var before pb changes them --> 
    <cms:abort><cms:dump_all /></cms:abort>   
</cms:ignore>
<cms:breadcrumbs separator='&nbsp;>>&nbsp;' include_template='1' />
<br>
<cms:embed 'single/blog.html' />
<cms:if k_is_home>
  k is home<br>
  <h3>PAGES dump FROM HOME</h3>
  <cms:pages masterpage=k_template_name>
                <cms:dump />
 </cms:pages>
 <h3>HOME dump</h3>
  <cms:dump />
<cms:else_if k_is_folder />
    <h1>Folder-view: <cms:show k_folder_title /> Dump</h1>
<cms:show_pagebuilder 'ccs_abt_cntn_pg_tp'  no_cache="<cms:if (k_user_access_level ge '7') && ("<cms:get_session 'inline_edit_on' />") >1</cms:if>">
    <cms:show k_content />
</cms:show_pagebuilder> <cms:dump />
<cms:else />
k is not home or folder... so a page? <cms:dump />
</cms:if>
<h2>TEMPLATES DUMP</h2>
<cms:templates order='desc'>
  <cms:if k_template_order gt '1000' && k_template_order lt '1999'>
    <cms:pages masterpage=k_template_name >
      <cms:if k_is_page>
        <cms:if k_page_foldername=''>
        <cms:show k_page_title />
        </cms:if>
      </cms:if>
 </cms:pages>
</cms:if>
</cms:templates>

    <cms:show_pagebuilder 'blg_cntnt_pb'  no_cache="<cms:if (k_user_access_level ge '7') && ("<cms:get_session 'inline_edit_on' />") >1</cms:if>">
        <cms:show k_content />
    </cms:show_pagebuilder>

<cms:if (k_user_access_level ge '7') && ("<cms:get_session 'inline_edit_on' />")  >     
    <div>
        <cms:popup_edit_ex 'cat_pb' width='1050' height='1200' link_text='<button class="btn btn-lg btn-danger me-2 mb-1 fs--1" type="button">Full Page Mosaic Edit</button>' /> 
    </div>         
</cms:if>
<cms:embed 'pg_frame/footer.htm' />
<cms:embed 'pg_frame/tail.htm' />
<?php COUCH::invoke(); ?>