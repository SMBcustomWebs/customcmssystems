<?php require_once( 'ccs_dash/cms.php' ); ?>
    <cms:template title="Contact" icon='phone' order="1900" >
    <cms:pagebuilder name='ccs_cntc_hro_pb' label='<h3>Page Topper</h3>' skip_custom_fields='1' order='10'>
        <cms:section label='Page Hero' name='ccs_abt_cat_hro_sect'  masterpage='blocks/frame/hero.php' mosaic='ccs_hro_block_msc' />
    </cms:pagebuilder>

    </cms:template>
<cms:embed 'pg_frame/head.htm' />

<cms:set ccs_svc_pg="<cms:gpc 'page' />" "global" />
<cms:set ccs_svc_pg_id="<cms:gpc 'post' />" "global" />
<cms:if ccs_svc_pg='content/abouts.php'>
    <cms:set ccs_svc_redirect_link = "<cms:add_querystring "<cms:link masterpage=k_template_name />" "page=<cms:show ccs_svc_pg />&post=<cms:show ccs_svc_pg_id />" />" />
<cms:else />
    <cms:set ccs_svc_redirect_link="<cms:show k_template_link />" />
</cms:if>

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
            <cms:redirect ccs_svc_redirect_link />
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
<cms:breadcrumbs separator='&nbsp;>>&nbsp;' include_template='1' />
<br>
<cms:show_pagebuilder 'ccs_cntc_hro_pb'  no_cache="<cms:if (k_user_access_level ge '7') && ("<cms:get_session 'inline_edit_on' />") >1</cms:if>">
    <cms:show k_content />
</cms:show_pagebuilder> 

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

<h2>Layout 1</h2>
 <!-- ============================================-->
          <!-- <section> begin ============================-->
          <section class="p-0">

            <div class="bg-holder overlay" style="background-image:url(<cms:show k_site_link />assets/images/contact-03.jpg);">
            </div>
            <!--/.bg-holder-->

            <div class="container">
              <div class="row min-vh-50 align-items-center py-5">
                <div class="col-md-8 col-lg-5">
                  <h1 class="text-white fs-6 fs-md-5 mb-3">Contact</h1>
                  <p class="fs-8 fw-light text-white">Relax, we've got your back. Feel free to contact us for any support.</p>
                </div>
              </div>
            </div>
            <!-- end of .container-->

          </section>
          <!-- <section> close ============================-->
          <!-- ============================================-->




          <!-- ============================================-->
          <!-- <section> begin ============================-->
          <section>

            <div class="container">
              <div class="row">
                <div class="col-lg-5">
                  <h3 class="text-uppercase fw-light">Contact</h3>
                  <hr class="text-1200 short mb-5" />
                  <div class="row">
                    <div class="col-auto pe-0" style="min-width: 16px;"><span class="fas fa-map-marker-alt" style="font-size: 13px;"> </span></div>
                    <div class="col">
                      <p class="fw-light fs-9 text-700 mb-2"><strong class="text-1100 fw-normal">Posh International Hotel Las Vegas</strong><br />2000 Fashion Show Drive<br />Las Vegas, Nevada 56846</p>
                      <p class="mb-3"><strong class="text-1100 fw-normal">Phone: &nbsp;&nbsp;&nbsp;&nbsp; </strong>458.564.4545<br /><strong class="text-1100 fw-normal">Reserve: &nbsp; </strong>677.454.5757</p>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-auto pe-0" style="min-width: 16px;"> <span class="fas fa-envelope" style="font-size:13px;"></span></div>
                    <div class="col"> <a class="fs-9" href="mailto:info@posh.com"> info@posh.com</a></div>
                  </div>
                </div>
                <div class="col-lg-7">
                  <form class="posh-form" method="POST" onsubmit="return false;">
                    <div class="mb-3">
                      <input class="form-control" type="hidden" name="to" value="username@domain.extension" />
                    </div>
                    <div class="mb-3">
                      <input class="form-control" type="text" name="name" placeholder="Name" required="required" />
                    </div>
                    <div class="mb-3">
                      <input class="form-control" type="email" name="from" placeholder="Email" required="required" />
                    </div>
                    <div class="mb-3">
                      <textarea class="form-control" rows="8" name="message" placeholder="Message"></textarea>
                    </div>
                    <input class="btn btn-primary" type="submit" name="submit" value="Send!" />
                    <div class="form-feedback mt-3"></div>
                  </form>
                </div>
                <div class="col mt-5">
                  <div class="googlemap" data-gmap="data-gmap" data-latlng="48.8583701,2.2922926" data-scrollwheel="false" data-icon="<cms:show k_site_link />assets/images/hotel-icon.png" data-zoom="14" data-theme="Default" style="min-height: 400px">
                    <div class="marker-content py-3">
                      <h5>Eiffel Tower</h5>
                      <p>Gustave Eiffel's iconic,<br />wrought-iron 1889 tower.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- end of .container-->

          </section>
          <!-- <section> close ============================-->
          <!-- ============================================-->

