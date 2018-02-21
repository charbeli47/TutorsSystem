<!-- Page Title Wrap  -->
    <div class="page-title-wrap">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                </div>
            </div>
        </div>
    </div>
    <!-- Page Title Wrap  -->
<section class="dashboard-section">
        <div class="container">
        <div class="row offcanvas offcanvas-right row-margin">
        <div class="col-xs-12 col-sm-12 dashboard-content ">
                    <!-- Breadcrumb -->
                    <ol class="breadcrumb dashcrumb">
                        <li><a href="<?php echo SITEURL;?>"><?php echo get_languageword("home");?></a></li>
                        <li class="active"><?php if(isset($course->name)) echo $course->name;?></li>
                    </ol>
                    <!-- Breadcrumb ends -->

                    <!-- Dashboard Panel -->
                    <div class="dashboard-panel">
                        <div class="row">
                            <div class="col-sm-12">
                                <h4><?php if(isset($course->name)) echo $course->name;?></h4>
                            </div>
                        </div>
                        <hr>
                        <div id="course_list">
                        <div class="col-xs-4 col-sm-4">
                        <img src="<?php echo get_course_img($course->image); ?>" alt="" class="img-responsive">
                        </div>
                        <div class="col-xs-8 col-sm-8">
                        <?php echo $course->description ?>
                        </div>
                        <?php if(isset($course->pdf_file) && $course->pdf_file !=""){?>
                        <div class="col-xs-6 col-sm-6">
                            <a href="<?php echo get_course_img($course->pdf_file); ?>" class="btn btn-primary" target="_blank">download pdf</a>
                        </div>
                        <?php }?>
                        <div class="col-xs-6 col-sm-6">
        <a href="<?php echo URL_HOME_SEARCH_TUTOR.'/'.$course->slug;?>" class="btn btn-primary">Find Tutor</a>
        </div>
                    </div>
	
        
            
        </div></div></div></div>

    </section>