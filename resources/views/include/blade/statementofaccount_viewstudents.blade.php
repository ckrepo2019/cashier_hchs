
@if(count($students) > 0)
    @foreach($students as $student)
        <div class="col-md-12">
            <div class="card card-success collapsed-card soa-card" data-string="{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}<" id="{{$student->id}}">
                <div class="card-header">
                    <h3 class="card-title">{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}</h3>
    
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool soa-btn-viewdetailsexpand text-info" id="{{$student->id}}" data-card-widget="maximize"><i class="fa fa-expand"></i></button>
                        <button type="button" class="btn btn-tool soa-btn-viewdetailscollapse text-info" id="{{$student->id}}" data-card-widget="collapse"><i class="fa fa-plus"></i></button>
                    </div>
            <!-- /.card-tools -->
                 </div>
          <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <button type="button" class="btn text-info printstatementofacct" exporttype="excel"  studid="{{$student->id}}" data-toggle="tooltip" data-placement="bottom" title="Excel"><i class="fa fa-file-excel-o"></i></button>
                            <button type="button" class="btn text-info printstatementofacct" exporttype="pdf"   studid="{{$student->id}}" data-toggle="tooltip" data-placement="bottom" title="Pdf"><i class="fa fa-file-pdf-o"></i></button>
                        </div>
                    </div>
                    <div class="row p-2" id="soa-stud-{{$student->id}}">

                    </div>
                </div>
            <!-- /.card-body -->
            </div>
        <!-- /.card -->
        </div>
    @endforeach
@endif