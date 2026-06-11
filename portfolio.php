<?php require_once( 'ccs_dash/cms.php' ); ?>
    <cms:template title="Portfolio"  clonable='1' icon='book' order="1600" >

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
<cms:breadcrumbs separator='&nbsp;>>&nbsp;' include_template='1' />
<br>
<cms:embed 'single/portfolio.html' />
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

<h2>Portfolio List Layout 1</h2>
           <!-- ============================================-->
          <!-- <section> begin ============================-->
          <section class="text-center">

            <div class="container">
              <h1 class="fs-5">Showcase your talents </h1>
              <p class="lead text-700">Posh empowers you to show what you are capable of.</p>
              <hr class="short text-500 mx-auto" />
              <div class="row justify-content-center my-6">
                <div class="col-7 col-sm-4 col-md-3">
                  <select class="form-select form-select-lg text-1100" id="filter" name="filter" data-filter-NAV="data-filter-NAV">
                    <option class="isotope-nav active" value="*" data-filter="*" selected="selected"> Viewing all</option>
                    <option class="isotope-nav" value=".digital" data-filter=".digital"> Digital </option>
                    <option class="isotope-nav" value=".animation" data-filter=".animation"> Animation</option>
                    <option class="isotope-nav" value=".print" data-filter=".print">Print </option>
                  </select>
                </div>
              </div>
              <div class="row text-start" data-isotope='{"layoutMode":"packery"}'>
                <div class="col-lg-6 isotope-item animation"><a class="text-white" href="<cms:show k_site_link />pages/portfolio-details.html">
                    <div class="hoverbox mb-4 position-relative">
                      <div class="bg-holder overlay" style="background-image:url(<cms:show k_site_link />assets/images/s-01.png);">
                      </div>
                      <!--/.bg-holder-->

                      <div class="pt-11 ps-4 pb-3">
                        <h5 class="mt-3 text-white">Website for Sarah and Mike </h5>
                        <p>Website </p>
                      </div>
                    </div>
                  </a></div>
                <div class="col-lg-6 isotope-item digital"><a class="text-white" href="<cms:show k_site_link />pages/portfolio-details.html">
                    <div class="hoverbox mb-4 position-relative">
                      <div class="bg-holder overlay" style="background-image:url(<cms:show k_site_link />assets/images/s-02.png);">
                      </div>
                      <!--/.bg-holder-->

                      <div class="pt-11 ps-4 pb-3">
                        <h5 class="mt-3 text-white">Wildlife videography of the North </h5>
                        <p>Digital </p>
                      </div>
                    </div>
                  </a></div>
                <div class="col-lg-6 isotope-item print"><a class="text-white" href="<cms:show k_site_link />pages/portfolio-details.html">
                    <div class="hoverbox mb-4 position-relative">
                      <div class="bg-holder overlay" style="background-image:url(<cms:show k_site_link />assets/images/s-03.png);">
                      </div>
                      <!--/.bg-holder-->

                      <div class="pt-11 ps-4 pb-3">
                        <h5 class="mt-3 text-white">Design of the coolest interior furniture </h5>
                        <p>Animation </p>
                      </div>
                    </div>
                  </a></div>
                <div class="col-lg-6 isotope-item animation"><a class="text-white" href="<cms:show k_site_link />pages/portfolio-details.html">
                    <div class="hoverbox mb-4 position-relative">
                      <div class="bg-holder overlay" style="background-image:url(<cms:show k_site_link />assets/images/s-04.png);">
                      </div>
                      <!--/.bg-holder-->

                      <div class="pt-11 ps-4 pb-3">
                        <h5 class="mt-3 text-white">Tale of a natural bridge </h5>
                        <p>Design </p>
                      </div>
                    </div>
                  </a></div>
                <div class="col-lg-6 isotope-item print"><a class="text-white" href="<cms:show k_site_link />pages/portfolio-details.html">
                    <div class="hoverbox mb-4 position-relative">
                      <div class="bg-holder overlay" style="background-image:url(<cms:show k_site_link />assets/images/s-05.png);">
                      </div>
                      <!--/.bg-holder-->

                      <div class="pt-11 ps-4 pb-3">
                        <h5 class="mt-3 text-white">The horse race </h5>
                        <p>Photography </p>
                      </div>
                    </div>
                  </a></div>
                <div class="col-lg-6 isotope-item digital"><a class="text-white" href="<cms:show k_site_link />pages/portfolio-details.html">
                    <div class="hoverbox mb-4 position-relative">
                      <div class="bg-holder overlay" style="background-image:url(<cms:show k_site_link />assets/images/s-06.png);">
                      </div>
                      <!--/.bg-holder-->

                      <div class="pt-11 ps-4 pb-3">
                        <h5 class="mt-3 text-white">Grizzly Bear</h5>
                        <p>Animation</p>
                      </div>
                    </div>
                  </a></div>
              </div>
            </div>
            <!-- end of .container-->

          </section>
          <!-- <section> close ============================-->
          <!-- ============================================-->  