<hr><hr><hr>
<h2>Layout 2</h2>
           <!-- ============================================-->
          <!-- <section> begin ============================-->
          <section class="p-0">

            <div class="bg-holder overlay overlay-1" style="background-image:url(<cms:show k_site_link />assets/images/contact-04.jpg);">
            </div>
            <!--/.bg-holder-->

            <div class="container">
              <div class="row min-vh-50 align-items-center py-5 text-center justify-content-center">
                <div class="col-md-8 col-lg-6">
                  <h1 class="text-white fs-6 fs-md-5 mb-3">Contact</h1>
                  <p class="fs-8 fw-light text-white">Feel free to drop us a line.</p>
                </div>
              </div>
            </div>
            <!-- end of .container-->

          </section>
          <!-- <section> close ============================-->
          <!-- ============================================-->




          <!-- ============================================-->
          <!-- <section> begin ============================-->
          <section>

            <div class="container">
              <div class="row justify-content-center">
                <div class="col-lg-10">
                  <form data-form="data-form" onsubmit="return false;">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <input class="form-control" type="hidden" name="to" value="username@domain.extension" />
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="ls-0" for="exampleInputName1">First Name </label>
                          <input class="form-control" type="text" name="firstName" id="exampleInputName1" required="required" />
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="ls-0" for="exampleInputName2"> Last Name</label>
                          <input class="form-control" type="text" name="lastName" id="exampleInputName2" required="required" />
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="ls-0" for="exampleInputCompany">Company</label>
                          <input class="form-control" type="text" name="company" id="exampleInputCompany" required="required" />
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="ls-0" for="exampleInputEmail1">Email</label>
                          <input class="form-control" type="email" name="from" id="exampleInputEmail1" required="required" />
                        </div>
                      </div>
                      <div class="col">
                        <div class="form-group">
                          <label class="ls-0" for="exampleInputMessage">Message</label>
                          <textarea class="form-control" rows="8" name="message" id="exampleInputMessage"></textarea>
                        </div>
                      </div>
                      <div class="col-12 text-center">
                        <input class="btn btn-primary" type="submit" name="submit" value="Send!" />
                      </div>
                      <div class="feedback mt-3"></div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <!-- end of .container-->

          </section>
          <!-- <section> close ============================-->
          <!-- ============================================-->


          <section class="py-0">
            <div class="container-fluid px-0">
              <div class="row g-0 text-center">
                <div class="col-lg-4">
                  <div class="googlemap h-100" data-gmap="data-gmap" data-latlng="51.5228995,-0.1571674" data-scrollwheel="false" data-icon="<cms:show k_site_link />assets/images/hotel-icon.png" data-zoom="14" data-theme="Default" style="min-height: 400px">
                    <div class="marker-content py-3">
                      <h5>Madame Tussauds</h5>
                      <p>Museum chain for life-size wax replicas<br />of famous celebrities &amp; historic icons.</p>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 position-relative">
                  <div class="bg-holder overlay overlay-0" style="background-image:url(<cms:show k_site_link />assets/images/contact-01.jpg);">
                  </div>
                  <!--/.bg-holder-->

                  <div class="row g-0 align-items-center w-100 py-8">
                    <div class="col text-200">
                      <h4 class="text-200">Bristol HQ </h4>
                      <hr class="mb-4 short mx-auto" />
                      <p class="mb-4">35 King Street <br />Bristol, BS1 4DZ<br /><a class="link-200" href="tel:+4401179226892">Tel: +44 (0)117 922 6892</a></p><a class="btn btn-outline-white rounded-pill btn-sm" href="#!">Contact</a>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 position-relative">
                  <div class="bg-holder overlay overlay-0" style="background-image:url(<cms:show k_site_link />assets/images/contact-02.jpg);">
                  </div>
                  <!--/.bg-holder-->

                  <div class="row g-0 align-items-center w-100 py-8">
                    <div class="col text-200">
                      <h4 class="text-200">London </h4>
                      <hr class="mb-4 short mx-auto" />
                      <p class="mb-4">3rd Floor, 86-90 Paul Street<br />London, EC2A 4NE<br /><a class="link-200" href="tel:+4402038564454">Tel: +44 (0)203 856 4454</a></p><a class="btn btn-outline-white rounded-pill btn-sm" href="#!">Contact</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section> 

