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
                        <div class="row">
                        <div class="col-md-4 col-sm-6 col-xs-12">
                        <?php if(!isset($course->video) || $course->video == ""){?>
                        <img src="<?php echo get_course_img($course->image); ?>" class="img-responsive" alt="">
                        <?php }else{?>
                        <iframe style="width:100%;height:400px" src="https://www.youtube.com/embed/<?php echo $course->video;?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                        <?php }?>
                        </div>
                        <div class="col-md-8 col-sm-6 col-xs-12">
                        <?php echo $course->description ?>
                        </div>

                        <?php if(isset($course->pdf_file) && $course->pdf_file !=""){?>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <a href="<?php echo get_course_img($course->pdf_file); ?>" class="btn btn-primary" target="_blank">download pdf</a>
                        </div>
                        <?php }?>
                        <div class="col-md-6 col-sm-6 col-xs-12">
        <a href="<?php echo URL_HOME_SEARCH_TUTOR.'/'.$course->slug;?>" class="btn btn-primary">Find Tutor</a>
        </div>
        </div>
                    </div>
	
        
            
        </div></div></div></div>

    </section>