<hr><hr><hr>
<h2>Portfolio List Layout 2</h2>
          <!-- ============================================-->
          <!-- <section> begin ============================-->
          <section class="text-center">

            <div class="container">
              <h1 class="fs-5">Discover Posh </h1>
              <p class="lead text-700">Showcase for your all latest works</p>
              <hr class="short text-500 mx-auto" />
              <div class="row justify-content-center my-6">
                <div class="col-7 col-sm-4 col-md-3">
                  <select class="form-select form-select-lg text-1100" id="filter" name="filter" data-filter-NAV="data-filter-NAV">
                    <option class="isotope-nav active" value="*" data-filter="*" selected="selected"> Viewing all</option>
                    <option class="isotope-nav" value=".digital" data-filter=".digital"> Digital </option>
                    <option class="isotope-nav" value=".animation" data-filter=".animation"> Animation</option>
                    <option class="isotope-nav" value=".print" data-filter=".print">Print </option>
                  </select>
                </div>
              </div>
              <div class="row text-start" data-isotope='{"layoutMode":"packery"}'>
                <div class="col-lg-6 mb-6 isotope-item animation"><a href="<cms:show k_site_link />pages/portfolio-details.html">
                    <div class="hoverbox"> <img class="rounded-1" src="<cms:show k_site_link />assets/images/s-01.png" alt="" />
                      <div class="hoverbox-content d-flex justify-content-center align-items-center background-semi-transparent text-center row text-white">
                        <div class="col"> <span class="icon-Add fs-7"> </span></div>
                      </div>
                    </div>
                  </a>
                  <h5 class="mt-3 mb-1"> <a class="link-1100" href="<cms:show k_site_link />pages/portfolio-details.html"> Website for Sarah and Mike </a></h5>
                  <p class="text-700">Animation </p>
                </div>
                <div class="col-lg-6 mb-6 isotope-item digital"><a href="<cms:show k_site_link />pages/portfolio-details.html">
                    <div class="hoverbox"> <img class="rounded-1" src="<cms:show k_site_link />assets/images/s-02.png" alt="" />
                      <div class="hoverbox-content d-flex justify-content-center align-items-center background-semi-transparent text-center row text-white">
                        <div class="col"> <span class="icon-Add fs-7"> </span></div>
                      </div>
                    </div>
                  </a>
                  <h5 class="mt-3 mb-1"> <a class="link-1100" href="<cms:show k_site_link />pages/portfolio-details.html"> Wildlife videography of the North </a></h5>
                  <p class="text-700">Design </p>
                </div>
                <div class="col-lg-6 mb-6 isotope-item print"><a href="<cms:show k_site_link />pages/portfolio-details.html">
                    <div class="hoverbox"> <img class="rounded-1" src="<cms:show k_site_link />assets/images/s-03.png" alt="" />
                      <div class="hoverbox-content d-flex justify-content-center align-items-center background-semi-transparent text-center row text-white">
                        <div class="col"> <span class="icon-Add fs-7"> </span></div>
                      </div>
                    </div>
                  </a>
                  <h5 class="mt-3 mb-1"> <a class="link-1100" href="<cms:show k_site_link />pages/portfolio-details.html"> Design of the coolest interior furniture</a></h5>
                  <p class="text-700">Videography </p>
                </div>
                <div class="col-lg-6 mb-6 isotope-item animation"><a href="<cms:show k_site_link />pages/portfolio-details.html">
                    <div class="hoverbox"> <img class="rounded-1" src="<cms:show k_site_link />assets/images/s-04.png" alt="" />
                      <div class="hoverbox-content d-flex justify-content-center align-items-center background-semi-transparent text-center row text-white">
                        <div class="col"> <span class="icon-Add fs-7"> </span></div>
                      </div>
                    </div>
                  </a>
                  <h5 class="mt-3 mb-1"> <a class="link-1100" href="<cms:show k_site_link />pages/portfolio-details.html">Tale of a natural bridge </a></h5>
                  <p class="text-700">Photography </p>
                </div>
                <div class="col-lg-6 mb-6 isotope-item print"><a href="<cms:show k_site_link />pages/portfolio-details.html">
                    <div class="hoverbox"> <img class="rounded-1" src="<cms:show k_site_link />assets/images/s-05.png" alt="" />
                      <div class="hoverbox-content d-flex justify-content-center align-items-center background-semi-transparent text-center row text-white">
                        <div class="col"> <span class="icon-Add fs-7"> </span></div>
                      </div>
                    </div>
                  </a>
                  <h5 class="mt-3 mb-1"> <a class="link-1100" href="<cms:show k_site_link />pages/portfolio-details.html"> The horse race </a></h5>
                  <p class="text-700">Website </p>
                </div>
                <div class="col-lg-6 mb-6 isotope-item digital"><a href="<cms:show k_site_link />pages/portfolio-details.html">
                    <div class="hoverbox"> <img class="rounded-1" src="<cms:show k_site_link />assets/images/s-06.png" alt="" />
                      <div class="hoverbox-content d-flex justify-content-center align-items-center background-semi-transparent text-center row text-white">
                        <div class="col"> <span class="icon-Add fs-7"> </span></div>
                      </div>
                    </div>
                  </a>
                  <h5 class="mt-3 mb-1"> <a class="link-1100" href="<cms:show k_site_link />pages/portfolio-details.html"> Grizzly Bear </a></h5>
                  <p class="text-700">Animation </p>
                </div>
              </div>
            </div>
            <!-- end of .container-->

          </section>
          <!-- <section> close ============================-->
          <!-- ============================================-->         