<hr><hr><hr>
<h2>Layout 3</h2>
          <!-- ============================================-->
          <!-- <section> begin ============================-->
          <section class="p-0">

            <div class="bg-holder overlay overlay-1" style="background-image:url(<cms:show k_site_link />assets/images/about-company.jpg);">
            </div>
            <!--/.bg-holder-->

            <div class="container">
              <div class="row min-vh-50 align-items-center py-5">
                <div class="col-md-8 col-lg-5">
                  <h1 class="text-white fs-6 fs-md-5 mb-3">Let's Talk</h1><a class="fs-8 text-white fw-light" href="tel:+1567889953"> +1 (567) 889953</a>
                  <p class="fs-8 fw-light text-white">info@themewagon.com</p>
                </div>
              </div>
            </div>
            <!-- end of .container-->

          </section>
          <!-- <section> close ============================-->
          <!-- ============================================-->




          <!-- ============================================-->
          <!-- <section> begin ============================-->
          <section>

            <div class="container">
              <div class="row">
                <div class="col-lg-6 fs-10">
                  <form data-form="data-form" onsubmit="return false;">
                    <input class="form-control" type="hidden" name="to" value="username@domain.extension" />
                    <div class="form-group row">
                      <label class="col-2 text-end col-form-label" for="name">Name :</label>
                      <div class="col-10">
                        <input class="form-control" type="text" name="name" id="name" required="required" />
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-2 text-end col-form-label" for="subject"> Subject :</label>
                      <div class="col-10">
                        <input class="form-control" type="text" name="subject" id="subject" required="required" />
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-2 text-end col-form-label" for="email"> Email :</label>
                      <div class="col-10">
                        <input class="form-control" type="email" name="from" id="email" required="required" />
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-2 text-end col-form-label" for="message"> Message :</label>
                      <div class="col-10">
                        <textarea class="form-control" rows="8" name="message" id="message"></textarea>
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-10 offset-2">
                        <input class="btn btn-primary" type="submit" name="submit" value="Send!" />
                      </div>
                    </div>
                    <div class="feedback mt-3"></div>
                  </form>
                </div>
                <div class="col">
                  <div class="googlemap" data-gmap="data-gmap" data-latlng="37.4408194,-122.0204993" data-scrollwheel="false" data-icon="<cms:show k_site_link />assets/images/hotel-icon.png" data-zoom="14" data-theme="Default" style="min-height: 355px">
                    <div class="marker-content py-3">
                      <h5>Alviso</h5>
                      <p>Alviso is a neighborhood in San Jose,<br />Santa Clara County, California.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- end of .container-->

          </section>
          <!-- <section> close ============================-->
          <!-- ============================================-->
<cms:embed 'pg_frame/footer.htm' />
<cms:embed 'pg_frame/tail.htm' />
<?php COUCH::invoke(); ?>