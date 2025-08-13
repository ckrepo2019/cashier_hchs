

<script>
    var $ = jQuery;
    $(document).ready(function(){
        $(".soa-filterstudent").on("keyup", function() {
            var input = $(this).val().toUpperCase();
            var visibleCards = 0;
            var hiddenCards = 0;

            $(".container").append($("<div class='card-group card-group-filter'></div>"));


            $(".soa-card").each(function() {
                if ($(this).data("string").toUpperCase().indexOf(input) < 0) {

                $(".card-group.card-group-filter:first-of-type").append($(this));
                $(this).hide();
                hiddenCards++;

                } else {

                $(".card-group.card-group-filter:last-of-type").prepend($(this));
                $(this).show();
                visibleCards++;

                if (((visibleCards % 4) == 0)) {
                    $(".container").append($("<div class='card-group card-group-filter'></div>"));
                }
                }
            });

        });
    })
</script>
<div id="soaallstudentscontainer" class="soa-screen screen oe_hidden">
            <div class="screen-content">
                <section class="top-content">
                <span class="button back">
                    <i class="fa fa-angle-double-left"></i>
                    Cancel
                </span>
                </section>
                <section class="full-content">
                <div class="window">
                    <section class="subwindow collapsed">
                        <div class="subwindow-container collapsed">
                            <div class="subwindow-container-fix client-details-contents">
                            </div>
                        </div>
                    </section>
                    <section class="subwindow">
                    <div class="subwindow-container">
                        <div class="subwindow-container-fix touch-scrollable scrollable-y">
                        
                        <div class="menu-header">
                            STATEMENT OF ACCOUNT
                        </div>
                        <div id="soa-stud-name" class="menu-name mt-2">
                            
                        </div>
                        <div class="row p-2">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text">S.Y </span>
                                        </div>
                                        <select class="form-control" id="soaschoolyear">

                                        </select>
                                    </div>
                                    <!-- /.input group -->
                                    </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text">SEMESTER </span>
                                        </div>
                                        <select class="form-control" id="soasemester">
                                            
                                        </select>
                                    </div>
                                    <!-- /.input group -->
                                    </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text">MONTH SETUP </span>
                                        </div>
                                        <select class="form-control" id="soamonthsetup">
                                            
                                        </select>
                                    </div>
                                    <!-- /.input group -->
                                    </div>
                            </div>
                            <div class="col-md-1">
                                
                                <button type="button" class="btn btn-primary float-right p-2 bg-primary btn-block" id="btn-soa-generate" style="background:unset; font-size:unset;border:none; border-radius:4px;">Generate</button>
                            </div>
                        </div>
                        <div class="row p-2">
                            <div class=" col-md-8">
                                <input class="soa-filterstudent form-control" placeholder="Search student" />
                            </div>
                        </div>
                        <div class="row p-2" id="soa-studenscontainer">

                        </div>
                        </div>
                    </div>
                    </section>
                </div>
                </section>
            </div>
            </div>
            {{-- <div id="soaviewstudentcontainer" class="soa-screen screen oe_hidden">
                                <div class="screen-content">
                                <section class="top-content">
                                    <span class="button back">
                                    <i class="fa fa-angle-double-left"></i>
                                    Cancel
                                    </span>
                                </section>
                                <section class="full-content">
                                    <div class="window">
                                    <section class="subwindow collapsed">
                                        <div class="subwindow-container collapsed">
                                            <div class="subwindow-container-fix client-details-contents">
                                            </div>
                                        </div>
                                    </section>
                                    <section class="subwindow">
                                        <div class="subwindow-container">
                                        <div class="subwindow-container-fix touch-scrollable scrollable-y">
                                            
                                            <div class="menu-header">
                                            STATEMENT OF ACCOUNT
                                            </div>
                                            <div id="assessment-name" class="menu-name mt-2">
                                            
                                            </div>
                                            <div class="row p-2">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">S.Y </span>
                                                        </div>
                                                        <select class="form-control" id="soaschoolyear">
            
                                                        </select>
                                                        </div>
                                                        <!-- /.input group -->
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">SEMESTER </span>
                                                        </div>
                                                        <select class="form-control" id="soasemester">
                                                            
                                                        </select>
                                                        </div>
                                                        <!-- /.input group -->
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">MONTH SETUP </span>
                                                        </div>
                                                        <select class="form-control" id="soamonthsetup">
                                                            
                                                        </select>
                                                        </div>
                                                        <!-- /.input group -->
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    
                                                    <button type="button" class="btn btn-primary float-right p-2 bg-primary btn-block" id="btn-soa-generate" style="background:unset; font-size:unset;border:none; border-radius:4px;">Generate</button>
                                                </div>
                                            </div>
                                            <div class="row p-2" id="soa-viewstudentcontainer">
            
                                            </div>
                                        </div>
                                        </div>
                                    </section>
                                    </div>
                                </section>
                                </div>
                            </div> --}}