<hr><hr><hr>
<h2>Portfolio Layout 3</h2>
           <!-- ============================================-->
          <!-- <section> begin ============================-->
          <section class="text-center">

            <div class="container">
              <h1 class="fs-5">See Our Works </h1>
              <p class="lead text-700">Posh helps you showcase your best works.</p>
              <hr class="short text-500 mx-auto" />
              <div class="row justify-content-center my-6">
                <div class="col-7 col-sm-4 col-md-3">
                  <select class="form-select form-select-lg text-1100" id="filter" name="filter" data-filter-NAV="data-filter-NAV">
                    <option class="isotope-nav active" value="*" data-filter="*" selected="selected"> Viewing all</option>
                    <option class="isotope-nav" value=".digital" data-filter=".digital"> Digital </option>
                    <option class="isotope-nav" value=".animation" data-filter=".animation"> Animation</option>
                    <option class="isotope-nav" value=".print" data-filter=".print">Print </option>
                  </select>
                </div>
              </div>
              <div class="row text-start" data-isotope='{"layoutMode":"packery"}'>
                <div class="col-lg-6 isotope-item animation"><a class="text-white" href="<cms:show k_site_link />pages/portfolio-details.html">
                    <div class="hoverbox mb-4"> <img class="rounded-1" src="<cms:show k_site_link />assets/images/s-01.png" alt="" />
                      <div class="hoverbox-content d-flex justify-content-center align-items-center bg-1100 row text-center">
                        <div class="col">
                          <p class="mb-2 d-inline-block">Website </p><br />
                          <h5 class="text-white d-inline-block mt-0">Website for Sarah and Mike </h5><br />
                          <div class="btn btn-outline-white btn-xs mt-4">View Details</div>
                        </div>
                      </div>
                    </div>
                  </a></div>
                <div class="col-lg-6 isotope-item digital"><a class="text-white" href="<cms:show k_site_link />pages/portfolio-details.html">
                    <div class="hoverbox mb-4"> <img class="rounded-1" src="<cms:show k_site_link />assets/images/s-02.png" alt="" />
                      <div class="hoverbox-content d-flex justify-content-center align-items-center bg-1100 row text-center">
                        <div class="col">
                          <p class="mb-2 d-inline-block">Design </p><br />
                          <h5 class="text-white d-inline-block mt-0">Wildlife videography of the North</h5><br />
                          <div class="btn btn-outline-white btn-xs mt-4">View Details </div>
                        </div>
                      </div>
                    </div>
                  </a></div>
                <div class="col-lg-6 isotope-item print"><a class="text-white" href="<cms:show k_site_link />pages/portfolio-details.html">
                    <div class="hoverbox mb-4"> <img class="rounded-1" src="<cms:show k_site_link />assets/images/s-03.png" alt="" />
                      <div class="hoverbox-content d-flex justify-content-center align-items-center bg-1100 row text-center">
                        <div class="col">
                          <p class="mb-2 d-inline-block">Animation </p><br />
                          <h5 class="text-white d-inline-block mt-0">Design of the coolest interior furniture</h5><br />
                          <div class="btn btn-outline-white btn-xs mt-4">View Details </div>
                        </div>
                      </div>
                    </div>
                  </a></div>
                <div class="col-lg-6 isotope-item animation"><a class="text-white" href="<cms:show k_site_link />pages/portfolio-details.html">
                    <div class="hoverbox mb-4"> <img class="rounded-1" src="<cms:show k_site_link />assets/images/s-04.png" alt="" />
                      <div class="hoverbox-content d-flex justify-content-center align-items-center bg-1100 row text-center">
                        <div class="col">
                          <p class="mb-2 d-inline-block">Website </p><br />
                          <h5 class="text-white d-inline-block mt-0">Tale of natural bridge</h5><br />
                          <div class="btn btn-outline-white btn-xs mt-4">View Details</div>
                        </div>
                      </div>
                    </div>
                  </a></div>
                <div class="col-lg-6 isotope-item print"><a class="text-white" href="<cms:show k_site_link />pages/portfolio-details.html">
                    <div class="hoverbox mb-4"> <img class="rounded-1" src="<cms:show k_site_link />assets/images/s-05.png" alt="" />
                      <div class="hoverbox-content d-flex justify-content-center align-items-center bg-1100 row text-center">
                        <div class="col">
                          <p class="mb-2 d-inline-block">Photography </p><br />
                          <h5 class="text-white d-inline-block mt-0">The horse </h5><br />
                          <div class="btn btn-outline-white btn-xs mt-4">View Details </div>
                        </div>
                      </div>
                    </div>
                  </a></div>
                <div class="col-lg-6 isotope-item digital"><a class="text-white" href="<cms:show k_site_link />pages/portfolio-details.html">
                    <div class="hoverbox mb-4"> <img class="rounded-1" src="<cms:show k_site_link />assets/images/s-06.png" alt="" />
                      <div class="hoverbox-content d-flex justify-content-center align-items-center bg-1100 row text-center">
                        <div class="col">
                          <p class="mb-2 d-inline-block">Animation</p><br />
                          <h5 class="text-white d-inline-block mt-0">Grizzly Bear</h5><br />
                          <div class="btn btn-outline-white btn-xs mt-4">View Details</div>
                        </div>
                      </div>
                    </div>
                  </a></div>
              </div>
            </div>
            <!-- end of .container-->

          </section>
          <!-- <section> close ============================-->
          <!-- ============================================-->        

<hr><hr><hr>
<h2>Portfolio Single Detail</h2>
          <!-- ============================================-->
          <!-- <section> begin ============================-->
          <section>

            <div class="container">
              <div class="row">
                <div class="col-xl-8">
                  <h1 class="fs-6 fs-md-5 mb-4">The Details </h1>
                  <p class="lead text-700">Released in 2017 with a vision to renovate the web design; Posh Today is a global trend setter in beautiful design and modern user experience.</p>
                </div>
              </div>
              <div class="row mt-5">
                <div class="col"> <img src="<cms:show k_site_link />assets/images/careers-header.jpg" alt="" /></div>
              </div>
              <div class="row mt-8">
                <div class="col-md-6 col-lg-4">
                  <h5>Client: </h5>
                  <p class="text-700 fw-light">ThemeWagon Inc.</p>
                  <h6 class="mt-4">Project Link: </h6><a href="#!"> https://themewagon.com/</a>
                  <h5 class="mt-6">Services: </h5>
                  <ul class="bullet-inside ps-0 text-700 fw-light">
                    <li>Branding </li>
                    <li>Print Collateral </li>
                    <li>Web Design </li>
                    <li>Digital Strategy </li>
                  </ul>
                </div>
                <div class="col-md-6 col-lg-7 mt-5 mt-md-0">
                  <h4>Brief </h4>
                  <hr class="short text-400" />
                  <p class="fw-light text-800">From a simple hyperlink to the contact form, Posh is packed with so many elements and features to do more.</p>
                  <p class="fw-light text-800">It's important to launch a startup or personal website quickly. Posh will allow you to make a cool site in no time. And you don't have to bargain with off-shore freelancers or virtual workers anyway; even if you don't know coding. </p>
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